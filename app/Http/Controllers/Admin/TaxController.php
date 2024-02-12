<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TaxDataTable;
use App\Http\Controllers\Controller;
use App\Models\CollectionTax;
use App\Models\Tax;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.tax.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tax.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:taxes,name'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $tax = new Tax();
        $tax->name = $request->name;
        $tax->description = $request->description;
        $tax->status = $request->status;
        $tax->save();
        toastr()->success('Tax Created Successfully');
        return redirect()->route('tax.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): view
    {
        $tax = Tax::find($id);
        $collectionTaxes = CollectionTax::where('tax_id', $tax->id)->get();
        return view('admin.tax.show', compact('tax', 'collectionTaxes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): view
    {
        $tax = Tax::find($id);
        return view('admin.tax.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:taxes,name,' . $id],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $tax = Tax::find($id);
        $tax->name = $request->name;
        $tax->description = $request->description;
        $tax->status = $request->status;
        $tax->save();
        toastr()->success('Tax Updated Successfully');
        return to_route('tax.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $tax = Tax::findOrFail($id);
            $tax->delete();
            return response(['status' => 'success', 'message' => 'Tax Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
