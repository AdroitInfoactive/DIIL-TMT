@extends('admin.layouts.master')
@section('content')

    <section class="section">
        <div class="section-header">
            <h1>View Order</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">View Order</div>
            </div>
        </div>
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Order</h2>
                                <div class="invoice-number mt-2">ORDER NO :
                                    {{ generateQuoteNumber($qmaster->order_main_prefix, $qmaster->order_entity_prefix, $qmaster->order_financial_year, $qmaster->order_no, $qmaster->order_type) }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        Order Date:
                                        <strong>{{ $qmaster->order_date }}</strong>
                                    </address>
                                    <address>
                                        Order Status:
                                        <strong>
                                            @if ($qmaster->order_status == 'p')
                                                Pending
                                            @elseif($qmaster->order_status == 'a')
                                                Accepted
                                            @elseif($qmaster->order_status == 'r')
                                                Rejected
                                            @else
                                                NA
                                            @endif
                                        </strong>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Client Details:</strong><br>
                                        Name: <b>{{ $qmaster->client?->name }}</b><br>
                                        Email: <b>{{ $qmaster->client?->email }}</b><br>
                                        Contact person: <b>{{ $qmaster->client?->primary_name }}</b><br>
                                        Phone: <b>{{ $qmaster->client?->primary_mobile }}</b><br>
                                        GST No: <b>{{ $qmaster->client?->gst_no }}</b>
                                    </address>
                                </div>
                                <div class="col-md-6">
                                    <address>
                                        <br>
                                        Address: <b>{{ $qmaster->client?->address }}</b><br>
                                        Area: <b>{{ $qmaster->client?->area }}</b><br>
                                        City: <b>{{ $qmaster->client?->city }}</b><br>
                                        State: <b>{{ $qmaster->client?->state }}</b><br>
                                        Country & Pincode: <b>{{ $qmaster->client?->country }},
                                            {{ $qmaster->client?->pincode }}</b>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-md">
                                    <tr>
                                        <div class="section-title">Products</div>
                                    </tr>
                                    <tr
                                        style="border-top: 1px solid #000; border-right: 0; border-bottom: 1px solid #000; border-left: 1px solid #000; background-color: #eee">
                                        <th data-width="40">#</th>
                                        <th>Product</th>
                                        <th>UOM</th>
                                        <th class="text-right">Qty</th>
                                        <th>Make</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Tax</th>
                                        <th class="text-right">Total+Tax</th>
                                        @php
                                            $found_flag = false;
                                            foreach ($qdetails as $qdetail) {
                                                if ($qdetail->multi_make == 1) {
                                                    $found_flag = true;
                                                }
                                            }
                                        @endphp
                                        @if ($found_flag == true)
                                            <th class="text-right" style="border-left: 1px solid #000;">Qty2</th>
                                            <th>Make2</th>
                                            <th class="text-center">Price2</th>
                                            <th class="text-right">Total2</th>
                                            <th class="text-right">Tax2</th>
                                            <th class="text-right">Total+Tax(2)</th>
                                        @endif
                                    </tr>
                                    @php
                                        $mk1_tot_tax = 0;
                                        $mk2_tot_tax = 0;

                                        $mk1_qty = 0;
                                        $mk2_qty = 0;
                                        $mk1_prc = 0;
                                        $mk2_prc = 0;
                                        $mk1_prcXqty = 0;
                                        $mk2_prcXqty = 0;
                                        $mk1_total = 0;
                                        $mk2_total = 0;
                                    @endphp
                                    @foreach ($qdetails as $qdetail)
                                        @php
                                            // Initialize total tax for each product
                                            $product_mk2_tot_tax = 0;

                                            $mk1_qty += $qdetail->quantity;
                                            $mk2_qty += $qdetail->quantity2;
                                            $mk1_prc += $qdetail->price;
                                            $mk2_prc += $qdetail->price2;
                                            $mk1_prcXqty += $qdetail->priceXqty;
                                            $mk2_prcXqty += $qdetail->priceXqty2;
                                            $mk1_total += $qdetail->total_price;
                                            $mk2_total += $qdetail->total_price2;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $qdetail->product?->name }}</td>
                                            <td>{{ $qdetail->uom?->name }}</td>
                                            <td class="text-right">{{ $qdetail->quantity }}</td>
                                            <td>{{ $qdetail->make?->name }}</td>
                                            <td class="text-right">{{ currencyPosition($qdetail->price) }}</td>
                                            <td class="text-right">{{ currencyPosition($qdetail->priceXqty) }}</td>
                                            <td class="text-right">
                                                @if ($qtaxes->isNotEmpty())
                                                    @foreach ($qtaxes as $qtax)
                                                        @if ($qdetail->id == $qtax->order_tax_detail_id)
                                                            @php
                                                                $mk1_tot_tax += $qtax->order_tax_amount;
                                                            @endphp
                                                            {{ $qtax->order_tax_name }}({{ $qtax->order_tax_value }}%)
                                                            - {{ currencyPosition($qtax->order_tax_amount) }}<br>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ currencyPosition(0) }}
                                                @endif
                                            </td>
                                            <!-- Display total price for Make1 -->
                                            <td class="text-right">{{ currencyPosition($qdetail->total_price) }}</td>
                                            <!-- Additional columns for Make2 -->
                                            @if ($found_flag == true)
                                                <td class="text-right" style="border-left: 1px solid #000;">
                                                    {{ $qdetail->quantity2 }}</td>
                                                <td>
                                                    @isset($qdetail->price2)
                                                        {{ $qdetail->make?->name }}
                                                    @endisset
                                                </td>
                                                <td class="text-right">
                                                    @isset($qdetail->price2)
                                                        {{ currencyPosition($qdetail->price2) }}
                                                    @endisset
                                                </td>
                                                <td class="text-right">
                                                    @isset($qdetail->priceXqty2)
                                                        {{ currencyPosition($qdetail->priceXqty2) }}
                                                    @endisset
                                                </td>
                                                <td class="text-right">
                                                    @foreach ($qtaxes as $qtax)
                                                        @if ($qdetail->id == $qtax->order_tax_detail_id && isset($qtax->order_tax_make2_amount))
                                                            @php
                                                                // Accumulate tax amount for make2 for the current product
                                                                $product_mk2_tot_tax += $qtax->order_tax_make2_amount;
                                                                // Accumulate tax amount for make2 globally
                                                                $mk2_tot_tax += $qtax->order_tax_make2_amount;
                                                            @endphp
                                                            {{ $qtax->order_tax_name }}({{ $qtax->order_tax_value }}%)
                                                            - {{ currencyPosition($qtax->order_tax_make2_amount) }}<br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="text-right">
                                                    @isset($qdetail->total_price2)
                                                        {{ currencyPosition($qdetail->total_price2) }}
                                                    @endisset
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <!-- Total row -->
                                    <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                                        <td align="center" colspan="3"> <b>Total</b></td>
                                        <td align="right"><b>{{ $mk1_qty }}</b></td>
                                        <td colspan="2" align="right"><b>{{ currencyPosition($mk1_prc) }}</b></td>
                                        <td align="right"><b>{{ currencyPosition($mk1_prcXqty) }}</b></td>
                                        <td align="right"><b>{{ currencyPosition($mk1_tot_tax) }}</b></td>
                                        <td align="right"><b>{{ currencyPosition($mk1_total) }}</b></td>
                                        @if ($found_flag == true)
                                            <td align="right" style="border-left: 1px solid #000;">
                                                <b>{{ $mk2_qty }}</b>
                                            </td>
                                            <td colspan="2" align="right"><b>{{ currencyPosition($mk2_prc) }}</b></td>
                                            <td align="right"><b>{{ currencyPosition($mk2_prcXqty) }}</b></td>
                                            <td align="right"><b>{{ currencyPosition($mk2_tot_tax) }}</b></td>
                                            <td align="right"><b>{{ currencyPosition($mk2_total) }}</b></td>
                                        @endif
                                    </tr>
                                    @if ($qcharges->isNotEmpty())
                                        <div class="table-responsive">
                                            <tr>
                                                <td colspan="20">
                                                    <div class="section-title" style="margin: 5px 0 5px 0;">Charges</div>
                                                </td>
                                            </tr>
                                            <tr
                                                style="border-top: 1px solid #000; border-right: 0; border-bottom: 1px solid #000; border-left: 1px solid #000; background-color: #eee">
                                                <th scope="col">#</th>
                                                <th scope="col" colspan="6">Name</th>
                                                <th scope="col" style="text-align: right;">Value</th>
                                                <th scope="col" style="text-align: right;">Total</th>
                                                @if ($found_flag == true)
                                                    <th scope="col" colspan="7"
                                                        style="text-align: right; border-left: 1px solid #000;">Total2</th>
                                                @endif
                                            </tr>
                                            @php
                                                $mk1_chrg_tot = 0;
                                                $mk2_chrg_tot = 0;
                                            @endphp
                                            @foreach ($qcharges as $qcharge)
                                                @php
                                                    $mk1_chrg_tot += $qcharge->order_charge_amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td colspan="6">{{ $qcharge->charge?->name }} -
                                                        {{ $qcharge->charge?->description }}</td>
                                                    <td class="text-right">{{ $qcharge->order_charge_value }}</td>
                                                    <td class="text-right">
                                                        {{ currencyPosition($qcharge->order_charge_amount) }}</td>
                                                    @if ($found_flag == true)
                                                        @php
                                                            $mk2_chrg_tot += $qcharge->order_charge_make2_amount;
                                                        @endphp
                                                        <td class="text-right" colspan="7"
                                                            style="border-left: 1px solid #000;">
                                                            {{ currencyPosition($qcharge->order_charge_make2_amount) }}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                                                <td align="center" colspan="8"> <b>Total</b></td>
                                                <td align="right"><b>{{ currencyPosition($mk1_chrg_tot) }}</b></td>
                                                @if ($found_flag == true)
                                                    <td align="right" colspan="7" style="border-left: 1px solid #000;">
                                                        <b>{{ currencyPosition($mk2_chrg_tot) }}</b>
                                                    </td>
                                                @endif
                                            </tr>
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <tr>
                                            <td colspan="20">
                                                <div class="section-title" style="margin: 5px 0 5px 0;">Grand Totals</div>
                                            </td>
                                        </tr>
                                        <tr
                                            style="border-top: 1px solid #000; border-right: 0; border-bottom: 1px solid #000; border-left: 1px solid #000;">
                                            <td colspan="9" align="right">
                                                <strong>{{ currencyPosition($qmaster->order_total_amount_withcharges) }}</strong>
                                            </td>
                                            @if ($found_flag == true)
                                                <td colspan="7" style="border-left: 1px solid #000" align="right">
                                                    <strong>{{ currencyPosition($qmaster->order_total_amount_withcharges2) }}</strong>
                                                </td>
                                            @endif
                                        </tr>
                                    </div>
                                </table>
                                @if ($qterms->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-md">
                                            <tr>
                                                <td colspan="20">
                                                    <div class="section-title" style="margin: 5px 0 5px 0;">Terms &
                                                        Conditions</div>
                                                </td>
                                            </tr>
                                            @foreach ($qterms as $qterm)
                                                <tr>
                                                    <td style="width: 10px;">{{ $loop->iteration }}</td>
                                                    <td colspan="6">{{ $qterm->order_term_name }} -
                                                        {{ $qterm->order_term_description }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="section-title" style="margin: 5px 0 5px 0;">Notes</div>
                                    <div class="invoice-note">{!! $qmaster->order_note !!}</div>
                                    <p class="mt-4">Order Prepared by: <b>{{ $qmaster->user?->name }}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right">
                    <div class="float-lg-left mb-lg-0 mb-3">
                        <a href="{{ route('order.index') }}" class="btn btn-warning btn-icon icon-left">Back</a>
                    </div>
                    @if ($qmaster->order_delete_status != 'y')
                        <a href="{{ route('order.edit', $qmaster->id) }}"
                            class="btn btn-primary btn-icon icon-left"><i class="fas fa-edit"></i>Edit</a>
                        {{-- <a href="{{ route('order.revise', $qmaster->id) }}"
                            class="btn btn-info btn-icon icon-left"><i class="fas fa-history"></i>Revise</a> --}}
                    @endif
                    {{-- <a href="{{ route('order.print', $qmaster->id) }}" class="btn btn-success btn-icon icon-left"><i
                            class="fas fa-print"></i>Print</a>
                    <a href="" class="btn btn-danger btn-icon icon-left">PDF</a> --}}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#print_btn').on('click', function() {
                let printContents = $('.invoice-print').html();
                let originalContents = document.body.innerHTML;
                let printWindow = window.open('', '', 'height=800,width=800');

                let htmlContent = `<html>
                    <link rel="stylesheet" href="{{ asset('admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
                    <link rel="stylesheet" href="{{ asset('admin/assets/modules/fontawesome/css/all.min.css') }}">

                    <link rel="stylesheet" href="{{ asset('admin/assets/css/toastr.min.css') }}">
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
                    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap-iconpicker.css') }}">
                    <link rel="stylesheet" href="{{ asset('admin/assets/modules/select2/dist/css/select2.min.css') }}">
                    <link rel="stylesheet" href="{{ asset('admin/assets/modules/summernote/summernote-bs4.css') }}">
                    <link rel="stylesheet"
                        href="{{ asset('admin/assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
                    <link rel="stylesheet"
                        href="{{ asset('admin/assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
                    <link rel="stylesheet" href="{{ asset('admin/assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
                    <!-- Template CSS -->
                    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
                    <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">
                    <body>
                        <section class="section">
                            <div class="section-body">
                                <div class="invoice">
                                    ${printContents}
                                </div>
                            </div>
                        </section>
                    </body>
                    </html>`;
                printWindow.document.open();
                printWindow.document.write(htmlContent);
                printWindow.document.close();

                /* setTimeout(function() {
                    printWindow.print();
                    printWindow.close();
                }, 1000); */

                return false; // Prevents default link action
            });
        });
    </script>
@endpush
