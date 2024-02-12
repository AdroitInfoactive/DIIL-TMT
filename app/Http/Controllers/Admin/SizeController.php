<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SizeDataTable;
use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SizeDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.size.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.size.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:sizes,name'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $size = new Size();
        $size->name = $request->name;
        $size->description = $request->description;
        $size->status = $request->status;
        $size->save();
        toastr()->success('Size Created Successfully');
        return redirect()->route('size.index');
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
        $size = Size::find($id);
        return view('admin.size.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:sizes,name,' . $id],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $size = Size::find($id);
        $size->name = $request->name;
        $size->description = $request->description;
        $size->status = $request->status;
        $size->save();
        toastr()->success('Size Updated Successfully');
        return to_route('size.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();
            return response(['status' => 'success', 'message' => 'Size Deleted Successfully!']);
        }
        catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}

