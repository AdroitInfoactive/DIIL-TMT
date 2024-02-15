<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReportsAllClientLedgerController extends Controller
{
    public function allClientLedger()
    {
        $financialYear = getFinancialYearWithDates();
        $from_date = $financialYear['start_date'];
        $to_date = $financialYear['end_date'];
        $receipts = Receipt::selectRaw('clients.name as client_name, clients.id as client_id,
                                SUM(COALESCE(received_amount, 0)) as received_amount,
                                SUM(COALESCE(ordered_amount, 0)) as ordered_amount,
                                (SUM(COALESCE(received_amount, 0)) - SUM(COALESCE(ordered_amount, 0))) as difference')
            ->join('clients', 'receipts.client_id', '=', 'clients.id')
            ->whereBetween('received_date', [$from_date, $to_date])
            ->groupBy('clients.id', 'clients.name')
            ->orderBy('difference', 'asc')
            ->get();
        return view('admin.reports.all-clients-ledger.index', compact('receipts', 'from_date', 'to_date'));
    }
    public function getReport()
    {
        $from_date = request()->from_date;
        $to_date = request()->to_date;
        $receipts = Receipt::selectRaw('clients.name as client_name, clients.id as client_id,
                                SUM(COALESCE(received_amount, 0)) as received_amount,
                                SUM(COALESCE(ordered_amount, 0)) as ordered_amount,
                                (SUM(COALESCE(received_amount, 0)) - SUM(COALESCE(ordered_amount, 0))) as difference')
            ->join('clients', 'receipts.client_id', '=', 'clients.id')
            ->whereBetween('received_date', ["$from_date", "$to_date"])
            ->groupBy('clients.id', 'clients.name')
            ->orderBy('difference', 'asc')
            ->get();
        /* dd([
            'sql' => $receipts->toSql(),
            'bindings' => $receipts->getBindings(),
        ]); */
        return view('admin.reports.all-clients-ledger.table', compact('receipts', 'from_date', 'to_date'));
    }
}
