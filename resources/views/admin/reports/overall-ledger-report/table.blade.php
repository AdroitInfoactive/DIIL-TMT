<h4>Overall Ledger Report from {{ date('d-m-Y', strtotime($from_date)) }} to
    {{ date('d-m-Y', strtotime($to_date)) }}</h4>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-md">
        <tr>
            <th style="text-align: right;">Orders Placed</th>
            <th style="text-align: right;">Received Amount</th>
            <th style="text-align: right;">Expenses</th>
        </tr>
        <tr>
            <td style="text-align: right;">{{ currencyPosition($orders->ordered_amount) }}</td>
            <td style="text-align: right;">{{ currencyPosition($orders->received_amount) }}</td>
            <td style="text-align: right;">{{ currencyPosition($expenses->expenses_amount) }}</td>
        </tr>
    </table>
</div>
