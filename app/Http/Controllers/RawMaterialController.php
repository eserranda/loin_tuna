<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\Receiving;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('raw-material.index');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = RawMaterial::latest('created_at')->get();
            // $data->transform(function ($item) {
            //     $item->tanggal = Carbon::parse($item->tanggal)->format('d-m-Y');
            //     return $item;
            // });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    $btn .= ' <a href="/rawmaterial/grading/' . $row->ilc . '"<i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getNoIkan($ilc)
    {
        $noIkanList = RawMaterial::where('ilc', $ilc)
            ->orderBy('no_loin', 'asc')
            ->pluck('no_loin');


        return response()->json($noIkanList);
    }

    public function calculateTotalWeight($ilc)
    {
        $totalBerat = RawMaterial::where('ilc', $ilc)->sum('berat');
        return response()->json([
            'totalBerat' => $totalBerat
        ]);
    }

    public function findManyWithILC(Request $request, $ilc)
    {
        if ($request->ajax()) {
            $data = RawMaterial::where('ilc', $ilc)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function getOneRawWithILC($ilc)
    {
        $data = Receiving::where('ilc', $ilc)->first();
        return view('raw-material.index', compact('data'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'berat' => 'required|numeric',
            'no_loin' => 'required|numeric',
            'grade' => 'required',
        ], [
            'ilc.unique' => 'ILC Sudah Ada',
            'berat.required' => 'Berat Ikan Wajib Diisi',
            'berat.numeric' => 'Berat Ikan Harus Berupa Angka',
            'no_loin.required' => 'Nomor Ikan Wajib Diisi',
            'no_loin.numeric' => 'Nomor Ikan Harus Berupa Angka',
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingEntry = RawMaterial::where('ilc', $request->ilc)
                ->where('no_loin', $request->no_loin)
                ->exists();

            if ($existingEntry) {
                $validator->errors()->add('no_loin', 'Nomor Loin sudah ada.');
                // $validator->errors()->add('ilc', 'Ilc sudah ada.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        RawMaterial::create([
            'ilc' => $request->ilc,
            'berat' => $request->berat,
            'no_loin' => $request->no_loin,
            'grade' => $request->grade,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berat Ikan Berhasil',
        ], 201);
    }


    public function gradingStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'berat' => 'required|numeric',
            'no_loin' => 'required|numeric',
            'grade' => 'required',
        ], [
            'ilc.unique' => 'ILC Sudah Ada',
            'berat.required' => 'Berat Ikan Wajib Diisi',
            'berat.numeric' => 'Berat Ikan Harus Berupa Angka',
            'no_loin.required' => 'Nomor Ikan Wajib Diisi',
            'no_loin.numeric' => 'Nomor Ikan Harus Berupa Angka',
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        RawMaterial::create([
            'ilc' => $request->ilc,
            'berat' => $request->berat,
            'no_loin' => $request->no_loin,
            'grade' => $request->grade,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berat Ikan Berhasil',
        ], 201);
    }

    public function nextNumber($ilc)
    {
        $lastLot = RawMaterial::where('ilc', $ilc)->orderBy('no_loin', 'desc')->first();
        $nextNoLoin = $lastLot ? $lastLot->no_loin + 1 : 1;
        return response()->json([
            'next_no_loin' => $nextNoLoin,
        ]);
    }

    public function destroy(RawMaterial $receiving, $id)
    {
        try {
            $del_receiving = $receiving::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
