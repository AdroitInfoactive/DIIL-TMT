<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCreateRequest;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taxes = Tax::where('status', 1)->get();
        return view('admin.product.create', compact('taxes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request): RedirectResponse
    {
        $product = new Product();
        $product->name = $request->name;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->slug = Str::slug($request->name);
        if (!$request->charge_tax) {
            $product->charge_tax = 0;
        } else {
            $product->charge_tax = $request->charge_tax;
        }
        $product->tax_id = $request->tax_id;
        $product->status = $request->status;
        $product->save();
        toastr()->success('Product Created Successfully');
        return to_route('product.index');
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
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $taxes = Tax::where('status', 1)->get();
        return view('admin.product.edit', compact('product', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        if (!$request->charge_tax) {
            $product->charge_tax = 0;
        } else {

            $product->charge_tax = $request->charge_tax;
        }
        $product->tax_id = $request->tax_id;
        $product->save();
        toastr()->success('Update Successfully');
        return to_route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $product = Product::findOrFail($id);

            $product->delete();

            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
