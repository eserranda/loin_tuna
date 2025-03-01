<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Packing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PackingController extends Controller
{
    public function index()
    {
        return view('packing.index');
    }

    public function getAllDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Packing::latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                // ->editColumn('total_price', function ($row) {
                //     return 'Rp.' . number_format($row->total_price, 0, ',', '.');
                // })
                // ->addColumn('status', function ($row) {
                //     if ($row->status == 'pending') {
                //         return '<span class="badge text-bg-warning">' . $row->status . '</span>';
                //     } else if ($row->status == 'confirmed') {
                //         return '<span class="badge text-bg-success">' . $row->status . '</span>';
                //     } else if ($row->status == 'rejected') {
                //         return '<span class="badge text-bg-danger">' . $row->status . '</span>';
                //     }
                // })
                ->editColumn('tanggal', function ($row) {
                    return  $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    $btn .= '<a href="/packing-po/' . $row->po_number . '"<i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_number' => 'required|string|max:255|unique:packings',
        ], [
            'po_number.required' => 'PO Number harus diisi',
            'po_number.max' => 'PO Number maksimal 255 karakter',
            'po_number.string' => 'PO Number harus berupa string',
            'po_number.unique' => 'PO Number sudah ada',
        ]);

        $detail_po = Order::where('po_number', $request->po_number)->first();

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            DB::beginTransaction();

            Packing::create([
                'po_number' => $request->po_number,
                'user_id' => $detail_po->user_id,
            ]);

            Order::where('po_number', $request->po_number)->update([
                'is_packed' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Packing $packing, $id)
    {
        try {
            DB::beginTransaction();
            $del_po = Packing::where('id', $id)->first();

            Order::where('po_number', $del_po->po_number)->update([
                'is_packed' => false,
            ]);

            $del_packing = $packing::findOrFail($id);
            $del_packing->delete();

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
