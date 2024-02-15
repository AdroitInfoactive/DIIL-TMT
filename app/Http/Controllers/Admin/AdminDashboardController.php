<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\OrderMaster;
use App\Models\Product;
use App\Models\Receipt;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    function index(): View{
        $clients = Client::count();
        $products = Product::count();
        $todayOrders = OrderMaster::whereDate('created_at', date('Y-m-d'))->count();
        $todayReceipts = Receipt::whereDate('created_at', date('Y-m-d'))->count();
        $currentMonthOrders = OrderMaster::whereMonth('created_at', date('m'))->count();
        $currentMonthReceipts = Receipt::whereMonth('created_at', date('m'))->count();
        $allOrders = OrderMaster::count();
        $allReceipts = Receipt::count();
        return view('admin.dashboard.index', compact('clients', 'products', 'todayOrders', 'currentMonthOrders', 'allOrders', 'todayReceipts', 'currentMonthReceipts', 'allReceipts'));
    }
}
