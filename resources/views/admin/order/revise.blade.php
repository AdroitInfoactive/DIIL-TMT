@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Order</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Revise Order</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="revise" id="revise" value="r">
                    <input type="hidden" name="quotid" id="quotid" value="{{ $qmaster->id }}">
                    <input type="hidden" name="quotno" id="quotno" value="{{ $qmaster->quotation_no }}">
                    <div class="col-6">
                        <div class="form-group">
                            <p>Order No : <span
                                    class="text-danger">{{ generateQuoteNumber($qmaster->quotation_main_prefix, $qmaster->quotation_entity_prefix, $qmaster->quotation_financial_year, $qmaster->quotation_no, $qmaster->quotation_type) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="invoice_entity" id="invoice_entity" class="select2 form-control">
                                    <option value="">Select Invoice Entity *</option>
                                    @foreach ($invoiceEntities as $invoiceEntity)
                                        <option value="{{ $invoiceEntity->id }}"
                                            @if ($qmaster->invoice_entity_id == $invoiceEntity->id) selected @endif>{{ $invoiceEntity->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="organization" id="organization" class="select2 form-control">
                                    <option value="0">Select Client *</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            @if ($qmaster->client_id == $client->id) selected @endif>{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="client-details">
                    </div>
                    <div class="row">
                        <div class="col-12 disp_added_products">
                            @if (!empty($quotationProducts))
                                @include('admin.order.product-table')
                            @endif
                        </div>
                    </div>
                    {{-- <div class="form-group text-right">
                    <a href="javascript:;" class="make1-detail-tax" style="color: red"> Detail Taxes</a>
                </div> --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group text-right">
                                <a href="#" class='btn btn-primary edit-product' data-id="">Add Product
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <a href="#" id="charges"> Add / Edit Charges</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <a href="#" id="terms_and_conditions"> Add / Edit Terms and Conditions</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group disp_terms">
                        @if (!empty($quotationTerms))
                            @include('admin.order.terms-table')
                        @endif
                    </div>
                    <div class="form-group">
                        <textarea name="note" id="" cols="30" rows="10" class="form-control" placeholder="Add Note">{!! @$qmaster->quotation_note !!}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="prepared_by" class="form-control" value="{{ Auth::user()->name }}"
                            readonly>
                    </div>
                    {{-- <div class="form-group">
                    <select name="status" class="form-control" id="">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div> --}}
                    <button type="submit" class="btn btn-primary">Revise</button>
                </form>
            </div>
        </div>
    </section>
    @include('admin.order.add-product-modal')
    @include('admin.order.terms-and-conditions-modal')
    @include('admin.order.charges-modal')
    @include('admin.order.detail-taxes')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            function beforeUnloadHandler(e) {
                // Custom message to prompt the user for confirmation
                var confirmationMessage =
                    'Are you sure you want to refresh the page? Any unsaved changes will be lost.';
                // Set the confirmation message (not supported in all browsers)
                e.returnValue = confirmationMessage;
                // Show the confirmation dialog (supported in most modern browsers)
                return confirmationMessage;
            }
            // Add event listener for beforeunload
            window.addEventListener('beforeunload', beforeUnloadHandler);
            // Add event listener for form submit
            $(document).on("submit", "form", function() {
                // Remove the beforeunload event listener temporarily
                window.removeEventListener('beforeunload', beforeUnloadHandler);
            });
            // Function to load client details
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
            // Get the selected organization ID
            var org_id = $('#organization').val();
            // Load client details if organization is selected
            if (org_id) {
                loadClientDetails(org_id);
            }
            // AJAX call to load client details when organization is changed
            $(document).on('change', '#organization', function(e) {
                e.preventDefault();
                var org_id = $(this).val();
                loadClientDetails(org_id);
            });
            // ----------------------- Terms and conditions ----------------------------
            $(document).on('click', '#terms_and_conditions', function(e) {
                e.preventDefault();
                $('#terms_condition_modal').modal('show');
            });
            $(document).on('click', '.terms-and-conditions', function(e) {
                e.preventDefault();
                var selectedTerms = $('input[name="terms_condition[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                if (selectedTerms.length === 0) {
                    alert('Please select at least one term and condition.');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('order.add-terms') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        terms: selectedTerms
                    },
                    success: function(data) {
                        $('.disp_terms').html(data);
                        $('#terms_condition_modal').modal('hide');
                        toastr.success('Terms and Conditions added successfully');
                    },
                    error: function(xhr, status, error) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        })
                    }
                });
            });
            // -----------------------end terms and conditions ----------------------------  
            // ----------------------- charges-------------------------------------------
            $(document).on('click', '#charges', function(e) {
                e.preventDefault();
                $('#charges_modal').modal('show');
            });
            $('.charge_select').on('change', function() {
                var checkbox = $(this);
                var chargeId = checkbox.val();
                var editableField = $('input[name="charge_values[' + chargeId + ']"]');
                if (checkbox.is(':checked')) {
                    editableField.removeClass('d-none');
                } else {
                    editableField.addClass('d-none');
                }
            });
            $(document).on('click', '.charges_save', function(e) {
                e.preventDefault();
                var selectedCharges = $('input[name="charges_new[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                if (selectedCharges.length === 0) {
                    alert('Please select at least one charge.');
                    return;
                }
                // if (selectedCharges.length === 0) {
                //     $('#charges_modal').modal('hide');
                //     return;
                // }
                var chargeValues = {};
                $.each(selectedCharges, function(index, chargeId) {
                    var editableField = $('input[name="charge_values[' + chargeId + ']"]');
                    if (editableField.length && editableField.val().trim() !== '') {
                        chargeValues[chargeId] = editableField.val();
                    } else {
                        chargeValues[chargeId] = $('#hdn_chrg_values_' + chargeId).val();
                    }
                });
                // console.log(chargeValues);
                $.ajax({
                    type: "POST",
                    url: "{{ route('order.add-charges') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        charges: selectedCharges,
                        value: chargeValues
                    },
                    success: function(data) {
                        $('.disp_added_products').html(data);
                        $('#charges_modal').modal('hide');
                        toastr.success('Charges added successfully');
                        // reload page
                        // location.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error("Something went wrong!");
                    }
                });
            });
            // ------------------------- end charges --------------------------------
            // ----------------------- on calculation type change ----------------------------
            $('#product').on('change', function(e, slcted) {
                e.preventDefault();
                var product_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('order.get-taxes', ':product_id') }}".replace(
                        ':product_id',
                        product_id),
                    success: function(data) {
                        if (data != '') {
                            var chkbox = ``;
                            var select = (slcted && slcted.slcted) ? slcted.slcted :
                                null;
                            $.each(data, function(key, value) {
                                var isChecked = select?.includes(value.id
                                    .toString());
                                chkbox += `
                                <div class="form-check col-md-3">
                                        <input class="form-check-input" name="taxes[]" type="checkbox" id="defaultCheck${value.id}"
                                                value="${value.id}" style="width: 20px; height: 20px;" ${isChecked ? 'checked' : ''}>
                                        &nbsp;
                                        <label class="form-check-label" for="defaultCheck${value.id}" style="padding-top: 6px;">
                                                ${value.name} - ${value.value} %
                                        </label>
                                </div>
                                `;
                            });
                            $('.taxes').html(chkbox);
                        } else {
                            $('.taxes').html(
                                '<code>No Applicable Taxes Found</code>');
                        }
                    },
                    error: function(data) {
                        $('.taxes').html('<code>No Applicable Taxes Found</code>');
                    }
                })
            })
      
            // ----------------------add product session and display--------------------------
            $(document).on('click', '.add_product', function(e) {
                e.preventDefault();
                var inv_entity_id = $('#invoice_entity').val();
                var org_id = $('#organization').val();
                var data = $(this).closest('form').serialize();
                var product_id = $('select[name="product"]').val();
                var quotSessId = $('#sessionId').val();
                if (quotSessId == "") {
                    url = "{{ route('order.add-product') }}";
                } else {
                    url = "{{ route('order.update-product', ':sessionId') }}".replace(
                        'sessionId',
                        quotSessId);
                }
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response) {
                        // console.log(response);
                        if (response) {
                            var data = response.split('<--||-->');
                            var products_table = data[0];
                            var charges_table = data[1];
                            if (!quotSessId) {
                                toastr.success('Added Successfully!');
                            } else {
                                toastr.success('Updated Successfully!');
                            }
                            $('.add_product_form')[0].reset();
                            $('.select2pop').val('').trigger('change');
                            $('#add_product_modal').modal('hide');
                            $('.disp_added_products').html(products_table);
                            // $('.disp_charges').html(charges_table);
                            $('#invoice_entity').val(inv_entity_id).trigger(
                                'change');
                            $('#organization').val(org_id).trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        })
                        // toastr.error(error);
                    }
                });
            });
            $(document).on('click', '.edit-product', function(e) {
                e.preventDefault();
                // Get the product ID from the clicked button
                var inv_entity_id = $('#invoice_entity').val();
                var org_id = $('#organization').val();
                $('#invoice_entity').val(inv_entity_id).trigger('change');
                $('#organization').val(org_id).trigger('change');
                var sessionId = $(this).data('id');
                // Fetch session values from the server using AJAX
                if (sessionId == '') {
                    $('.add_product_form')[0].reset();
                    $('#sessionId').val('');
                    $('.select2pop').val('').trigger('change');
                    $('.button_to_submit').html(
                        '<button type="button" class="btn btn-primary add_product">Add</button>'
                    )
                    $('#add_product_modal').modal('show');
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('order.get-products', ':sessionId') }}"
                            .replace(
                                ':sessionId', sessionId),
                        success: function(data) {
                            if (data) {
                                $('#sessionId').val(sessionId);
                                $('#product').val(data.productData.product).trigger(
                                    'change', {
                                        slcted: data.productData.taxes
                                    });
                                $('#description').val(data.productData.description);
                                $('#uom').val(data.productData.uom).trigger(
                                    'change');
                                $('#quantity').val(data.productData.quantity);
                                $('#make').val(data.productData.make).trigger(
                                    'change');
                                $('#price').val(data.productData.price);
                              
                                $('.button_to_submit').html(
                                    '<button type="button" class="btn btn-primary add_product">Edit</button>'
                                )
                                $('#add_product_modal').modal('show');
                            } else {
                                console.error('Product data not found.');
                            }
                        },
                        error: function(error) {
                            console.error('Error fetching product details:', error);
                        }
                    });
                }
            });
        });
        $('.make1-detail-tax').on('click', function() {
            $('#detail_tax_modal').modal('show');
        });
    </script>
@endpush
