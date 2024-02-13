<html>

<head>
</head>

<body>
    <title>Order Automation System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <!--<link href="style_admin.css" rel="stylesheet" type="text/css"> -->
    <style type="text/css">
        body,
        td,
        th {
            font-family: Arial, Verdana, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            background-color: #FFFFFF;
            line-height: 17px;
        }

        .style1 {
            font-size: 12px;
            font-weight: bold;
        }
    </style>
    <script language="javascript">
        function prntpage() {
            document.getElementById('prnbtn').style.display = "none";
            window.print();
            window.close();
        }
    </script>
    <div>
        <table width="800" align="center" border="0" cellpadding="5" cellspacing="1">
            <tr>
                <td><img src="{{ asset(config('settings.logo')) }}" alt="{{ config('settings.site_name') }}"
                        height="130" border="0" /></td>
                <td align="right"><span class="style1">{{ $qmaster->invoiceEntity?->name }}</span>.<br>
                    {{ $qmaster->invoiceEntity?->address }}<br>
                    {{ $qmaster->invoiceEntity?->area }}, {{ $qmaster->invoiceEntity?->city }}<br>
                    {{ $qmaster->invoiceEntity?->pincode }}, {{ $qmaster->invoiceEntity?->state }}<br>
                    Phone: {{ $qmaster->invoiceEntity?->primary_mobile }}<br>
                    Email: {{ $qmaster->invoiceEntity?->primary_email }}<br>
                    GSTIN: {{ $qmaster->invoiceEntity?->gst_no }}<br>
                </td>
            </tr>
            <tr>
                <td colspan='2' height='5'></td>
            </tr>
        </table><br>
        <table width="800" align="center" border="0" cellpadding="5" cellspacing="1">
            <tr>
                <td colspan="4" align="right" valign="top" bgcolor='#EEEEEE'>Order No :
                    {{ generateQuoteNumber($qmaster->quotation_main_prefix, $qmaster->quotation_entity_prefix, $qmaster->quotation_financial_year, $qmaster->quotation_no, $qmaster->quotation_type) }}<br>
                    Prepared by: {{ $qmaster->user?->name }}<br>
                    Date : {{ date('d-m-Y', strtotime($qmaster->created_at)) }}<br>
                </td>
            </tr>
            <tr>
                <td colspan="6" valign="top" bgcolor='#EEEEEE'>
                    <p>
                        To,
                        <br>
                        {{ $qmaster->client?->name }}<br>
                        {{ $qmaster->client?->address }}, {{ $qmaster->client?->area }},
                        {{ $qmaster->client?->city }}<br>
                        {{ $qmaster->client?->pincode }}, {{ $qmaster->client?->state }}<br>
                        Phone: {{ $qmaster->client?->primary_mobile }}
                        @if ($qmaster->client?->secondary_mobile)
                            , {{ $qmaster->client?->secondary_mobile }}
                        @endif
                        <br>
                        Email: {{ $qmaster->client?->email }}
                        <br>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="10" align="center"><strong>
                        <font size="3">Order</font>
                    </strong></td>
            </tr>
            <tr>
                <td colspan="6">
                    Dear
                    Sir/Madam, <br><br>
                    We acknowledge your valued enquiry and in reply we are glad to quote our lowest rates as under:</p>
                </td>
            </tr>
        </table>
        <table width="800" border="1" align="center" cellpadding="3" cellspacing="0" bgcolor="#cbcbcb">
            <tr valign="top">
                <td align="center"><b>S.No.</b></td>
                <td width="25%"><b>Product</b></td>
                <td width="20%"><b>Description</b></td>
                <td width="6%" align="center"><b>UOM</b></td>
                <td width="8%" align="center"><b>Make</b></td>
                <td width="7%" align="right" class="lightbg1"><b>Qty</b></td>
                <td width="13%" align="right"><strong>Price</strong></td>
                <td width="17%" align="right"><strong>Total</strong></td>
                <td width="17%" align="right"><strong>Tax</strong></td>
                <td width="17%" align="right"><strong>Total + Tax</strong></td>
                @php
                    $found_flag = false;
                    foreach ($qdetails as $qdetail) {
                        if ($qdetail->multi_make == 1) {
                            $found_flag = true;
                        }
                    }
                @endphp
                @if ($found_flag == true)
                    <td width="8%" align="center"><b>Make 2</b></td>
                    <td width="7%" align="right"><b>Qty 2</b></td>
                    <td width="13%" align="right"><strong>Price 2</strong></td>
                    <td width="17%" align="right"><strong>Total 2</strong></td>
                    <td width="17%" align="right"><strong>Tax 2</strong></td>
                    <td width="17%" align="right"><strong>Total + Tax (2)</strong></td>
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
                    <td>{{ $qdetail->description }}</td>
                    <td>{{ $qdetail->uom?->name }}</td>
                    <td>{{ $qdetail->make?->name }}</td>
                    <td align="right">{{ $qdetail->quantity }}</td>
                    <td align="right">{{ currencyPosition($qdetail->price) }}</td>
                    <td align="right">{{ currencyPosition($qdetail->priceXqty) }}</td>
                    <td align="right">
                        @if ($qtaxes->isNotEmpty())
                            @foreach ($qtaxes as $qtax)
                                @if ($qdetail->id == $qtax->quotation_tax_detail_id)
                                    @php
                                        $mk1_tot_tax += $qtax->quotation_tax_amount;
                                    @endphp
                                    {{ $qtax->quotation_tax_name }}({{ $qtax->quotation_tax_value }}%)
                                    - {{ currencyPosition($qtax->quotation_tax_amount) }}<br>
                                @endif
                            @endforeach
                        @else
                            {{ currencyPosition(0) }}
                        @endif
                    </td>
                    <!-- Display total price for Make1 -->
                    <td align="right">{{ currencyPosition($qdetail->total_price) }}</td>
                    <!-- Additional columns for Make2 -->
                    @if ($found_flag == true)
                        <td>
                            @isset($qdetail->price2)
                                {{ $qdetail->make?->name }}
                            @endisset
                        </td>
                        <td align="right">
                            {{ $qdetail->quantity2 }}</td>
                        <td align="right">
                            @isset($qdetail->price2)
                                {{ currencyPosition($qdetail->price2) }}
                            @endisset
                        </td>
                        <td align="right">
                            @isset($qdetail->priceXqty2)
                                {{ currencyPosition($qdetail->priceXqty2) }}
                            @endisset
                        </td>
                        <td align="right">
                            @foreach ($qtaxes as $qtax)
                                @if ($qdetail->id == $qtax->quotation_tax_detail_id && isset($qtax->quotation_tax_make2_amount))
                                    @php
                                        // Accumulate tax amount for make2 for the current product
                                        $product_mk2_tot_tax += $qtax->quotation_tax_make2_amount;
                                        // Accumulate tax amount for make2 globally
                                        $mk2_tot_tax += $qtax->quotation_tax_make2_amount;
                                    @endphp
                                    {{ $qtax->quotation_tax_name }}({{ $qtax->quotation_tax_value }}%)
                                    - {{ currencyPosition($qtax->quotation_tax_make2_amount) }}<br>
                                @endif
                            @endforeach
                        </td>
                        <td align="right">
                            @isset($qdetail->total_price2)
                                {{ currencyPosition($qdetail->total_price2) }}
                            @endisset
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="5"> <b>Total</b></td>
                <td align="right"><b>{{ $mk1_qty }}</b></td>
                <td align="right"><b>{{ currencyPosition($mk1_prc) }}</b></td>
                <td align="right"><b>{{ currencyPosition($mk1_prcXqty) }}</b></td>
                <td align="right"><b>{{ currencyPosition($mk1_tot_tax) }}</b></td>
                <td align="right"><b>{{ currencyPosition($mk1_total) }}</b></td>
                @if ($found_flag == true)
                    <td colspan="2" align="right">
                        <b>{{ $mk2_qty }}</b>
                    </td>
                    <td align="right"><b>{{ currencyPosition($mk2_prc) }}</b></td>
                    <td align="right"><b>{{ currencyPosition($mk2_prcXqty) }}</b></td>
                    <td align="right"><b>{{ currencyPosition($mk2_tot_tax) }}</b></td>
                    <td align="right"><b>{{ currencyPosition($mk2_total) }}</b></td>
                @endif
            </tr>
            @if ($qcharges->isNotEmpty())
                <tr>
                    <td colspan='16'><strong>Charges:</strong></td>
                </tr>
                <tr>
                    <td><b>S.No</b></th>
                    <td colspan="7"><b>Name</b></th>
                    <td style="text-align: right;"><b>Value</b></th>
                    <td style="text-align: right;"><b>Total</b></th>
                        @if ($found_flag == true)
                    <td colspan="7" style="text-align: right;">
                        <b>Total2</b></th>
            @endif
            </tr>
            @php
                $mk1_chrg_tot = 0;
                $mk2_chrg_tot = 0;
            @endphp
            @foreach ($qcharges as $qcharge)
                @php
                    $mk1_chrg_tot += $qcharge->quotation_charge_amount;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td colspan="7">{{ $qcharge->charge?->name }} -
                        {{ $qcharge->charge?->description }}</td>
                    <td align="right">{{ $qcharge->quotation_charge_value }}</td>
                    <td align="right">
                        {{ currencyPosition($qcharge->quotation_charge_amount) }}</td>
                    @if ($found_flag == true)
                        @php
                            $mk2_chrg_tot += $qcharge->quotation_charge_make2_amount;
                        @endphp
                        <td align="right" colspan="7" style="border-left: 1px solid #000;">
                            {{ currencyPosition($qcharge->quotation_charge_make2_amount) }}
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="9"> <b>Total</b></td>
                <td align="right"><b>{{ currencyPosition($mk1_chrg_tot) }}</b></td>
                @if ($found_flag == true)
                    <td align="right" colspan="7" style="border-left: 1px solid #000;">
                        <b>{{ currencyPosition($mk2_chrg_tot) }}</b>
                    </td>
                @endif
            </tr>
            @endif
            <tr>
                <td colspan="9"><b>Grand Total</b></td>
                <td align="right">
                    <strong>{{ currencyPosition($qmaster->quotation_total_amount_withcharges) }}</strong>
                </td>
                @if ($found_flag == true)
                    <td colspan="7" align="right">
                        <strong>{{ currencyPosition($qmaster->quotation_total_amount_withcharges2) }}</strong>
                    </td>
                @endif
            </tr>
            <tr>
                <td colspan="16"><strong>We are awaiting for your valuable order.</strong></td>
            </tr>
        </table><br>
        @if ($qterms->isNotEmpty())
            <div class="table-responsive">
                <table width="800" align="center" border="0" cellpadding="3" cellspacing="1">
                    <tr>
                        <td colspan="20"><strong>Terms & Conditions:</strong></td>
                    </tr>
                    @foreach ($qterms as $qterm)
                        <tr>
                            <td style="width: 10px;">{{ $loop->iteration }}.</td>
                            <td colspan="6">{{ $qterm->quotation_term_name }} -
                                {{ $qterm->quotation_term_description }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        <table width="800" align="center" border="0" cellpadding="3" cellspacing="1">
            @if ($qmaster->quotation_note != '')
                <tr>
                    <td colspan="2"><strong>NOTE : </strong></td>
                </tr>
                <tr>
                    <td colspan="2">{{ $qmaster->quotation_note }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="2"><br>
                    In case of any queries and for further discussion, please contact
                    <strong>{{ $qmaster->invoiceEntity?->primary_email }}</strong> at office on
                    <strong>{{ $qmaster->invoiceEntity?->primary_mobile }}</strong><br>
                    <br>
                    Please feel free to contact us for any clarification(s).<br>
                    <br>
                    Regards,
                    <br>
                    <span class="style1">{{ $qmaster->invoiceEntity?->name }}</span><br>
                    <span class="style1">This is a computer generated document, hence signature not
                        required.</span><br>
                    <span class="style1"></span><br>
                    <br>
                </td>
            </tr>
            @if ($qmaster->invoiceEntity?->account_number != '')
                <tr>
                    <td>
                        <table cellpadding="1" cellspacing="1" width="100%">
                            <tr>
                                <td colspan="3">
                                    <strong>BANK DETAILS:</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    NAME OF THE COMPANY </td>
                                <td width="1%">
                                    <b>:</b>
                                </td>
                                <td width="79%">
                                    {{ strtoupper($qmaster->invoiceEntity?->account_name) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    NAME OF THE BANK
                                </td>
                                <td>
                                    <b>:</b>
                                </td>
                                <td>
                                    {{ strtoupper($qmaster->invoiceEntity?->bank_name) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    NAME OF THE BRANCH
                                </td>
                                <td>
                                    <b>:</b>
                                </td>
                                <td>
                                    {{ strtoupper($qmaster->invoiceEntity?->branch) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ACCOUNT NO
                                </td>
                                <td>
                                    <b>:</b>
                                </td>
                                <td>
                                    {{ $qmaster->invoiceEntity?->account_number }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    IFSC CODE
                                </td>
                                <td>
                                    <b>:</b>
                                </td>
                                <td>
                                    {{ strtoupper($qmaster->invoiceEntity?->ifsc_code) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
        </table><br>
        <table width="800" align="center" border="0" cellpadding="3" cellspacing="1"
            style="border-top:#999 1px solid;">
            <tr>
                <td colspan='2' bgcolor="#000000" height='1'></td>
            </tr>
            <tr>
                <td>
                    Visit Us: {{ url('') }}
                </td>
                <td align="right">
                    Any Dispute will be subject to the exclusive {{ $qmaster->invoiceEntity?->area }} jurisdiction.
                </td>
            </tr>
        </table>
    </div>
    <div id="prnbtn" align="center" style="min-height:50px;"><br>
        <input type="button" name="btnprnt" id="btnprnt" onClick="javascript:prntpage()" value="Print / Save">
        <input type="button" name="btnclose" onClick="history.back()" value="Close">
    </div>
</body>

</html>
