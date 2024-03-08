<?php

namespace App\DataTables;

use App\Models\Expense;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class ExpenseDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return(new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                // $view = "<a href='" . route('expenses.show', $query->id) . "' class='btn btn-primary'title='View' ><i class='fas fa-eye'></i></a>";
                /* $revise = "<a href='" . route('order.revise', $query->id) . "' class='btn btn-info mb-2' title='Revise'><i class='fas fa-history'></i></a>"; */
                $edit = "<a href='" . route('expenses.edit', $query->id) . "' class='btn btn-warning ml-2 mr-2' title='Edit'><i class='fas fa-edit'></i></a>";
                $delete = "<a href='" . route('expenses.destroy', $query->id) . "' class='btn btn-danger delete-item' title='Delete'><i class='fas fa-trash'></i></a>";

                return $edit . $delete;
            })
            ->addColumn('expenses_amount', function ($query) {
                return "<p class='text-right'>" . currencyPosition($query->expenses_amount) . "</p>";
            })
            ->addColumn(('expenses_date'), function ($query) {
                return date('d-m-Y', strtotime($query->expenses_date));
            })
            ->rawColumns(['action', 'expenses_amount'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Expense $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expense-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(2)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('expenses_date'),
            Column::make('expenses_amount'),
            Column::make('description'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Expense_' . date('YmdHis');
    }
}
