<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
            $edit = "<a href='".route('product.edit', $query->id)."' class='btn btn-primary'><i class='fas fa-edit'></i></a>";
            $delete = "<a href='".route('product.destroy', $query->id)."' class='btn btn-danger delete-item ml-2'><i class='fas fa-trash'></i></a>";

            return $edit.$delete;
        }) ->addColumn('status', function($query){
            if($query->status === 1){
                return '<span class="badge badge-primary">Active</span>';
            }else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        })
        ->addColumn('code', function($query){
            if($query->code !== null){
                return $query->code;
            }else {
                return 'NA';
            }
        })
        ->addColumn('charge_tax', function($query){
            if($query->charge_tax === 1){
                return '<span class="badge badge-primary">Yes</span>';
            }else {
                return '<span class="badge badge-danger">No</span>';
            }
        })
        ->addColumn('tax_name', function($query){
            return $query->tax?->name;
        })
        ->rawColumns(['show_at_home', 'status', 'action', 'code', 'charge_tax', 'tax_name'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
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
            Column::make('code'),
            Column::make('charge_tax'),
            Column::make('tax_name'),
            Column::make('status'),
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
        return 'Product_' . date('YmdHis');
    }
}
