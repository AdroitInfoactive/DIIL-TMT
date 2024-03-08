<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExpenseDataTable $dataTable)
    {
        return $dataTable->render('admin.expenses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'expenses_date' => ['required', 'date'],
            'expenses_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $expense = new Expense();
        $expense->name = $request->name;
        $expense->expenses_date = $request->expenses_date;
        $expense->expenses_amount = $request->expenses_amount;
        $expense->description = $request->description;
        $expense->save();
        toastr()->success('Expense Created Successfully');
        return to_route('expenses.index');
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
        $expense = Expense::findOrFail($id);
        return view('admin.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'expenses_date' => ['required', 'date'],
            'expenses_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $expense = Expense::findOrFail($id);
        $expense->name = $request->name;
        $expense->expenses_date = $request->expenses_date;
        $expense->expenses_amount = $request->expenses_amount;
        $expense->description = $request->description;
        $expense->save();
        toastr()->success('Expense Updated Successfully');
        return to_route('expenses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return response(['status' => 'success', 'message' => 'Expense Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
