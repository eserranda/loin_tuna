<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function userCustomers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('customer', 1)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn .= '<a class="btn btn-outline-secondary btn-sm" title="Edit" onclick="edit(' . $row->id . ')"> <i class="ri-pencil-line"></i> </a>';
                    $btn .= '<a class="btn btn-outline-secondary btn-sm  text-danger mx-2" title="Hapus" onclick="hapus(' . $row->id . ')"> <i class="text-danger ri-delete-bin-5-line"></i> </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customer.index');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('customer', 0)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn .= '<a class="btn btn-outline-secondary btn-sm" title="Edit" onclick="edit(' . $row->id . ')"> <i class="ri-pencil-line"></i> </a>';
                    $btn .= '<a class="btn btn-outline-secondary btn-sm  text-danger mx-2" title="Hapus" onclick="hapus(' . $row->id . ')"> <i class="text-danger ri-delete-bin-5-line"></i> </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    public function findById($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'confirmed' => ':attribute tidak cocok',
            'exists' => ':attribute tidak ada',
            'roles.*' => ':attribute tidak ada',
            'string' => ':attribute harus berupa string',
            'email' => ':attribute harus berupa email',
            'max' => ':attribute maksimal :max karakter',
            'confirmed' => ':attribute tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach(Role::whereIn('name', $request->roles)->get());

        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil'
        ], 200);
    }
    public function customerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'confirmed' => ':attribute tidak cocok',
            'exists' => ':attribute tidak ada',
            'string' => ':attribute harus berupa string',
            'email' => ':attribute harus berupa email',
            'max' => ':attribute maksimal :max karakter',
            'confirmed' => ':attribute tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'customer' => true,
            'password' => Hash::make($request->password),
        ]);

        // Menambahkan role 'customer' ke user
        $role = Role::where('name', 'customer')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi Berhasil'
        ], 200);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginType => $request->login, 'password' => $request->password];
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }


        return redirect()->back()->withErrors(['login' => 'Username or Password is incorrect'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'edit_name' => 'required|string|max:255',
            'edit_username' => 'required|string|max:255|unique:users,username,' . $id,
            'edit_email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'edit_roles' => 'required|array',
            'edit_roles.*' => 'exists:roles,name',
        ], [
            'edit_name.required' => 'Nama Tidak Boleh Kosong',
            'edit_username.required' => 'Username Tidak Boleh Kosong',
            'edit_email.required' => 'Email Tidak Boleh Kosong',
            'edit_roles.required' => 'Role Tidak Boleh Kosong',
            'edit_roles.*.exists' => 'Role Tidak Valid',
            'edit_username.unique' => 'Username Sudah Digunakan',
            'edit_email.unique' => 'Email Sudah Digunakan',
            'edit_email.email' => 'Email Tidak Valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $update = User::where('id', $id)->update([
            'name' => $request->edit_name,
            'username' => $request->edit_username,
            'email' => $request->edit_email,
        ]);

        $user = User::find($id);
        $user->roles()->sync(Role::whereIn('name', $request->edit_roles)->get());


        if ($update) {
            return response()->json([
                'success' => true,
                'messages' => 'Program berhasil diubah'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Program gagal diubah'
            ], 409);
        }
    }

    public function destroy(User $users, $id)
    {
        try {
            $del = $users::findOrFail($id);
            $del->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
