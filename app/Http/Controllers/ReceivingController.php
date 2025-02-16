<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ForwardTraceability;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ReceivingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('receiving.index');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = Receiving::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->format('d-m-Y');
                })
                ->editColumn('inspection', function ($row) {
                    if ($row->inspection != "") {
                        return ($row->inspection . '%');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('action', function ($row) {
                    // $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->id  . '\', \'' . $row->ilc . '\')"><i class="text-danger ri-delete-bin-5-line"></i></a>';
                    $btn .= ' <a href="/raw-material/' . $row->ilc . '"<i class="ri-arrow-right-line ms-2"></i></a>';
                    // $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $ilc = null;
        if ($request->id_supplier != null) {
            $supplier = Supplier::find($request->id_supplier);
            $now = Carbon::now();
            $year = $now->format('y');
            $julian_day = $now->format('z') + 1;
            $month = $now->format('m');
            $julian_date = $year . $julian_day . $month;

            $ilc = $supplier->kode_supplier . '-' . $julian_date;
        }
        $request->merge(['ilc' => $ilc]);

        $validator = Validator::make($request->all(), [
            'ilc' => 'required|unique:receivings',
            'id_supplier' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
        ], [
            'ilc.unique' => 'ILC Sudah Ada',
            'ilc.required' => 'Kode ILC gagal di generate',
            'id_supplier.required' => 'Supplier Wajib Diisi',
            'id_supplier.exists' => 'Supplier Tidak Valid',
            'tanggal.required' => 'Tanggal Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan data di table Receiving
            $receiving = Receiving::create([
                'ilc'         => $ilc,
                'id_supplier' => $request->id_supplier,
                'tanggal'     => $request->tanggal,
            ]);

            // Simpan data di table Inspection, stage "Receiving"
            $inspection = Inspection::create([
                'ilc'   => $ilc,
                'stage' => "Receiving"
            ]);

            // Simpan data di table ForwardTraceability
            $forwardTraceability = ForwardTraceability::create([
                'ilc'              => $request->ilc,
                'tanggal_receiving' => $request->tanggal,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Receiving created successfully.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat receiving data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Receiving $receiving, $id, $ilc)
    {
        try {
            $del_receiving = $receiving::findOrFail($id);
            $del_receiving->delete();

            // ReceivingChecking::where('ilc', $ilc)->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
