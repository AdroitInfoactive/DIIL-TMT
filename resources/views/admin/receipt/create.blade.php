@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Receipts</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Add Receipt</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('receipt.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Client * </label>
                                <select name="client_id" id="client_id" class="select2 form-control">
                                    <option value="">Select Client *</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            @if (old('client_id') == $client->id) selected @endif>{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-8" id="client-details"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Received Date *</label>
                                <input type="date" name="received_date" class="form-control" value="{{ date('Y-m-d') }}"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Amount *</label>
                                <input type="text" name="received_amount" value="{{ old('received_amount') }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Transaction Type *</label>
                                <select name="transaction_type" id="transaction_type" class="select2 form-control">
                                    <option value="">Select Transaction Type *</option>
                                    <option value="cash" @if (old('transaction_type') == 'cash') selected @endif>Cash</option>
                                    <option value="cheque" @if (old('transaction_type') == 'cheque') selected @endif>Cheque</option>
                                    <option value="bank transfer" @if (old('transaction_type') == 'bank transfer') selected @endif>Bank
                                        Transfer</option>
                                    <option value="others" @if (old('transaction_type') == 'others') selected @endif>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Transaction Reference Number </label>
                                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadClientDetails(org_id) {
                if (org_id != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('order.get-client-details', ':org_id') }}".replace(':org_id',
                            org_id),
                        success: function(data) {
                            var client = data;
                            var clientHTML =
                                `<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#FFFFFF">
                            <tbody>
                            <tr>
                                <td bgcolor="#EEEEEE"><strong>Client Name :</strong> ` + client.name + `</td>
                                <td bgcolor="#EEEEEE"><strong>Address : </strong>` + client.address + `</td>
                                <td bgcolor="#EEEEEE"><strong>Area : </strong>` + client.area + `</td>
                            </tr>
                            <tr>
                                <td bgcolor="#EEEEEE"><strong>City :</strong>` + client.city + `</td>
                                <td bgcolor="#EEEEEE"><strong>State : </strong>` + client.state + `</td>
                                <td bgcolor="#EEEEEE"><strong>Country & Pincode : </strong>` + client.country +
                                `, ` + client.pincode + `</td>
                            </tr>
                            <tr>
                                <td bgcolor="#EEEEEE"><strong>Email : </strong>` + client.email + `</td>
                                <td bgcolor="#EEEEEE"><strong>Primary Contact :</strong>` + client.primary_name + `</td>
                                <td bgcolor="#EEEEEE"><strong>Phone : </strong>` + client.primary_mobile + `</td>
                            </tr>
                            <tr>
                                <td bgcolor="#EEEEEE" colspan="3"><strong>GST No : </strong>` + client.gst_no + `</td>
                            </tr>
                            `;
                            $('#client-details').html(clientHTML);
                        },
                        error: function(xhr, status, error) {
                            $('#client-details').html(
                                '<b style="text-align: center; padding-left: 40%"><code>No Client Found</code></b>'
                            );
                            // console.error('Error fetching client details:', error);
                        }
                    })
                }
            }

            // Get the selected client_id ID
            var org_id = $('#client_id').val();

            // Load client details if client_id is selected
            if (org_id) {
                loadClientDetails(org_id);
            }

            // AJAX call to load client details when client_id is changed
            $(document).on('change', '#client_id', function(e) {
                e.preventDefault();
                var org_id = $(this).val();
                loadClientDetails(org_id);
            });
        })
    </script>
@endpush
