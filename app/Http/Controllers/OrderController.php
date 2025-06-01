<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index');
    }

    public function poStatus()
    {
        return view('order.po_status');
    }

    public function laporanPenjualan()
    {
        return view('penjualan.index');
    }

    public function invoicePenjualan($po_number)
    {
        $order = Order::where('po_number', $po_number)->first();
        $po_number = $order->po_number;
        $tanggal = $order->created_at->format('d-m-Y');
        $total_price = number_format($order->total_price, 0, ',', '.');

        $id_order = $order->id;

        $item_orders = OrderItem::where('order_id', $id_order)
            ->with(['product'])
            ->get();

        $total_price_product = 0;
        foreach ($item_orders as $item) {
            $total_price_product += $item->total_price;
        }
        $total_tax = $total_price_product * 0.12; // 12% pajak
        $total_tax = number_format($total_tax, 0, ',', '.');

        $total_price_product = number_format($total_price_product, 0, ',', '.');

        return view('penjualan.invoice_penjualan', compact('po_number', 'tanggal', 'total_price', 'total_price_product', 'total_tax', 'item_orders'));
    }

    // ubah ini
    public function dataLaporanPenjualan(Request $request)
    {
        if ($request->ajax()) {
            $filterMinggu = $request->input('filterMinggu');
            $filterBulan = $request->input('filterBulan');
            $filterTahun = $request->input('filterTahun');

            $query = Order::query();

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

            $data = $query->where('status', 'done')
                ->latest('created_at')
                ->get();
            // $data = Order::where('status', 'done')->latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('id_user', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('tanggal', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->editColumn('total_price', function ($row) {
                    return 'Rp.' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('status', function () {
                    return '<span class="badge text-bg-light">Selesai</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="/penjualan/invoice/' . $row->po_number . '"<i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function listOrder()
    {
        return view('order.list_order');
    }

    public function updateStatusPaid(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ], [
            'status.required' => 'Status harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $update = Order::where('id', $id)->update([
            'is_paid' => $request->status
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diperbarui',
            ], 400);
        }
    }

    public function payment(Request $request, $po_number)
    {
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'nama' => 'required',
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4048',
        ], [
            'bank.required' => 'bank harus diisi',
            'nama.required' => 'name harus diisi',
            'receipt_image.required' => 'image harus diisi',
            'receipt_image.image' => 'image harus berupa gambar',
            'receipt_image.mimes' => 'image harus berformat jpeg,png,jpg,gif',
            'receipt_image.max' => 'Ukuran image maksimal 2048KB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('receipt_image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $filePath = 'uploads/images/receipt/' . $fileName;
        $file->move(public_path('uploads/images/receipt/'), $fileName);

        $cekReceiptImages = Order::where('po_number', $po_number)->value('receipt_image');
        if (!$cekReceiptImages) {
            Order::where('po_number', $po_number)->update([
                'receipt_image' => $filePath
            ]);
        }

        $productImages = Order::where('po_number', $po_number)->update([
            'bank' => $request->bank,
            'nama' => $request->nama,
            'receipt_image' => $filePath,
            'is_paid' => 'checked'
        ]);

        if ($productImages) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Disimpan',
            ], 400);
        }
    }

    public function updateStatusOrder(Request $request, $id)
    {
        $update = Order::where('id', $id)->update([
            'status' => $request->status
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diperbarui',
            ], 400);
        }
    }

    public function poStatusData(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('customer', function ($row) {
                    return  $row->user->name;
                })
                ->editColumn('tanggal', function ($row) {
                    return  $row->created_at->format('d-m-Y');
                })
                ->editColumn('total_price', function ($row) {
                    return 'Rp' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('total_weight', function ($row) {
                    $total_weight = OrderItem::where('order_id', $row->id)->sum('weight');
                    return number_format($total_weight, 2, ',', '.') . ' Kg';
                })
                ->editColumn('total_payment', function ($row) {
                    if ($row->is_paid === 'confirmed') {
                        return 'Rp' . number_format($row->total_price, 0, ',', '.');
                    } else if ($row->is_paid === 'rejected') {
                        return '<span class="badge text-bg-danger">Ditolak</span>';
                    } else if ($row->is_paid === 'checked') {
                        return '<span class="badge text-bg-warning">Menunggu Konfirmasi</span>';
                    } else {
                        return '<span class="badge text-bg-danger">Belum Dibayar</span>';
                    }
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge text-bg-warning">Pending</span>';
                    } else if ($row->status == 'confirmed') {
                        return '<span class="badge text-bg-info">Disetujui</span>';
                    } else if ($row->status == 'done') {
                        return '<span class="badge text-bg-success">Selesai</span>';
                    } else {
                        return '-';
                    }
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="/order/detail-order/' . $row->po_number . '" class="btn btn-sm btn-light btn-icon waves-effect waves-light" title="Detail"><i class="ri-file-info-line text-warning"></i></a>';
                //     return $btn;
                // })
                ->rawColumns(['total_payment', 'status'])
                ->make(true);
        }
    }

    public function getAllListOrder(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_price', function ($row) {
                    return 'Rp' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('customer', function ($row) {
                    return  $row->user->name;
                })
                ->editColumn('tanggal', function ($row) {
                    return  $row->created_at->format('d-m-Y');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge text-bg-warning">Pending</span>';
                    } else if ($row->status == 'confirmed') {
                        return '<span class="badge text-bg-success">Disetujui pimpinan</span>';
                    } else if ($row->status == 'done') {
                        return '<button type="button" class="btn btn-light btn-sm waves-effect waves-light">Selesai</i>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('is_paid', function ($row) {
                    if ($row->is_paid == 'checked') {
                        return '<span class="badge text-bg-warning">Dibayar</span>';
                    } else if ($row->is_paid == 'confirmed') {
                        return '<span class="badge text-bg-success">Succes</span>';
                    } else if ($row->is_paid == 'rejected') {
                        return '<span class="badge text-bg-danger">Pembayaran ditolak</span>';
                    } else {
                        return '<span class="badge text-bg-danger">Belum di bayar</span>';
                    }
                })
                ->addColumn('bukti_transfer', function ($row) {
                    if ($row->receipt_image != '') {
                        return '<button type="button" title="Bukti Transfer" class="btn btn-success btn-sm waves-effect waves-light" onclick="showReceiptImg(\'' . $row->id . '\', \'' . $row->receipt_image . '\',\'' . $row->is_paid . '\',\'' . $row->status . '\')">Lihat Bukti Transfer';
                    } else {
                        return '-';
                    }
                })

                ->addColumn('action', function ($row) {
                    if ($row->is_paid == '' && $row->status != 'rejected') {
                        $btn = '<button type="button" title="Reject" class="btn btn-danger btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ', \'rejected\') "><i class="ri-close-circle-line"></i></button>';
                    } else if ($row->is_paid == '' && $row->status == 'rejected') {
                        $btn = '<button type="button" title="Delete" class="btn btn-danger btn-icon btn-sm waves-effect waves-light" onclick="hapusOrder(' . $row->id . ')"><i class="text-light ri-delete-bin-5-line"></i>';
                    } else if ($row->is_paid == 'checked') {
                        $btn = '<button type="button" title="Reject" class="btn btn-danger btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ', \'rejected\') "><i class="ri-close-circle-line"></i></button>';
                    } else if ($row->is_paid == 'confirmed' && $row->status == 'pending') {
                        $btn = '<button type="button" title="Confirm" class="btn btn-success btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ',\'confirmed\')" ><i class="ri-check-double-line"></i></button>';
                    } else if ($row->is_paid == 'confirmed' && $row->status == 'confirmed') {
                        $btn = '<button type="button" class="btn btn-warning btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ', \'pending\')" title="Cancel"><i class="ri-close-circle-line"></i></button>';
                        $btn .= '<button type="button" class="btn btn-success btn-sm waves-effect waves-light mx-1" onclick="updateStatus(' . $row->id . ', \'done\')" title="Selesai">Selesai</button>';
                        $btn .= '<a href="/order/list-order/' . $row->id . '" class="btn btn-info  btn-sm waves-effect waves-light">Inv.</a>';
                    } else if ($row->is_paid == 'rejected') {
                        $btn = '<button type="button" title="Delete" class="btn btn-danger btn-icon btn-sm waves-effect waves-light" onclick="hapusOrder(' . $row->id . ')"><i class="text-light ri-delete-bin-5-line"></i>';
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'is_paid', 'bukti_transfer'])
                ->make(true);
        }
    }


    public function update(Request $request, $po_number)
    {
        $update = Order::where('po_number', $po_number)->update([
            'phone' => $request->phone,
            'negara' => $request->negara,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'jalan' => $request->jalan,
            'kode_pos' => $request->kode_pos,
        ]);

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diperbarui',
            ], 400);
        }
    }

    // untuk tampilan pada packing
    public function getAllOrderInPo(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('status', 'confirmed')
                ->where('is_packed', false)
                ->latest('created_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                // ->editColumn('total_price', function ($row) {
                //     return 'Rp.' . number_format($row->total_price, 0, ',', '.');
                // })
                // ->addColumn('status', function ($row) {
                //     if ($row->status == 'pending') {
                //         return '<span class="badge text-bg-warning">' . $row->status . '</span>';
                //     } else if ($row->status == 'confirmed') {
                //         return '<span class="badge text-bg-success">' . $row->status . '</span>';
                //     } else if ($row->status == 'rejected') {
                //         return '<span class="badge text-bg-danger">' . $row->status . '</span>';
                //     }
                // })
                ->editColumn('tanggal', function ($row) {
                    return  $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    // $btn = '<div class="d-flex justify-content-start align-items-center">';
                    // $btn = ' <a href="/order/detail-order/' . $row->po_number . '"<i class="ri-file-info-line"></i></a>';
                    // $btn .= '</div>';
                    // return $btn;

                    $btn = '<a href="javascript:void(0);" onclick="POnumber(\'' . $row->po_number . '\')"><i class="ri-arrow-right-line"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('user_id', auth()->user()->id)
                ->latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_price', function ($row) {
                    return 'Rp' . number_format($row->total_price, 0, ',', '.');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge text-bg-warning">Pending</span>';
                    } else if ($row->status == 'confirmed') {
                        return '<span class="badge text-bg-success">Dikonfirmasi</span>';
                    } else if ($row->status == 'rejected') {
                        return '<span class="badge text-bg-danger">Dibatalkan</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    if ($row->status != 'rejected') {
                        $btn = '<a href="/order/detail-order/' . $row->po_number . '" class="btn btn-sm btn-light btn-icon waves-effect waves-light" title="Detail"><i class="ri-file-info-line text-warning"></i></a>';
                        return $btn;
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function checkout()
    {
        $userId = auth()->user()->id;

        DB::beginTransaction();

        try {
            $cartItems = Cart::where('user_id', $userId)->get();


            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja kosong',
                ], 404);
            }

            $po_number = "PO-" . uniqid();
            $totalPrice = $cartItems->sum('total_price');
            $ppn = $totalPrice * 0.12;

            $order = new Order();
            $order->user_id = $userId;
            $order->po_number = $po_number;
            $order->total_price = $totalPrice + $ppn;
            $order->status = 'pending';
            $order->save();

            foreach ($cartItems as $cartItem) {
                $produk = Product::where('id', $cartItem->id_product)->first();

                $orderItems = new OrderItem();
                $orderItems->order_id = $order->id;
                $orderItems->id_product = $cartItem->id_product;
                $orderItems->qty = $cartItem->qty;
                $orderItems->weight = $cartItem->qty * $produk->berat;
                $orderItems->price = $cartItem->product->harga;
                $orderItems->total_price = $cartItem->total_price;
                $orderItems->save();
            }

            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil',
                'po_number' => $po_number
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detailOrder($po_number)
    {
        $userId = auth()->user()->id;
        $order = Order::where('po_number', $po_number)->where('user_id', $userId)->first(); // ok
        $customer = Customer::where('user_id', $userId)->first();

        $list_orders = OrderItem::with(['order', 'product'])
            ->whereHas('order', function ($query) use ($po_number) {
                $query->where('po_number', $po_number);
            })
            ->get();

        $sub_total = 0;
        $pajak = 0;
        $total_amount = 0;
        foreach ($list_orders as $orders) {
            $sub_total += $orders->total_price;
        }

        $pajak = $sub_total * 0.12;
        $total_amount = $sub_total + $pajak;

        return view('checkout.index', compact('order', 'customer', 'list_orders', 'sub_total',  'total_amount', 'pajak'));
    }

    public function destroy(Order $order, $id)
    {
        try {
            $del_order = $order::findOrFail($id);
            $del_order->delete();


            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
