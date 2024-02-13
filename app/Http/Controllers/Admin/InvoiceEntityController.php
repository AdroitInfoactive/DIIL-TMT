<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\InvoiceEntityDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InvoiceEntityCreateRequest;
use App\Http\Requests\Admin\InvoiceEntityUpdateRequest;
use App\Models\InvoiceEntity;
use Illuminate\Http\Request;

class InvoiceEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvoiceEntityDataTable $dataTable)
    {
        return $dataTable->render('admin.invoice-entity.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.invoice-entity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceEntityCreateRequest $request)
    {
        $invoiceEntity = new InvoiceEntity();
        $invoiceEntity->name = $request->name;
        $invoiceEntity->invoice_prefix = $request->invoice_prefix;
        $invoiceEntity->gst_no = $request->gst_no;
        $invoiceEntity->address = $request->address;
        $invoiceEntity->area = $request->area;
        $invoiceEntity->city = $request->city;
        $invoiceEntity->state = $request->state;
        $invoiceEntity->country = $request->country;
        $invoiceEntity->pincode = $request->pincode;
        $invoiceEntity->primary_name = $request->primary_name;
        $invoiceEntity->primary_mobile = $request->primary_mobile;
        $invoiceEntity->primary_email = $request->primary_email;
        $invoiceEntity->primary_designation = $request->primary_designation;
        $invoiceEntity->account_name = $request->account_name;
        $invoiceEntity->account_number = $request->account_number;
        $invoiceEntity->ifsc_code = $request->ifsc_code;
        $invoiceEntity->bank_name = $request->bank_name;
        $invoiceEntity->branch = $request->branch;
        $invoiceEntity->description = $request->description;
        $invoiceEntity->status = $request->status;
        $invoiceEntity->save();
        toastr()->success('Invoice Entity Created Successfully');
        return to_route('invoice-entity.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoiceEntity = InvoiceEntity::findOrFail($id);
        return view('admin.invoice-entity.show', compact('invoiceEntity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoiceEntity = InvoiceEntity::findOrFail($id);
        return view('admin.invoice-entity.edit', compact('invoiceEntity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceEntityUpdateRequest $request, string $id)
    {
        $invoiceEntity = InvoiceEntity::findOrFail($id);
        $invoiceEntity->name = $request->name;
        $invoiceEntity->invoice_prefix = $request->invoice_prefix;
        $invoiceEntity->gst_no = $request->gst_no;
        $invoiceEntity->address = $request->address;
        $invoiceEntity->area = $request->area;
        $invoiceEntity->city = $request->city;
        $invoiceEntity->state = $request->state;
        $invoiceEntity->country = $request->country;
        $invoiceEntity->pincode = $request->pincode;
        $invoiceEntity->primary_name = $request->primary_name;
        $invoiceEntity->primary_mobile = $request->primary_mobile;
        $invoiceEntity->primary_email = $request->primary_email;
        $invoiceEntity->primary_designation = $request->primary_designation;
        $invoiceEntity->account_name = $request->account_name;
        $invoiceEntity->account_number = $request->account_number;
        $invoiceEntity->ifsc_code = $request->ifsc_code;
        $invoiceEntity->bank_name = $request->bank_name;
        $invoiceEntity->branch = $request->branch;
        $invoiceEntity->description = $request->description;
        $invoiceEntity->status = $request->status;
        $invoiceEntity->save();
        toastr()->success('Invoice Entity Updated Successfully');
        return to_route('invoice-entity.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $invoiceEntity = InvoiceEntity::findOrFail($id);
            $invoiceEntity->delete();
            return response(['status' => 'success', 'message' => 'Invoice Entity Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
