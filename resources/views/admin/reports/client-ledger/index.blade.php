@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reports</h1>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label>Client * </label>
                    <select name="client_id" id="client_id" class="select2 form-control">
                        <option value="">Select Client *</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @if (old('client_id') == $client->id) selected @endif>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value=""
                        max="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ date('Y-m-d') }}"
                        max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                <button class="btn btn-primary reports-search">Search</button>
            </div>
        </div>
        <div class="card card-primary report-table">
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.reports-search').on('click', function() {
                let client_id = $('#client_id').val();
                let from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (client_id == '') {
                    alert('Please select client');
                    return;
                }
                if (to_date == '') {
                    // to date = current dat
                    to_date = new Date();
                }
                $.ajax({
                    url: "{{ route('reports.client-ledger.get-report') }}",
                    method: "get",
                    data: {
                        client_id: client_id,
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
