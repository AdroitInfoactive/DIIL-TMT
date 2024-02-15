<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ReportsProductSaleController extends Controller
{
    public function allProductSaleReport()
    {
        $financialYear = getFinancialYearWithDates();
        $from_date = $financialYear['start_date'];
        $to_date = $financialYear['end_date'];
        $products = OrderDetail::selectRaw('products.name as product_name,
                                    sizes.name as uom,
                                    vendors.name as make,
                                    SUM(COALESCE(order_details.quantity, 0)) as quantity')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('sizes', 'order_details.uom_id', '=', 'sizes.id')
            ->join('vendors', 'order_details.make_id', '=', 'vendors.id')
            ->whereBetween('order_details.created_at', ["$from_date", "$to_date"])
            ->groupBy('products.id', 'products.name', 'sizes.name', 'vendors.name')
            ->orderBy('quantity', 'desc')
            ->get();

        return view('admin.reports.products-sale-report.index', compact('products', 'from_date', 'to_date'));
    }
    public function getReport()
    {
        $from_date = request()->from_date;
        $to_date = request()->to_date;
        $products = OrderDetail::selectRaw('products.name as product_name,
                                    sizes.name as uom,
                                    vendors.name as make,
                                    SUM(COALESCE(order_details.quantity, 0)) as quantity')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('sizes', 'order_details.uom_id', '=', 'sizes.id')
            ->join('vendors', 'order_details.make_id', '=', 'vendors.id')
            ->whereBetween('order_details.created_at', ["$from_date", "$to_date"])
            ->groupBy('products.id', 'products.name', 'sizes.name', 'vendors.name')
            ->orderBy('quantity', 'desc')
            ->get();
        /* dd([
            'sql' => $receipts->toSql(),
            'bindings' => $receipts->getBindings(),
        ]); */
        return view('admin.reports.products-sale-report.table', compact('products', 'from_date', 'to_date'));
    }
}
