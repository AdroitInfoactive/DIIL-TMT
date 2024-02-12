<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TaxProductsDataTable;
use App\Http\Controllers\Controller;
use App\Models\CollectionTax;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CollectionTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $taxId, TaxProductsDataTable $dataTable)
    {
        $tax = Tax::findOrFail($taxId);
        $collectionTaxes = CollectionTax::where('tax_id', $tax->id)->get();
        // $products=Product::where(['status'=> 1, 'charge_tax'=> 1])->get();\
        return $dataTable->withTaxId($taxId)->render('admin.tax.collection-tax.index', compact('tax', 'collectionTaxes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'value' => ['required', 'numeric'],
            'tax_id' => ['required', 'integer']
        ]);

        $collectionTax = new CollectionTax();
        $collectionTax->tax_id = $request->tax_id;
        $collectionTax->name = $request->name;
        $collectionTax->value = $request->value;
        $collectionTax->save();

        toastr()->success('Created Successfully!');

        return redirect()->back();
    }

    // public function show(string $id):View
    // {
    //     $tax = Tax::find($id);
    //     $collectionTaxes = CollectionTax::where('tax_id', $tax->id)->get();
    //     $product=Product::where('tax_id', $tax->id)->get();
    //     return view('admin.tax.collection-tax.show', compact('tax', 'collectionTaxes', 'product'));
    // }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $collectioTax = CollectionTax::findOrFail($id);
            $collectioTax->delete();

            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
    public function updateCollectionList(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
            'tax_id' => ['required', 'integer'],
            'checked' => ['required']
        ]);
        $collectionTax = Product::findOrFail($request->id);
        if ($request->checked == 'true') {
            $collectionTax->tax_id = $request->tax_id;
        } else {
            $collectionTax->tax_id = "";
        }
        $collectionTax->save();
        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }
}
