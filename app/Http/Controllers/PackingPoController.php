<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PackingPo;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

use function Laravel\Prompts\progress;

class PackingPoController extends Controller
{
    public function getOnePoNumber($po_number)
    {
        $data_po = Order::where('po_number', $po_number)->first();

        return view('packing.packing_po', compact('data_po'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk_log' => 'required',
            'nama_produk' => 'required',
            'berat' => 'required',
            'po_number' => 'required',
        ], [
            'id_produk_log.required' => 'Produk harus diisi',
            'nama_produk.required' => 'Nama Produk harus diisi',
            'berat.required' => 'Berat harus diisi',
            'po_number' => 'PO Number harus diisi',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $product_log = ProductLog::where('id', $request->id_produk_log)->first();

        $cek_progress = PackingPo::where('po_number', $request->po_number)
            ->where('id_product', $product_log->id_produk)
            ->first();

        if ($cek_progress) {
            if ($cek_progress->progress >= 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Progress sudah mencapai 100%'
                ], 422);
            }
        }


        $orderItem = PackingPo::where('po_number', $request->po_number)
            ->where('id_product', $product_log->id_produk)
            ->first();

        // ambil data untuk menghitung total progress
        $po_number = Order::where('po_number', $request->po_number)->first();
        $dataOrderItem = OrderItem::where('order_id', $po_number->id)
            ->where('id_product', $product_log->id_produk)
            ->first();

        if ($orderItem) {
            $progress = $orderItem->total_qty + 1;
            $total_progress = ($progress / $dataOrderItem->qty) * 100;

            $orderItem->total_qty += 1;
            $orderItem->total_weight += $request->berat;
            $orderItem->progress = number_format($total_progress, 2);
            $save = $orderItem->save();
        } else {
            $total_progress = (1 / $dataOrderItem->qty) * 100;
            $save = PackingPo::create([
                'po_number' => $request->po_number,
                'id_product' => $product_log->id_produk,
                'total_qty' => 1,
                'total_weight' => $request->berat,
                'progress' => number_format($total_progress, 2),
            ]);
        }

        ProductLog::where('id', $request->id_produk_log)->update([
            'is_used' => 1
        ]);

        if ($save) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil di packing'
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan'
            ], 500);
        }
    }

    public function getAllDataProductOrder(Request $request, $po_number)
    {
        if ($request->ajax()) {
            $po_number = Order::where('po_number', $po_number)->first();
            $data = OrderItem::where('order_id', $po_number->id)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('product', function ($row) {
                    return  $row->product->nama;
                })
                ->make(true);
        }
    }

    public function getAllProductLogs(Request $request)
    {
        if ($request->ajax()) {

            $data = ProductLog::where('is_used', 0)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_produk', function ($row) {
                    if ($row->id_produk) {
                        return $row->produk->nama;
                    }
                })
                ->addColumn('qty', function () {
                    return  1;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" onclick="setProduct(\'' . $row->id . '\', \'' . $row->produk->nama . '\',\'' . $row->berat . '\')"><i class="ri-arrow-right-line"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action', 'qty'])
                ->make(true);
        }
    }

    public function getAllPackingPo(Request $request, $po_number)
    {
        if ($request->ajax()) {
            $data = PackingPo::where('po_number', $po_number)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('id_produk', function ($row) {
                    return $row->produk->nama;
                })
                ->editColumn('total_weight', function ($row) {
                    return $row->total_weight . ' Kg';
                })
                ->editColumn('progress', function ($row) {
                    return $row->progress . '%';
                })
                ->make(true);
        }
    }
}
