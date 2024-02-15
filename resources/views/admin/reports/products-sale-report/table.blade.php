<div class="card card-primary report-table">
    <h4>Product Sale Report from {{ date('d-m-Y', strtotime($from_date)) }} to
        {{ date('d-m-Y', strtotime($to_date)) }}</h4>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-md">
            <tr>
                <th>S.No</th>
                <th>Product</th>
                <th>UOM</th>
                <th>Make</th>
                <th style="text-align: right;">Quantity Sold</th>
            </tr>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->uom }}</td>
                    <td>{{ $product->make }}</td>
                    <td style="text-align: right;">{{ $product->quantity }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
