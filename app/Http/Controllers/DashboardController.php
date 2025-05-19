<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Dashboard;
use App\Models\Order;
use App\Models\Packing;
use App\Models\Receiving;
use App\Models\Retouching;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalReceiving = Receiving::count();
        $totalReceivingToday = Receiving::whereDate('created_at', now())->count();

        $totalCutting = Cutting::count();
        $totalCuttingToday = Cutting::whereDate('created_at', now())->count();

        $totalRetouching = Retouching::count();
        $totalRetouchingToday = Retouching::whereDate('created_at', now())->count();

        $totalPacking = Packing::count();
        $totalPackingToday = Packing::whereDate('created_at', now())->count();

        $totalOrders = Order::count();
        return view('dashboard.index', compact('totalReceiving', 'totalReceivingToday', 'totalCutting', 'totalCuttingToday', 'totalRetouching', 'totalRetouchingToday', 'totalPacking', 'totalPackingToday', 'totalOrders'));
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
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
