<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReceiptDataTable;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ReceiptDataTable $dataTable)
    {
        return $dataTable->render('admin.receipt.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 1)->get();
        return view('admin.receipt.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'received_date' => ['required', 'date'],
            'received_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:500'],
            'transaction_type' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string'],
        ]);
        $receipt = new Receipt();
        $receipt->client_id = $request->client_id;
        $receipt->received_date = $request->received_date;
        $receipt->received_amount = $request->received_amount;
        $receipt->description = $request->description;
        $receipt->transaction_type = $request->transaction_type;
        $receipt->transaction_reference = $request->transaction_id;
        $receipt->save();
        toastr()->success('Receipt Created Successfully');
        return to_route('receipt.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $receipt = Receipt::findOrFail($id);
        return view('admin.receipt.show', compact('receipt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $receipt = Receipt::findOrFail($id);
        $clients = Client::where('status', 1)->get();
        return view('admin.receipt.edit', compact('receipt', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'received_date' => ['required', 'date'],
            'received_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:500'],
            'transaction_type' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string'],
        ]);
        $receipt = Receipt::findOrFail($id);
        $receipt->client_id = $request->client_id;
        $receipt->received_date = $request->received_date;
        $receipt->received_amount = $request->received_amount;
        $receipt->description = $request->description;
        $receipt->transaction_type = $request->transaction_type;
        $receipt->transaction_reference = $request->transaction_id;
        $receipt->save();
        toastr()->success('Receipt Updated Successfully');
        return to_route('receipt.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $receipt = Receipt::findOrFail($id);
            $receipt->delete();
            return response(['status' => 'success', 'message' => 'Receipt Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
