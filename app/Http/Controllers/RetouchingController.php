<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Inspection;
use App\Models\Retouching;
use Illuminate\Http\Request;
use App\Models\CuttingGrading;
use Illuminate\Support\Carbon;
use App\Models\RawMaterialLots;
use App\Models\RetouchingChecking;
use Illuminate\Support\Facades\DB;
use App\Models\ForwardTraceability;
use App\Models\RefinedMaterialLots;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RetouchingController extends Controller
{

    public function index()
    {
        return view('retouching.index');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            // $data = Retouching::all()->unique('ilc_cutting');
            $data = Retouching::select('ilc', 'ilc_cutting', 'id_supplier', 'tanggal', 'inspection',  DB::raw('SUM(berat) as total_berat'), DB::raw('MAX(created_at) as created_at'))
                ->groupBy('ilc', 'ilc_cutting', 'id_supplier', 'tanggal', 'inspection')
                ->orderBy('created_at', 'desc')
                ->get();

            $data->transform(function ($item) {
                // Mengambil ID terkait
                $relatedId = Retouching::where('ilc_cutting', $item->ilc_cutting)
                    ->where('id_supplier', $item->id_supplier)
                    // ->where('ekspor', $item->ekspor)
                    // ->where('checking', $item->checking)
                    ->orderBy('created_at', 'desc')
                    ->value('id');
                $item->id = $relatedId;

                $item->tanggal = Carbon::parse($item->created_at)->format('d-m-Y');
                return $item;
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_supplier', function ($row) {
                    if ($row->id_supplier) {
                        return $row->supplier->nama_supplier;
                    } else {
                        return '-';
                    }
                })
                ->editColumn('inspection', function ($row) {
                    if ($row->inspection) {
                        return $row->inspection . '%';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('total_berat', function ($row) {
                    return $row->total_berat;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    $btn .= ' <a href="/product-log/' . $row->ilc . '"<i class="ri-arrow-right-line"></i></a>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getBerat($ilc, $no_loin)
    {
        $berat = Retouching::where('ilc', $ilc)->where('no_loin', $no_loin)->value('berat');
        return response()->json($berat);
    }

    public function getAllCutting(Request $request)
    {
        if ($request->ajax()) {
            $data = Cutting::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="kodeILC(\'' . $row->ilc_cutting . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getNumberLoinCutting($ilc_cutting)
    {
        $noLoinList = CuttingGrading::where('ilc_cutting', $ilc_cutting)
            ->orderBy('no_loin', 'asc')
            ->distinct()
            ->pluck('no_loin');

        return response()->json($noLoinList);
    }

    public function getNumberLoinRetouching($ilc_cutting)
    {
        $noLoinList = Retouching::where('ilc_cutting', $ilc_cutting)
            ->orderBy('no_loin', 'asc')
            ->distinct()
            ->select('no_loin', 'sisa_berat')
            ->get();


        return response()->json($noLoinList);
    }

    public function calculateLoin($ilc_cutting, $no_loin)
    {
        $beratLoin = CuttingGrading::where('ilc_cutting', $ilc_cutting)
            ->where('no_loin', $no_loin)
            ->pluck('berat');
        return response()->json($beratLoin);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ilc_cutting' => 'required',
            'no_loin' => 'required',
            'berat' => 'required|numeric',
        ], [
            'ilc_cutting.required' => 'ILC Cutting Harus Diisi',
            'berat.required' => 'Berat Harus Diisi',
            'berat.numeric' => 'Berat Harus Angka',
            'no_loin.required' => 'No Loin Harus Diisi',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingEntry = Retouching::where('ilc_cutting', $request->ilc_cutting)
                ->where('no_loin', $request->no_loin)
                ->exists();

            if ($existingEntry) {
                $validator->errors()->add('ilc_cutting', 'ILC Cutting sudah ada.');
                $validator->errors()->add('no_loin', 'No Sudah sudah ada.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Ambil data Cutting berdasarkan ilc_cutting
            $cutting = Cutting::where('ilc_cutting', $request->ilc_cutting)->first();
            if (!$cutting) {
                throw new \Exception('Data cutting tidak ditemukan.');
            }
            $id_supplier = $cutting->id_supplier;
            $ilc = $cutting->ilc;

            // Ambil nilai inspection dari table Retouching, jika ada
            $inspectionRecord = Retouching::where('ilc', $ilc)->first('inspection');
            $inspection = $inspectionRecord ? $inspectionRecord->inspection : null;

            // Simpan data ke table Retouching
            $save = new Retouching();
            $save->id_supplier   = $id_supplier;
            $save->ilc           = $ilc;
            $save->ilc_cutting   = $request->ilc_cutting;
            $save->no_loin       = $request->no_loin;
            $save->tanggal       = Carbon::now()->format('Y-m-d');
            $save->berat         = $request->berat;
            $save->sisa_berat    = $request->berat;
            $save->inspection    = $inspection;
            $save->save();

            // Simpan data ke table Inspection dengan stage "Retouching"
            Inspection::create([
                'ilc'   => $ilc,
                'stage' => "Retouching",
            ]);

            // Perbarui data pada table ForwardTraceability jika tanggal_retouching masih null
            $forwardTraceability = ForwardTraceability::where('ilc', $ilc)->first();
            if ($forwardTraceability && $forwardTraceability->tanggal_retouching === null) {
                ForwardTraceability::where('ilc', $ilc)->update([
                    'tanggal_retouching' => Carbon::now()->format('Y-m-d'),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Logging error bisa dilakukan di sini jika diperlukan
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Retouching $retouching, $id)
    {
        try {
            $del_receiving = $retouching::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
