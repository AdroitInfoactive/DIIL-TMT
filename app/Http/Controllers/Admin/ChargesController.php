<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChargeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChargesCreateRequest;
use App\Http\Requests\Admin\ChargesUpdateRequest;
use App\Models\Charge;
use App\Models\Tax;
use Illuminate\Http\Request;

class ChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChargeDataTable $dataTable)
    {
        $taxes = Tax::all();
        return $dataTable->withTaxes($taxes)->render('admin.charges.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taxes = Tax::where('status', 1)->get();
        return view('admin.charges.create', compact('taxes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChargesCreateRequest $request)
    {
        $charges = new Charge();
        $charges->name = $request->name;
        $charges->description = $request->description;
        $charges->calculation_type = $request->calculation_type;
        $charges->calculation_on = $request->calculation_on;
        if ($request->calculation_on == 't') {
            $charges->referred_tax = $request->referred_tax;
        }
        else
        {
            $charges->referred_tax = "";
        }
        $charges->editable = $request->editable;
        $charges->value = $request->value;
        $charges->status = $request->status;
        $charges->save();
        toastr()->success('Charge Created Successfully');
        return redirect()->route('charges.index');
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
        $charge = Charge::findOrFail($id);
        $taxes = Tax::where('status', 1)->get();
        return view('admin.charges.edit', compact('charge', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChargesUpdateRequest $request, string $id)
    {
        $charges = Charge::findOrFail($id);
        $charges->name = $request->name;
        $charges->description = $request->description;
        $charges->calculation_type = $request->calculation_type;
        $charges->calculation_on = $request->calculation_on;
        if ($request->calculation_on == 't') {
            $charges->referred_tax = $request->referred_tax;
        }
        else
        {
            $charges->referred_tax = "";
        }
        $charges->editable = $request->editable;
        $charges->value = $request->value;
        $charges->status = $request->status;
        $charges->save();
        toastr()->success('Charge Updated Successfully');
        return redirect()->route('charges.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
