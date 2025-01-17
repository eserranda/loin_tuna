<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\CuttingGrading;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CuttingGradingController extends Controller
{
    public function index($ilc_cutting)
    {
        $data = Cutting::where('ilc_cutting', $ilc_cutting)->first();
        $tanggal = Carbon::parse($data->created_at)->format('d-m-Y');

        return view('cutting-grading.index', compact('data', 'tanggal'));
    }

    public function calculateTotalWeight($ilc)
    {
        $totalBeratReceiving = RawMaterial::where('ilc', $ilc)->sum('berat');
        $totalBerat = CuttingGrading::where('ilc', $ilc)->sum('berat');
        $totalSisa = round($totalBeratReceiving - $totalBerat, 2);

        if ($totalBeratReceiving != 0) {
            $persentasePenggunaan = round(($totalBerat / $totalBeratReceiving) * 100, 2);
        } else {
            $persentasePenggunaan = 0;
        }
        return response()->json([
            'totalBerat' => $totalBerat,
            'totalBeratReceiving' => $totalBeratReceiving,
            'totalSisaBerat' => $totalSisa,
            'persentasePenggunaan' => $persentasePenggunaan
        ]);
    }

    public function getAll(Request $request, $ilc_cutting)
    {
        if ($request->ajax()) {
            $data = CuttingGrading::where('ilc_cutting', $ilc_cutting)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line mx-3"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function nextNumber($ilc_cutting, $no_ikan)
    {
        $lastLot = CuttingGrading::where('ilc_cutting', $ilc_cutting)
            ->orderBy('no_loin', 'desc')->first();

        if ($lastLot) {
            $nextNoIkan = $lastLot->no_loin + 1;
            // if ($lastLot->no_loin == 4) {
            //     $nextNoIkan = 1;
            // }
        } else {
            $nextNoIkan = 1;
        }

        return response()->json([
            'next_no_loin' => $nextNoIkan,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ilc' => 'required',
            'ilc_cutting' => 'required',
            'berat' => 'required|numeric',
            'no_loin' => 'required|numeric',
            'grade' => 'required',
        ], [
            'ilc.required' => 'ILC Wajib Diisi',
            'ilc_cutting.required' => 'ILC Cutting Wajib Diisi',
            'berat.required' => 'Berat Ikan Wajib Diisi',
            'berat.numeric' => 'Berat Ikan Harus Berupa Angka',
            'no_loin.required' => 'Nomor Loin Wajib Diisi',
            'no_loin.numeric' => 'Nomor Loin Harus Berupa Angka',
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = CuttingGrading::where('ilc_cutting', $request->ilc_cutting)
                ->where('no_loin', $request->no_loin)
                ->exists();

            if ($exists) {
                return $validator->errors()->add('unique_combination', 'Nomor loin sudah ada');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data_cutting = Cutting::where('ilc_cutting', $request->ilc_cutting)->first();
        $id_cutting = $data_cutting->id;
        $id_supplier = $data_cutting->id_supplier;

        if ($data_cutting == null) {
            return response()->json([
                'success' => false,
                'errors' => 'ILC Tidak Ditemukan',
            ]);
        }

        $save =  CuttingGrading::create([
            'ilc' => $request->ilc,
            'id_supplier' => $id_supplier,
            'id_cutting' => $id_cutting,
            'ilc_cutting' => $request->ilc_cutting,
            'berat' => $request->berat,
            'no_loin' => $request->no_loin,
            'grade' => $request->grade,
        ]);

        if ($save) {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function destroy(CuttingGrading $refinedMaterialLots, $id)
    {
        try {
            $del_receiving = $refinedMaterialLots::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
