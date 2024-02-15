@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reports</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>All Clients Ledger Report</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="{{ $from_date }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="{{ $to_date }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary reports-search">Search</button>
                    </div>
                </div>
                <div class="card card-primary report-table">
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
                                    <td>{{ $receipt->client_name }}</td>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.reports-search').on('click', function() {
                let from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date == '') {
                    alert('Please select from date');
                    return;
                }
                if (to_date == '') {
                    alert('Please select to date');
                    return;
                }
                $.ajax({
                    url: "{{ route('reports.all-client-ledger.get-report') }}",
                    method: "get",
                    data: {
                        from_date: from_date,
                        to_date: to_date
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $('.report-table').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching reports, please try again.');
                    },
                    complete: function() {

                    }
                })
            })
        })
    </script>
@endpush
