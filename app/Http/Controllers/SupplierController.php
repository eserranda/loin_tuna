<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Supplier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\RawMaterialLots;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    // kok di sini ?
    public function nextNumber($no_ikan)
    {
        $lastLot = RawMaterial::where('no_ikan', $no_ikan)
            ->orderBy('no_ikan', 'desc')->first();

        if ($lastLot) {
            $nextNoIkan = $lastLot->no_loin + 1;
            if ($lastLot->no_loin == 4) {
                $nextNoIkan = 1;
            }
        } else {
            $nextNoIkan = 1;
        }

        return response()->json([
            'next_no_loin' => $nextNoIkan,
        ]);
    }

    public function getAllData(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('alamat', function ($row) {
                    return $row->jalan . ', ' . $row->kecamatan . ', ' . $row->kabupaten . ', ' . $row->provinsi;
                })
                ->addColumn('periode', function ($row) {
                    return $row->tahun_mulai . '-' . $row->tahun_selesai;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="hapus(' . $row->id . ')"><i class="ri-delete-bin-5-line mx-3"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function get()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function add()
    {
        return view('supplier.add');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'kode_supplier' => 'required|string|max:3|unique:suppliers',
            'nama_supplier' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
        ], [
            'kode_supplier.required' => 'Kode Supplier harus diisi.',
            'kode_supplier.unique' => 'Kode Supplier sudah ada.',
            'kode_supplier.max' => 'Kode Supplier maksimal 3 karakter.',
            'nama_supplier.required' => 'Nama harus diisi.',
            'provinsi.required' => 'Provinsi harus diisi.',
            'kabupaten.required' => 'Kabupaten harus diisi.',
            'kecamatan.required' => 'kecamatan harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data ke database
        $supplier = new Supplier();
        // $supplier->kode_batch = $request->kode_batch;
        $supplier->kode_supplier = $request->kode_supplier;
        $supplier->nama_supplier = $request->nama_supplier;
        $supplier->phone = $request->phone;
        $supplier->jalan = $request->jalan;
        $supplier->provinsi = $request->provinsi;
        $supplier->kabupaten = $request->kabupaten;
        $supplier->kecamatan = $request->kecamatan;
        $supplier->save();

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function destroy(Supplier $supplier, $id)
    {
        try {
            $del_receiving = $supplier::findOrFail($id);
            $del_receiving->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
