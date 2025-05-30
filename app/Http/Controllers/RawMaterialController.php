<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\Receiving;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('raw-material.index');
    }

    public function laporanPembelian()
    {
        return view('pembelian.index');
    }

    public function invoicePembelian($ilc)
    {

        $data = Receiving::where('ilc', $ilc)->first();
        $invoice_number = $data->invoice_number;
        $tanggal = Carbon::parse($data->created_at)->format('d-m-Y H:i');

        $raw_material = RawMaterial::where('ilc', $ilc)->latest('created_at')->get();

        $harga = $raw_material->sum('harga');
        $total_harga = number_format($harga, 0, ',', '.');

        return view('pembelian.invoice', compact('ilc', 'invoice_number', 'tanggal', 'total_harga', 'raw_material'));
    }

    public function detailPembelian($ilc)
    {
        return view('pembelian.detail_pembelian', compact('ilc'));
    }

    public function updateHarga(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'harga' => 'required|numeric',
        ], [
            'harga.required' => 'Harga Wajib Diisi',
            'harga.numeric' => 'Harga Harus Berupa Angka',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $update = RawMaterial::where('id', $id)->update([
            'harga' => $request->harga,
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diupdate',
            ], 500);
        }
    }

    public function updateGrade(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required',
        ], [
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $update = RawMaterial::where('id', $id)->update([
            'grade' => $request->grade,
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diupdate',
            ], 500);
        }
    }

    public function getAllDataPembelian(Request $request)
    {
        if ($request->ajax()) {
            $filterMinggu = $request->input('filterMinggu');
            $filterBulan = $request->input('filterBulan');
            $filterTahun = $request->input('filterTahun');

            $query = Receiving::query();

            // Filter berdasarkan minggu
            if ($filterMinggu !== null) {
                if ($filterMinggu == 0) {
                    // Minggu ini
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                } else {
                    // X minggu yang lalu
                    $query->whereBetween('created_at', [
                        Carbon::now()->subWeeks($filterMinggu)->startOfWeek(),
                        Carbon::now()->subWeeks($filterMinggu)->endOfWeek()
                    ]);
                }
            }

            // Filter berdasarkan bulan (tanpa tahun)
            if ($filterBulan !== null) {
                $query->whereMonth('created_at', $filterBulan)
                    ->whereYear('created_at', Carbon::now()->year); // Tahun berjalan
            }

            // Filter berdasarkan tahun (tanpa bulan)
            if ($filterTahun !== null) {
                $query->whereYear('created_at', $filterTahun);
            }

            // Filter berdasarkan bulan dan tahun
            if ($filterBulan !== null && $filterTahun !== null) {
                $query->whereMonth('created_at', $filterBulan)
                    ->whereYear('created_at', $filterTahun);
            }

            $data = $query->latest('created_at')->get();

            $totalLoin = 0;
            foreach ($data as $item) {
                $totalLoin += RawMaterial::where('ilc', $item->ilc)->count();
            }
            $totalHarga = 0;
            foreach ($data as $item) {
                $totalHarga += RawMaterial::where('ilc', $item->ilc)->sum('harga');
            }
            $totalBerat = 0;
            foreach ($data as $item) {
                $totalBerat += RawMaterial::where('ilc', $item->ilc)->sum('berat');
            }

            // $data = Receiving::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->format('d-m-Y');
                })
                ->editColumn('total_loin', function ($row) {
                    return RawMaterial::where('ilc', $row->ilc)->count() . ' Loin';
                })
                ->editColumn('total_berat', function ($row) {
                    return RawMaterial::where('ilc', $row->ilc)->sum('berat') . ' Kg';
                })
                ->editColumn('total_harga', function ($row) {
                    $totalHarga = RawMaterial::where('ilc', $row->ilc)->sum('harga');
                    return $totalHarga > 0 ? 'Rp' . number_format($totalHarga, 0, ',', '.') : 'harga belum diinput';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="/pembelian/detail/' . $row->ilc . '"<i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->with([
                    'totalBerat' => $totalBerat,
                    'totalHarga' => $totalHarga,
                    'totalLoin' => $totalLoin,
                ])
                ->make(true);
        }
    }

    public function dataDetailPembelianPerILC(Request $request, $ilc)
    {
        if ($request->ajax()) {
            $query = RawMaterial::where('ilc', $ilc)->latest('created_at')->get();

            $totalBerat = $query->sum('berat');
            $totalHarga = $query->sum('harga');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('berat', function ($row) {
                    return $row->berat . ' Kg';
                })
                ->editColumn('harga', function ($row) {
                    return $row->harga > 0 ? 'Rp' . number_format($row->harga, 0, ',', '.') : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-light btn-icon waves-effect waves-danger" onclick="updateHarga(\'' . $row->id . '\')"><i class="ri-pencil-line" title="Update Harga"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->with([
                    'totalBerat' => $totalBerat,
                    'totalHarga' => $totalHarga
                ])
                ->make(true);
        }
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = RawMaterial::latest('created_at')->get();
            // $data->transform(function ($item) {
            //     $item->tanggal = Carbon::parse($item->tanggal)->format('d-m-Y');
            //     return $item;
            // });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    $btn .= ' <a href="/rawmaterial/grading/' . $row->ilc . '"<i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getNoIkan($ilc)
    {
        $noIkanList = RawMaterial::where('ilc', $ilc)
            ->orderBy('no_loin', 'asc')
            ->select('no_loin', 'berat', 'grade') // Memilih kolom yang dibutuhkan
            ->get();

        return response()->json($noIkanList);
    }

    public function calculateTotalWeight($ilc)
    {
        $totalBerat = RawMaterial::where('ilc', $ilc)->sum('berat');
        return response()->json([
            'totalBerat' => $totalBerat
        ]);
    }

    public function findManyWithILC(Request $request, $ilc)
    {
        if ($request->ajax()) {
            $data = RawMaterial::where('ilc', $ilc)->latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('berat', function ($row) {
                    return $row->berat . ' Kg';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="editGrade(\'' . $row->id . '\',\'' . $row->ilc . '\' ,\'' . $row->no_loin . '\' )"><i class="ri-pencil-line" title="Edit Grade"></i></a>';
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="text-danger ri-delete-bin-5-line mx-3"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function getOneRawWithILC($ilc)
    {
        $data = Receiving::where('ilc', $ilc)->first();
        return view('raw-material.index', compact('data'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'berat' => 'required|numeric',
            'no_loin' => 'required|numeric',
            'grade' => 'required',
        ], [
            'ilc.unique' => 'ILC Sudah Ada',
            'berat.required' => 'Berat Ikan Wajib Diisi',
            'berat.numeric' => 'Berat Ikan Harus Berupa Angka',
            'no_loin.required' => 'Nomor Ikan Wajib Diisi',
            'no_loin.numeric' => 'Nomor Ikan Harus Berupa Angka',
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingEntry = RawMaterial::where('ilc', $request->ilc)
                ->where('no_loin', $request->no_loin)
                ->exists();

            if ($existingEntry) {
                $validator->errors()->add('no_loin', 'Nomor Loin sudah ada.');
                // $validator->errors()->add('ilc', 'Ilc sudah ada.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        RawMaterial::create([
            'ilc' => $request->ilc,
            'berat' => $request->berat,
            'no_loin' => $request->no_loin,
            'grade' => $request->grade,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berat Ikan Berhasil',
        ], 201);
    }


    public function gradingStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'berat' => 'required|numeric',
            'no_loin' => 'required|numeric',
            'grade' => 'required',
        ], [
            'ilc.unique' => 'ILC Sudah Ada',
            'berat.required' => 'Berat Ikan Wajib Diisi',
            'berat.numeric' => 'Berat Ikan Harus Berupa Angka',
            'no_loin.required' => 'Nomor Ikan Wajib Diisi',
            'no_loin.numeric' => 'Nomor Ikan Harus Berupa Angka',
            'grade.required' => 'Grade Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        RawMaterial::create([
            'ilc' => $request->ilc,
            'berat' => $request->berat,
            'no_loin' => $request->no_loin,
            'grade' => $request->grade,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berat Ikan Berhasil',
        ], 201);
    }

    public function nextNumber($ilc)
    {
        $lastLot = RawMaterial::where('ilc', $ilc)->orderBy('no_loin', 'desc')->first();
        $nextNoLoin = $lastLot ? $lastLot->no_loin + 1 : 1;
        return response()->json([
            'next_no_loin' => $nextNoLoin,
        ]);
    }

    public function destroy(RawMaterial $receiving, $id)
    {
        try {
            $del_receiving = $receiving::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
