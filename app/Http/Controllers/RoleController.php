<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::latest('created_at')->get();
        return view('roles.index', compact('role'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ], [
            'required' => ':attribute harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $save = Role::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        if ($save) {
            return response()->json([
                'success' => true,
                'messages' => 'Data berhasil disimpan'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Data gagal disimpan'
            ]);
        }
    }


    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role,  $id)
    {
        try {
            $delate = $role::findOrFail($id);
            $delate->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
