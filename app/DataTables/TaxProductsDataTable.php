<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\TaxProduct;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TaxProductsDataTable extends DataTable
{
    protected $taxId;

    public function withTaxId($taxId)
    {
        $this->taxId = $taxId;
        return $this;
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $taxId = $this->taxId;
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) use ($taxId) {
                if ($query->tax_id == $taxId) {
                    $cheked = 'checked';
                } else {
                    $cheked = '';
                }
                $checkbox = '<input type="checkbox" name="products[]" value="' . $query->id . '" ' . $cheked . ' class="update-product" data-id="' . $query->id . '" style="width: 20px; height: 20px;">
                <input type="hidden" name="tax_id" value="' . $taxId . '">';
                return $checkbox;
            })
            ->addColumn('name', function ($query) {
                return '<b class="text-primary" style=" text-transform: uppercase;">' . $query->name . '</b>';
            })
            ->rawColumns(['action', 'name'])
            ->setRowId('');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $taxId = $this->taxId;
        return $model->where(['status' => 1, 'charge_tax' => 1])->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taxproducts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('action')->width(150)
                ->addClass('text-center'),
            Column::make('name'),
            Column::make('code'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TaxProducts_' . date('YmdHis');
    }
}
