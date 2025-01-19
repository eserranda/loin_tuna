<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_product' => 'required',
            'qty' => 'required',
        ], [
            'id_product.required' => 'Product harus diisi',
            'qty.required' => 'Quantity harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        // Menyimpan atau memperbarui data keranjang
        $cart = Cart::updateOrCreate(
            [
                'user_id' => auth()->user()->id, // Kondisi kombinasi unik
                'id_product' => $request->id_product,
            ],
            [
                'qty' => DB::raw('qty + ' . $request->qty), // Menambahkan qty jika sudah ada
            ]
        );

        if ($cart) {
            return response()->json([
                'success' => true,
                'message' => 'Grade Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Grade Gagal Disimpan',
            ], 409);
        }
    }

    public function findOne(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())->with('product')->get();

        if ($cart) {
            return response()->json([
                'success' => true,
                'message' => 'Data Ditemukan',
                'data' => $cart,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }
}
