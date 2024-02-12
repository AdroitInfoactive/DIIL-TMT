<?php

namespace App\DataTables;

use App\Models\Charge;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChargeDataTable extends DataTable
{
    protected $taxes;
    public function withTaxes($taxes)
    {
        $this->taxes = $taxes;
        return $this;
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $taxes = $this->taxes;
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $edit = "<a href='" . route('charges.edit', $query->id) . "' class='btn btn-warning ml-2 mr-2'><i class='fas fa-edit'></i></a>";
                $delete = "<a href='" . route('charges.destroy', $query->id) . "' class='btn btn-danger delete-item'><i class='fas fa-trash'></i></a>";
                return $edit . $delete;
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<span class="badge badge-primary">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('calculation_type', function ($query) {
                if ($query->calculation_type == 'v') {
                    return 'Value';
                } else {
                    return 'Percentage';
                }
            })
            ->addColumn('calculation_on', function ($query) {
                if ($query->calculation_on == 'f') {
                    return 'Fixed';
                }
                elseif ($query->calculation_on == 'w') {
                    return 'Net Weight(PMT)';
                }
                elseif ($query->calculation_on == 'n') {
                    return 'Net Price';
                }
                elseif ($query->calculation_on == 'g') {
                    return 'Gross Price';
                }
                elseif ($query->calculation_on == 't') {
                    return 'Taxes';
                }
                else {
                    return 'NA';
                }
            })
            ->addColumn('referred_tax', function ($query) use ($taxes) {

                // Decode JSON string to an array
                $taxesArray = json_decode($taxes, true);

                // Convert the array to a Laravel Collection for easier manipulation
                $taxesCollection = collect($taxesArray);

                // Example usage:
                $chargeTaxId = $query->referred_tax; // Replace with the actual tax ID you want to retrieve

                // Find the tax with the specified ID
                $tax = $taxesCollection->firstWhere('id', $chargeTaxId);

                // Retrieve the tax name or provide a default value if not found
                return $tax ? $tax['name'] : 'NA';
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Charge $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('charge-table')
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
            Column::make('value'),
            Column::make('calculation_type'),
            Column::make('calculation_on'),
            Column::make('referred_tax'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Charge_' . date('YmdHis');
    }
}
