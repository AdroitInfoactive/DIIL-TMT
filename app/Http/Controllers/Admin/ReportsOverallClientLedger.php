<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReportsOverallClientLedger extends Controller
{
    public function overallLedgerReport()
    {
        $financialYear = getFinancialYearWithDates();
        $from_date = $financialYear['start_date'];
        $to_date = $financialYear['end_date'];
        $orders = Receipt::selectRaw('SUM(COALESCE(ordered_amount, 0)) as ordered_amount, SUM(COALESCE(received_amount, 0)) as received_amount')
            ->whereBetween('received_date', [$from_date, $to_date])
            ->first();
        $expenses = Expense::selectRaw('SUM(COALESCE(expenses_amount, 0)) as expenses_amount')
            ->whereBetween('expenses_date', [$from_date, $to_date])
            ->first();
        return view('admin.reports.overall-ledger-report.index', compact('orders', 'from_date', 'to_date', 'expenses'));
    }
    public function getReport()
    {
        $from_date = request()->from_date;
        $to_date = request()->to_date;
        $orders = Receipt::selectRaw('SUM(COALESCE(ordered_amount, 0)) as ordered_amount, SUM(COALESCE(received_amount, 0)) as received_amount')
            ->whereBetween('received_date', ["$from_date", "$to_date"])
            ->first();
        $expenses = Expense::selectRaw('SUM(COALESCE(expenses_amount, 0)) as expenses_amount')
            ->whereBetween('expenses_date', ["$from_date", "$to_date"])
            ->first();
        return view('admin.reports.overall-ledger-report.table', compact('orders', 'from_date', 'to_date', 'expenses'));
    }
}
