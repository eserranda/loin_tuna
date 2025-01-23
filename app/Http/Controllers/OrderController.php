<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index');
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('user_id', auth()->user()->id)
                ->latest('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                // ->editColumn('checking', function ($row) {
                //     if ($row->checking != "") {
                //         return ($row->checking . '%');
                //     } else {
                //         return "-";
                //     }
                // })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-start align-items-center">';
                    $btn = ' <a href="/order/detail-order/' . $row->po_number . '"<i class="ri-file-info-line"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
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
                $orderItems = new OrderItem();
                $orderItems->order_id = $order->id;
                $orderItems->id_product = $cartItem->id_product;
                $orderItems->qty = $cartItem->qty;
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
        $order = Order::where('user_id', $userId)->first(); // ok

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
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
