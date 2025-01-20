<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function decrease($id)
    {
        $cart = Cart::find($id);
        $cart->qty -= 1;
        $cart->total_amount = $cart->qty * $cart->product->harga;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus',
        ], 200);
    }

    public function increase($id)
    {
        $cart = Cart::find($id);
        $cart->qty += 1;
        $cart->total_amount = $cart->qty * $cart->product->harga;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus',
        ], 200);
    }

    public function index()
    {
        return view('cart.index');
    }

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

        $price = Product::where('id', $request->id_product)->value('harga');
        // Menyimpan atau memperbarui data keranjang
        $cart = Cart::where('user_id', auth()->user()->id)
            ->where('id_product', $request->id_product)
            ->first();

        if ($cart) {
            $cart->qty += $request->qty;
            $cart->total_amount = $cart->qty * $price;
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
                'id_product' => $request->id_product,
                'qty' => $request->qty,
                'total_amount' => $request->qty * $price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Grade Gagal Disimpan',
        ], 409);
    }

    public function findOne()
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

    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus',
        ], 200);
    }
}
