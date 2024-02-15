<h4>Ledger of All Clients from {{ date('d-m-Y', strtotime($from_date)) }} to
    {{ date('d-m-Y', strtotime($to_date)) }}</h4>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-md">
        <tr>
            <th>S.No</th>
            <th>Client Name</th>
            <th style="text-align: right;">Orders Placed</th>
            <th style="text-align: right;">Received Amount</th>
            <th style="text-align: right;">Balance</th>
        </tr>
        @foreach ($receipts as $receipt)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a
                        href="{{ route('reports.client-ledger.client-report', ['client_id' => $receipt->client_id, 'from_date' => $from_date, 'to_date' => $to_date]) }}">{{ $receipt->client_name }}</a>
                </td>
                <td style="text-align: right;">{{ currencyPosition($receipt->ordered_amount) }}</td>
                <td style="text-align: right;">{{ currencyPosition($receipt->received_amount) }}</td>
                @php
                    if ($receipt->difference < 0) {
                        $clr_cls = 'color:red';
                    } else {
                        $clr_cls = 'color:green';
                    }
                @endphp
                <td style="text-align: right; {{ $clr_cls }};">
                    {{ currencyPosition($receipt->difference) }}</td>
            </tr>
        @endforeach
    </table>
</div>
