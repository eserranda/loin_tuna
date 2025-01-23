<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string',
            'negara' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'jalan' => 'nullable|string',
            'kode_pos' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        if (auth()->check() && auth()->user()->hasRole(['customer'])) {
            try {
                // Mulai transaksi
                DB::beginTransaction();

                // Update data di tabel Customer
                $updateCustomer = Customer::where('user_id', $id)->update([
                    'phone' => $request->phone,
                    'negara' => $request->negara,
                    'provinsi' => $request->provinsi,
                    'kabupaten' => $request->kabupaten,
                    'kecamatan' => $request->kecamatan,
                    'jalan' => $request->jalan,
                    'kode_pos' => $request->kode_pos
                ]);

                // Update data di tabel User
                $updateUser = User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);

                // Periksa apakah kedua update berhasil
                if (!$updateCustomer || !$updateUser) {
                    // Jika salah satu gagal, rollback
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Data gagal diubah.',
                    ], 400);
                }

                // Commit transaksi jika semua berhasil
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Diubah',
                ], 200);
            } catch (\Exception $e) {
                // Rollback jika terjadi exception
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }
        } else {
            $updateUser = User::where('id', $id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($updateUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Diubah',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data gagal diubah.',
            ], 400);
        }
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
