<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Receiving;
use App\Models\Inspection;
use App\Models\Retouching;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inspection.index');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = Inspection::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('uji_lab', function ($row) {
                    if ($row->uji_lab != "") {
                        return $row->uji_lab;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('tekstur', function ($row) {
                    if ($row->tekstur != "") {
                        return $row->tekstur;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('bau', function ($row) {
                    if ($row->bau != "") {
                        return $row->bau;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('es', function ($row) {
                    if ($row->es != "") {
                        return $row->es;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('suhu', function ($row) {
                    if ($row->suhu != "") {
                        return $row->suhu;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('hasil', function ($row) {
                    if ($row->hasil != "") {
                        return ($row->hasil . '%');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="update(\'' . $row->id  . '\', \'' . $row->ilc . '\', \'' . $row->stage . '\')"><i class="ri-pencil-fill text-info"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function update(Request $request, Inspection $inspection)
    {
        $validator = Validator::make($request->all(), [
            'uji_lab' => 'required|numeric|min:0|max:4',
            'tekstur' => 'required|numeric|min:0|max:4',
            'bau' => 'required|numeric|min:0|max:4',
            'es' => 'required|numeric|min:0|max:4',
            'suhu' => 'required|numeric|min:0|max:4',
        ], [
            'required' => 'Nilai :attribute harus diisi.',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors()
            ], 422);
        }

        // Ambil nilai yang sudah tervalidasi
        $validatedData = $validator->validated();

        // Hitung rata-rata nilai aktual (X)
        $averageX = array_sum($validatedData) / count($validatedData);

        // Hitung nilai kesesuaian (hasil) dalam persentase dan bulatkan
        $nilaiKesesuaian = round(($averageX / 4) * 100, 0);

        $updateReceivingChecking = Inspection::where('id', $request->id)->update([
            'uji_lab' => $request->uji_lab,
            'tekstur' => $request->tekstur,
            'bau' => $request->bau,
            'es' => $request->es,
            'suhu' => $request->suhu,
            'hasil' => $nilaiKesesuaian,
        ]);

        if ($request->stage == "Receiving") {
            Receiving::where('ilc', $request->ilc)->update([
                'inspection' => $nilaiKesesuaian,
            ]);
        } else if ($request->stage == "Cutting") {
            Cutting::where('ilc', $request->ilc)->update([
                'inspection' => $nilaiKesesuaian,
            ]);
        } else if ($request->stage == "Retouching") {
            Retouching::where('ilc', $request->ilc)->update([
                'inspection' => $nilaiKesesuaian,
            ]);
        }


        if ($updateReceivingChecking) {
            return response()->json([
                'success' => true
            ], 201);
        } else {
            return response()->json([
                'success' => false
            ], 500);
        }
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
    public function show(Inspection $inspection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspection $inspection)
    {
        //
    }
}
