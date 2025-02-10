<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Packing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                    // $btn = '<div class="d-flex justify-content-start align-items-center">';
                    // $btn = ' <a href="/order/detail-order/' . $row->po_number . '"<i class="ri-file-info-line"></i></a>';
                    // $btn .= '</div>';
                    // return $btn;

                    $btn = '<a href="javascript:void(0);" onclick="POnumber(\'' . $row->po_number . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // dd($request->po_number);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $packing = Packing::create([
            'po_number' => $request->po_number,
            'user_id' => $detail_po->user_id,
            // 'tanggal' => Carbon::now()->format('Y-m-d'),
        ]);

        if ($packing) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
            ], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Packing $packing)
    {
        //
    }
}
