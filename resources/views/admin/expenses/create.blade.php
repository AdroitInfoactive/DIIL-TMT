@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Expenses</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Add expense</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Received Date *</label>
                                <input type="date" name="expenses_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Amount *</label>
                                <input type="text" name="expenses_amount" value="{{ old('expenses_amount') }}"
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