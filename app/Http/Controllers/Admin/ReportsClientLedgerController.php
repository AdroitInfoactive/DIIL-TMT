<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\OrderMaster;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReportsClientLedgerController extends Controller
{
    public function clientLedger()
    {
        $clients = Client::all();
        return view('admin.reports.client-ledger.index', compact('clients'));
    }
    public function getReport()
    {
        $client_id = request()->client_id;
        $from_date = request()->from_date;
        $to_date = request()->to_date;
        $client = Client::findOrFail($client_id);
        // Query to sum the received_amount and ordered_amount for dates before the from_date
        if (!is_null($from_date)) {
        $openingBalance = Receipt::selectRaw('SUM(COALESCE(received_amount, 0)) - SUM(COALESCE(ordered_amount, 0)) AS opening_balance')
            ->where('client_id', $client_id)
            ->where('received_date', '<', $from_date)
            ->first();
        } else {
            $openingBalance = null;
        }
        // dd($openingBalance);
        // Main query to fetch the receipts
        $query = Receipt::selectRaw('*, receipts.description as receipt_description')
            ->join('clients', 'receipts.client_id', '=', 'clients.id')
            ->where('receipts.client_id', $client_id)
            ->orderBy('received_date', 'asc');

        if (!is_null($from_date)) {
            $query->where('received_date', '>=', $from_date);
        }
        // Add the to_date filter
        $query->where('received_date', '<=', $to_date);
        $receipts = $query->get();

        return view('admin.reports.client-ledger.table', compact('receipts', 'client', 'from_date', 'to_date', 'openingBalance'));
    }
}
