<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return view('produk.index');
    }

    public function getAllDataProductLog(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductLog::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_produk', function ($row) {
                    if ($row->id_produk) {
                        return $row->produk->nama;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="print(\'' . $row->id_produk  . '\', \'' . $row->ilc . '\')"><i class="ri-printer-fill mx-1"></i></a>';
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function productWithCustomerGroup(Request $request, $customer_group)
    {
        if ($request->ajax()) {
            $data = Product::where('customer_group', $customer_group)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="setProduct(\'' . $row->id . '\', \'' . $row->nama . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getAllData(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line mx-3"></i></a>';
                    // $btn .= '<a href="javascript:void(0);" onclick="kodeILC(\'' . $row->ilc_cutting . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // public function getAllDataProductLog(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = ProductLog::latest('created_at')->get();
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('id_produk', function ($row) {
    //                 if ($row->id_produk) {
    //                     return $row->produk->nama;
    //                 } else {
    //                     return '-';
    //                 }
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line mx-3"></i></a>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }


    public function productLogGetData(Request $request, $customer_group)
    {
        if ($request->ajax()) {
            $data = Product::where('customer_group', $customer_group)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="setProduct(\'' . $row->id . '\', \'' . $row->nama . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:products,kode',
            'nama' => 'required|unique:products,nama',
            'customer_group' => 'required',
        ], [
            'kode.required' => 'Kode produk harus diisi',
            'kode.unique' => 'Kode produk sudah ada',
            'nama.required' => 'Nama produk harus diisi',
            'nama.unique' => 'Nama produk sudah ada',
            'customer_group.required' => 'Customer group harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages(),
            ], 422);
        }

        $products = Product::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'customer_group' => $request->customer_group,
        ]);

        if ($products) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Produk Gagal Disimpan',
            ]);
        }
    }


    public function destroy(Product $products, $id)
    {
        try {
            $del_receiving = $products::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
