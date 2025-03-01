<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
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

    public function listOrder()
    {
        return view('order.list_order');
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
                'messages' => $validator->errors()
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
    public function getAllListOrder(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_price', function ($row) {
                    return 'Rp.' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('customer', function ($row) {
                    return  $row->user->name;
                })
                ->editColumn('tanggal', function ($row) {
                    return  $row->created_at->format('d-m-Y');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge text-bg-warning">' . $row->status . '</span>';
                    } else if ($row->status == 'confirmed') {
                        return '<span class="badge text-bg-success">' . $row->status . '</span>';
                    } else if ($row->status == 'rejected') {
                        return '<span class="badge text-bg-danger">' . $row->status . '</span>';
                    }
                })

                ->addColumn('action', function ($row) {
                    if ($row->status == 'pending') {
                        $btn = '<div class="d-flex justify-content-start align-items-center">';
                        $btn .= '<button type="button" title="Confirm" class="btn btn-success btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ',\'confirmed\')" ><i class="ri-check-double-line"></i></button>';
                        $btn .= '<button type="button" title="Reject" class="btn btn-danger btn-icon btn-sm waves-effect waves-light mx-2" onclick="updateStatus(' . $row->id . ', \'rejected\') "><i class="ri-close-circle-line"></i></button>';
                        $btn .= '</div>';
                    } else if ($row->status == 'confirmed') {
                        $btn = '<button type="button" class="btn btn-warning btn-icon btn-sm waves-effect waves-light" onclick="updateStatus(' . $row->id . ', \'pending\')" title="Cancel"><i class="ri-close-circle-line"></i></button>';
                    } else if ($row->status == 'rejected') {
                        $btn = '<button type="button" title="Delete" class="btn btn-danger btn-icon btn-sm waves-effect waves-light" onclick="hapusOrder(' . $row->id . ')"><i class="text-light ri-delete-bin-5-line"></i>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
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
                    return 'Rp.' . number_format($row->total_price, 0, ',', '.');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'pending') {
                        return '<span class="badge text-bg-warning">' . $row->status . '</span>';
                    } else if ($row->status == 'confirmed') {
                        return '<span class="badge text-bg-success">' . $row->status . '</span>';
                    } else if ($row->status == 'rejected') {
                        return '<span class="badge text-bg-danger">' . $row->status . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn = ' <a href="/order/detail-order/' . $row->po_number . '"<i class="ri-file-info-line"></i></a>';
                    $btn .= '</div>';
                    return $btn;
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
