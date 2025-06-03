<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function listProductByID($id)
    {


        $images = ProductImages::where('id_product', $id)->get();
        $product = Product::find($id);
        $product_name = $product->nama;
        $yen = $product->yen;
        $weight = $product->berat;
        $kode = $product->kode;
        $published = $product->created_at->format('d M Y');
        $deskripsi = $product->deskripsi;


        return view('list-product.detail', compact('images', 'product_name', 'yen', 'weight', 'kode', 'published', 'deskripsi'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'edit_kode' => 'required|unique:products,kode,' . $id,
            'edit_nama' => 'required|unique:products,nama,' . $id,
            'edit_harga' => 'required',
            'edit_berat' => 'required',
        ], [
            'edit_kode.required' => 'Kode produk harus diisi',
            'edit_kode.unique' => 'Kode produk sudah ada',
            'edit_harga.required' => 'Harga harus diisi',
            'edit_nama.required' => 'Nama produk harus diisi',
            'edit_nama.unique' => 'Nama produk sudah ada',
            'edit_berat.required' => 'Berat harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('edit_image')) {

            $file = $request->file('edit_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $filePath = 'uploads/images/products/' . $fileName;
            $file->move(public_path('uploads/images/products/'), $fileName);
        }

        $rupiah = $request->edit_harga;
        $getYen = Http::get('https://open.er-api.com/v6/latest/IDR');

        $data = $getYen->json();
        $yen = $rupiah * $data['rates']['JPY'];

        $update = Product::where('id', $id)->update([
            'kode' => $request->edit_kode,
            'nama' => $request->edit_nama,
            'harga' => $request->edit_harga,
            'yen' => $yen,
            'berat' => $request->edit_berat,
            'deskripsi' => $request->edit_deskripsi,
            'image' => $filePath ?? null,
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diupdate',
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Produk Gagal Diupdate',
            ]);
        }
    }

    public function saveImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4048',
        ], [
            'required' => ':attribute harus diisi',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berformat jpeg,png,jpg,gif',
            'max' => 'Ukuran :attribute maksimal 2048KB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages(),
            ], 422);
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $filePath = 'uploads/images/products/' . $fileName;
        $file->move(public_path('uploads/images/products/'), $fileName);

        $cekProductImages = Product::where('id', $request->input('id'))->value('image');
        if (!$cekProductImages) {
            Product::where('id', $request->input('id'))->update([
                'image' => $filePath
            ]);
        }

        $productImages = ProductImages::create([
            'id_product' => $request->input('id'),
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        if ($productImages) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        }
    }

    public function listProduct()
    {

        $product = Product::all();
        return view('list-product.index', compact('product'));
    }

    public function index()
    {
        return view('produk.index');
    }

    // data produk log mengungsi di sini 
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
                    // $btn = '<a href="javascript:void(0);" onclick="print(\'' . $row->id_produk  . '\', \'' . $row->ilc . '\')"><i class="ri-printer-fill mx-1"></i></a>';
                    if ($row->is_used == 0) {
                        $btn = '<button type="button" class="btn btn-sm btn-danger btn-icon waves-effect waves-light" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line"></i></button>';
                    } else {
                        $btn = '<button type="button" class="btn btn-sm btn-light btn-icon waves-effect waves-danger" disable><i class="ri-delete-bin-5-line"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function findById($id)
    {
        $data = Product::find($id);
        return response()->json($data);
    }

    public function productWithCustomerGroup(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="setProduct(\'' . $row->id . '\', \'' . $row->nama . '\', \'' . $row->berat . '\')"><i class="ri-arrow-right-line"></i></a>';
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
                ->editColumn('berat', function ($row) {
                    return $row->berat . ' Kg';
                })
                ->editColumn('harga', function ($row) {
                    return 'Rp' . number_format($row->harga, 0, ',', '.') . ' (¥ ' . number_format($row->yen, 1, ',', '.') . ')';
                })
                ->editColumn('images', function ($row) {
                    $images = $row->image;
                    $img = '';
                    if ($images) {
                        $img = '<img src="' . $images . '" alt="" class="img-thumbnail" style="width: 50px; height: 50px;">';
                    } else {
                        $img = '<img src="' . asset('uploads/images/no-image.jpg') . '" alt="" class="img-thumbnail" style="width: 50px; height: 50px;">';
                    }
                    return $img;
                })


                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-light btn-icon waves-effect waves-light" onclick="edit(' . $row->id . ')"><i class="ri-pencil-line text-info"></i></button>';

                    $btn .= '<button type="button" class="btn btn-sm btn-light btn-icon waves-effect waves-light mx-2" onclick="showModalAddImage(' . $row->id . ')"><i class="text-warning ri-image-add-line"></i></button>';

                    $btn .= '<button type="button" class="btn btn-sm btn-light btn-icon waves-effect waves-light" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line text-danger"></i></button>';

                    return $btn;
                })
                ->rawColumns(['action', 'images'])
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


    // fungsi ini sudah tidak di gunakan 
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
            'harga' => 'required',
            'berat' => 'required',
            // 'customer_group' => 'required',
        ], [
            'kode.required' => 'Kode produk harus diisi',
            'kode.unique' => 'Kode produk sudah ada',
            'harga.required' => 'Harga harus diisi',
            'nama.required' => 'Nama produk harus diisi',
            'nama.unique' => 'Nama produk sudah ada',
            'berat.required' => 'Berat harus diisi',
            // 'customer_group.required' => 'Customer group harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages(),
            ], 422);
        }

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $filePath = 'uploads/images/products/' . $fileName;
            $file->move(public_path('uploads/images/products/'), $fileName);
        }

        $rupiah = $request->harga;
        $getYen = Http::get('https://open.er-api.com/v6/latest/IDR');

        $data = $getYen->json();
        $yen = $rupiah * $data['rates']['JPY'];

        $products = Product::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'harga' => $request->harga,
            'yen' => $yen,
            'berat' => $request->berat,
            'deskripsi' => $request->deskripsi,
            'image' => $filePath ?? null,
            // 'customer_group' => $request->customer_group,
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
