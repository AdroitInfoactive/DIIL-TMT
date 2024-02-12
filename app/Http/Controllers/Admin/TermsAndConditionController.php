<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TermsAndConditionDataTable;
use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TermsAndConditionDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.terms-and-condition.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.terms-and-condition.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:terms_and_conditions,name'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $termsAndCondition = new TermsAndCondition();
        $termsAndCondition->name = $request->name;
        $termsAndCondition->description = $request->description;
        $termsAndCondition->status = $request->status;
        $termsAndCondition->save();
        toastr()->success('Terms And Condition Created Successfully');
        return redirect()->route('terms-and-conditions.index');
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
        $termsAndCondition = TermsAndCondition::find($id);
        return view('admin.terms-and-condition.edit', compact('termsAndCondition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:terms_and_conditions,name,' . $id],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ]);
        $termsAndCondition = TermsAndCondition::find($id);
        $termsAndCondition->name = $request->name;
        $termsAndCondition->description = $request->description;
        $termsAndCondition->status = $request->status;
        $termsAndCondition->save();
        toastr()->success('Terms And Condition Updated Successfully');
        return redirect()->route('terms-and-conditions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $termsAndCondition = TermsAndCondition::findOrFail($id);

            $termsAndCondition->delete();

            return response(['status' => 'success', 'message' => 'Terms And Condition Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
