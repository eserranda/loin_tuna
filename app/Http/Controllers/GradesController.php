<?php

namespace App\Http\Controllers;

use App\Models\Grades;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class GradesController extends Controller
{
    public function index()
    {
        return view('grades.index');
    }

    public function findById($id)
    {
        $data = Grades::find($id);
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_grade' => 'required|unique:grades,grade,' . $request->id,
        ], [
            'edit_grade.required' => 'Grade harus diisi',
            'edit_grade.unique' => 'Grade sudah ada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $update = Grades::find($request->id);
        $update->grade = $request->edit_grade;
        $update->description = $request->edit_description;
        $update->save();

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Grade Berhasil Diupdate',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Grade Gagal Diupdate',
            ], 409);
        }
    }

    public function getAll()
    {
        $dataGrade = Grades::orderBy('grade', 'ASC')->pluck('grade');
        if ($dataGrade) {
            return response()->json($dataGrade);
        } else {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
    }

    public function getAllData(Request $request)
    {
        if ($request->ajax()) {
            $data = Grades::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    $btn .= '<a href="javascript:void(0);" onclick="edit( ' . $row->id . ')"><i class="ri-pencil-line"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required|unique:grades,grade',
        ], [
            'grade.required' => 'Grade harus diisi',
            'grade.unique' => 'Grade sudah ada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        $save = new Grades();
        $save->grade = $request->grade;
        $save->description = $request->description;
        $save->save();

        if ($save) {
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


    public function destroy(Grades $grades, $id)
    {
        try {
            $del = $grades::findOrFail($id);
            $del->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
