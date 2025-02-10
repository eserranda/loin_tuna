<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\CuttingGrading;
use App\Models\Receiving;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\ForwardTraceability;
use App\Models\ProductLog;
use App\Models\Retouching;
use Yajra\DataTables\Facades\DataTables;

class ForwardTraceabilityController extends Controller
{
    public function index()
    {
        return view('forward-traceability.index');
    }

    public function detail($ilc)
    {
        $receiving = Receiving::where('ilc', $ilc)->first();
        $cutting = Cutting::where('ilc', $ilc)->first();
        $retouching = Retouching::where('ilc', $ilc)->first();
        $raw_materials = RawMaterial::where('ilc', $ilc)->get();
        $cutting_grading = CuttingGrading::where('ilc', $ilc)->get();
        $retouchings = Retouching::where('ilc', $ilc)->get();
        $product_logs = ProductLog::where('ilc', $ilc)->get();
        return view('forward-traceability.detail', compact('receiving', 'cutting', 'retouching', 'raw_materials', 'cutting_grading', 'retouchings', 'product_logs'));
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = ForwardTraceability::latest('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal_cutting', function ($row) {
                    if ($row->tanggal_cutting != "") {
                        return $row->tanggal_cutting;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('tanggal_retouching', function ($row) {
                    if ($row->tanggal_retouching != "") {
                        return $row->tanggal_retouching;
                    } else {
                        return "-";
                    }
                })
                ->editColumn('tanggal_packing', function ($row) {
                    if ($row->tanggal_packing != "") {
                        return $row->tanggal_packing;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('action', function ($row) {
                    // $btn = '<a href="javascript:void(0);" onclick="kodeILC(\'' . $row->ilc . '\')"><i class="ri-arrow-right-line"></i></a>';
                    $btn = ' <a href="/forward-traceability/detail/' . $row->ilc . '"<i class="ri-arrow-right-line ms-2"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
