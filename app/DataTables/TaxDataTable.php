<?php

namespace App\DataTables;

use App\Models\Tax;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TaxDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('action', function($query){
            // $view = "<a href='".route('tax.show', $query->id)."' class='btn btn-primary'><i class='fas fa-eye'></i></a>";
            $edit = "<a href='".route('tax.edit', $query->id)."' class='btn btn-primary ml-2'><i class='fas fa-edit'></i></a>";
            $delete = "<a href='".route('tax.destroy', $query->id)."' class='btn btn-danger delete-item mx-2'><i class='fas fa-trash'></i></a>";
            $more = '<a class="btn btn-dark" href="'.route('collection-tax.show-index', $query->id).'"><i class="fas fa-cog"></i></a> ';
            return $edit.$delete.$more;
        }) ->addColumn('status', function($query){
            if($query->status === 1){
                return '<span class="badge badge-primary">Active</span>';
            }else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        })
        ->rawColumns([ 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Tax $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tax-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
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

            Column::make('status'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(250)
            ->addClass('text-center')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tax_' . date('YmdHis');
    }
}
