<?php

namespace App\Models; // Check the correct namespace

use App\Models\OrderMaster; // Check the correct path
?>
<div class="card card-primary report-table">
    <div class="card-header">
        @php
            if ($from_date == null) {
                $from_date = 'Starting';
            } else {
                $from_date = date('d-m-Y', strtotime($from_date));
            }
        @endphp
        <h4>Ledger of <b>{{ $client->name }}</b> from {{ $from_date }} to {{ date('d-m-Y', strtotime($to_date)) }}
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-md">
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th style="text-align: right;">Order Amount</th>
                    <th style="text-align: right;">Receipt Amount</th>
                    <th style="text-align: right;">Balance</th>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right;"><b>Opening Balance</b></td>
                    @php
                        if (!is_null($openingBalance)) {
                            // Access the 'opening_balance' property only if $openingBalance is not null
                            $opening_balance = $openingBalance->opening_balance;
                        } else {
                            // Handle the case when $openingBalance is null
                            $opening_balance = 0; // or any other default value you want
                        }
                        if ($opening_balance < 0) {
                            $op_clr_cls = 'color:red';
                        } else {
                            $op_clr_cls = 'color:green';
                        }
                    @endphp
                    <td style="text-align: right; {{ $op_clr_cls }};"><b>{{ currencyPosition($opening_balance) }}</b>
                    </td>
                </tr>
                @php
                    $ordered_total = 0;
                    $receipt_total = 0;
                @endphp
                @foreach ($receipts as $receipt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($receipt->received_date)) }}</td>
                        @php
                            $type = '';
                            if ($receipt->ordered_amount != null) {
                                $type = 'Order';
                            } else {
                                $type = 'Receipt';
                            }
                        @endphp
                        <td>{{ $type }}</td>
                        @php
                            if ($type == 'Receipt') {
                                $description = $receipt->receipt_description;
                            } else {
                                $qmaster = OrderMaster::find($receipt->transaction_reference);
                                $description = $receipt->receipt_description . ' - ' . generateQuoteNumber($qmaster->order_main_prefix, $qmaster->order_entity_prefix, $qmaster->order_financial_year, $qmaster->order_no, $qmaster->order_type);
                            }
                        @endphp
                        <td>{{ $description }}</td>
                        <td style="text-align: right; color:green;">{{ currencyPosition($receipt->ordered_amount) }}
                        </td>
                        <td style="text-align: right; color:red;">{{ currencyPosition($receipt->received_amount) }}
                        </td>
                        @php
                            $ordered_total += $receipt->ordered_amount;
                            $receipt_total += $receipt->received_amount;
                            if ($loop->first) {
                                $closing_balance = $opening_balance + $receipt->received_amount - $receipt->ordered_amount;
                            } else {
                                $closing_balance = $closing_balance + $receipt->received_amount - $receipt->ordered_amount;
                            }
                            if ($closing_balance < 0) {
                                $clr_cls = 'color:red';
                            } else {
                                $clr_cls = 'color:green';
                            }
                        @endphp
                        <td style="text-align: right; {{ $clr_cls }};">{{ currencyPosition($closing_balance) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="border-top: 1px solid black;">
                    <td colspan="4" style="text-align: right;"><b>Total</b></td>
                    <td style="text-align: right; color:green">{{ currencyPosition($ordered_total) }}</td>
                    <td style="text-align: right; color:red">{{ currencyPosition($receipt_total) }}</td>
                    <td style="text-align: right; {{ $clr_cls }};">{{ currencyPosition($closing_balance) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
