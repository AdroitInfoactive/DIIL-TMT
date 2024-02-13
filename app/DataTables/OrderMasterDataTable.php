<?php

namespace App\DataTables;

use App\Models\OrderMaster;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderMasterDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $view = "<a href='" . route('order.show', $query->id) . "' class='btn btn-primary'title='View' ><i class='fas fa-eye'></i></a>";
                /* $revise = "<a href='" . route('order.revise', $query->id) . "' class='btn btn-info mb-2' title='Revise'><i class='fas fa-history'></i></a>"; */
                $edit = "<a href='" . route('order.edit', $query->id) . "' class='btn btn-warning ml-2 mr-2' title='Edit'><i class='fas fa-edit'></i></a>";
                $delete = "<a href='" . route('order.delete', $query->id) . "' class='btn btn-danger delete-order' title='Delete'><i class='fas fa-trash'></i></a>";

                return $view . $edit . $delete;
            })
            ->addColumn('order_no', function ($query) {
                // call function to generate quote number
                return '<b>' . generateQuoteNumber($query->order_main_prefix, $query->order_entity_prefix, $query->order_financial_year, $query->order_no, $query->order_type) . '</b>';
            })
            ->addColumn('date', function ($query) {
                return date('d-m-Y', strtotime($query->created_at));
            })
            ->addColumn('client_name', function ($query) {
                return $query->client?->name;
            })
            ->addColumn('prepared_by', function ($query) {
                return $query->user?->name;
            })
            /* ->addColumn('status', function ($query) {
                if ($query->order_delete_status == 'y') {
                    $html = '<span class="badge badge-danger">Deleted</span>';
                } else {
                    $html = '<select class="form-control order_status" data-id="' . $query->id . '">
                    <option ' . ($query->order_status === 'p' ? 'selected' : '') . ' value="p">Pending</option>
                    <option ' . ($query->order_status === 'a' ? 'selected' : '') . ' value="a">Accepted</option>
                    <option ' . ($query->order_status === 'r' ? 'selected' : '') . ' value="r">Rejected</option>
                    </select>';
                }

                return $html;
            }) */
            ->rawColumns(['action', 'order_no', 'date', 'client_name', 'prepared_by', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(OrderMaster $model): QueryBuilder
    {
        return $model->newQuery()->where('order_delete_status', 'n');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ordermaster-table')
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
            Column::make('order_no'),
            Column::make('date'),
            Column::make('client_name'),
            Column::make('prepared_by'),
            // Column::make('status'),
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
        return 'OrderMaster_' . date('YmdHis');
    }
}
