<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\VendorDataTable;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VendorDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:vendors,name'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->description = $request->description;
        $vendor->status = $request->status;
        $vendor->save();
        toastr()->success('Brand Created Successfully');
        return redirect()->route('brand.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $vendor = Vendor::find($id);
        return view('admin.brand.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:vendors,name,' . $id],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $vendor = Vendor::find($id);
        $vendor->name = $request->name;
        $vendor->description = $request->description;
        $vendor->status = $request->status;
        $vendor->save();
        toastr()->success('Vendor Updated Successfully');
        return redirect()->route('brand.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $vendor = Vendor::findOrFail($id);

            $vendor->delete();

            return response(['status' => 'success', 'message' => 'Vendor Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
