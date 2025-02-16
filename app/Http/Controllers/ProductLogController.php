<?php

namespace App\Http\Controllers;

use App\Models\ProductLog;
use App\Models\Retouching;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductLogController extends Controller
{
    public function index($ilc)
    {
        $data = Retouching::where('ilc', $ilc)->first();
        $tanggal = Carbon::parse($data->created_at)->format('d-m-Y');

        return view('product-log.index', compact('data', 'tanggal'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ilc' => 'required',
            'id_produk' => 'required',
            'no_loin' => 'required',
            'berat' => 'required',
            // 'ekspor' => 'required',
        ], [
            'ilc.required' => 'ILC harus diisi',
            'id_produk.required' => 'Produk harus diisi',
            'no_loin.required' => 'No. Loin harus diisi',
            'berat.required' => 'Berat harus diisi',
            'ilc.unique' => 'Kombinasi ILC dan No. Ikan sudah ada',
        ]);

        // $validator->after(function ($validator) use ($request) {
        //     $existingEntry = ProductLog::where('ilc', $request->ilc)
        //         ->where('no_loin', $request->no_loin)
        //         ->exists();

        //     if ($existingEntry) {
        //         $validator->errors()->add('ilc', 'ILC Cutting sudah ada.');
        //         $validator->errors()->add('no_loin', 'No Loin sudah ada.');
        //     }
        // });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        // $getProduk = ProductLog::where('ilc', $request->ilc)->first();

        // if (isset($getProduk) && ($getProduk->id_produk == $request->id_produk)) {
        //     $totalBerat = $getProduk->berat + $request->berat;
        //     $update = ProductLog::where('ilc', $request->ilc)->update([
        //         'berat' => $totalBerat
        //     ]);

        //     $updateSisaBerat = Retouching::where('ilc', $request->ilc)
        //         ->where('no_loin', $request->no_loin)
        //         ->decrement('sisa_berat', $request->berat);


        //     if ($update && $updateSisaBerat) {
        //         return response()->json([
        //             'success' => true
        //         ], 201);
        //     } else {
        //         return response()->json([
        //             'success' => false
        //         ], 500);
        //     }
        // }

        $save = ProductLog::create($request->all());

        $updateSisaBerat = Retouching::where('ilc', $request->ilc)
            ->where('no_loin', $request->no_loin)
            ->decrement('sisa_berat', $request->berat);
        if ($save && $updateSisaBerat) {
            return response()->json([
                'success' => true
            ], 201);
        } else {
            return response()->json([
                'success' => false
            ], 500);
        }
    }


    public function destroy(ProductLog $productLog, $id)
    {
        try {
            $delete = $productLog::findOrFail($id);
            $delete->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
