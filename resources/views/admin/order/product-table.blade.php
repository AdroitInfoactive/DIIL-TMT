<?php
namespace App\Models;
?>
<div class="section-title">Products</div>
<div class="table-responsive">
    <table class="table table-sm table-striped border">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">UOM</th>
                <th scope="col" style="text-align: right;">Qty</th>
                <th scope="col">Make 1</th>
                <th scope="col" style="text-align: right;">Price</th>
                <th scope="col" style="text-align: right;">Total</th>
                <th scope="col" style="text-align: right;">Tax</th>
                <th scope="col" style="text-align: right;">Total</th>
                @php
                    $foundFlag = false;
                @endphp
                @foreach ($quotationProducts as $quotationProduct)
                    @php
                        $product = $quotationProduct['productData'];
                        if (isset($product['disp_make2']) && $product['disp_make2'] == 1) {
                            $foundFlag = true;
                        }
                    @endphp
                @endforeach
                @if ($foundFlag == true)
                    <th scope="col" style="border-left: 1px solid #000;">Make 2</th>
                    <th scope="col" style="text-align: right;">Qty 2</th>
                    <th scope="col" style="text-align: right;">Price 2</th>
                    <th scope="col" style="text-align: right;">Total 2</th>
                    <th scope="col" style="text-align: right;">Tax 2</th>
                    <th scope="col" style="text-align: right;">Total 2</th>
                @endif
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($quotationProducts) && count($quotationProducts) > 0)
                @foreach ($quotationProducts as $quotationProduct)
                    @php
                        $product = $quotationProduct['productData'];
                    @endphp
                    <tr>
                        <th scope="row">{{ ++$loop->index }}</th>
                        <td>
                            <input type="hidden" name="session_prod_uid"
                                value="{{ $quotationProduct['quoteProdId'] }}">
                            @php
                                $product_name = Product::find($product['product']);
                                $make_name = Vendor::find($product['make']);
                                $make2_name = Vendor::find($product['make2']);
                                $size_name = Size::find($product['uom']);
                            @endphp
                            {{ @$product_name->name }}
                            <br>
                            Description: {{ @$quotationProduct['productData']['description'] }}
                        </td>
                        <td>{{ @$size_name->name }}</td>
                        <td align="right">{{ $product['quantity'] }}</td>
                        {{-- ---------------------------- make 1 --------------------------------------------------- --}}
                        <td>{{ @$make_name->name }}</td>
                        <td align="right"> {{ currencyPosition($product['price']) }}</td>
                        <td align="right">
                            {{ currencyPosition($quotationProduct['productMake1Total']) }}
                        </td>
                        <td align="right">
                            @if (!empty($quotationProduct['productMake1Taxes']))
                                @foreach ($quotationProduct['productMake1Taxes'] as $tax)
                                    {{ $tax['name'] }} ({{ $tax['value'] }} %)<br>
                                    {{ currencyPosition($tax['tax_amount']) }}<br>
                                @endforeach
                            @else
                                NA
                            @endif
                        </td>
                        <td align="right">
                            {{ currencyPosition(@$quotationProduct['productMake1TotalAmount']) }}
                        </td>
                        {{-- ----------------------------- make 2 ------------------------------------------------ --}}
                        @if ($foundFlag == true)
                            @if (isset($product['disp_make2']) && $product['disp_make2'] == 1)
                                <td style="border-left: 1px solid #000;">{{ @$make2_name->name }}</td>
                                <td align="right">{{ $product['quantity2'] }}</td>
                                <td align="right">
                                    {{ currencyPosition(@$product['price2']) }}
                                </td>
                                <td align="right">
                                    {{ currencyPosition(@$quotationProduct['productMake2Total']) }}
                                </td>
                                <td align="right">
                                    @if (isset($quotationProduct['productMake2Taxes']) &&
                                            is_array($quotationProduct['productMake2Taxes']) &&
                                            !empty($quotationProduct['productMake2Taxes']))
                                        @foreach ($quotationProduct['productMake2Taxes'] as $tax2)
                                            {{ $tax2['name'] }} ({{ $tax2['value'] }} %)
                                            <br>
                                            {{ currencyPosition($tax2['tax_amount']) }}<br>
                                        @endforeach
                                    @else
                                        NA
                                    @endif
                                </td>
                                <td align="right">
                                    {{ currencyPosition(@$quotationProduct['productMake2TotalAmount']) }}
                                </td>
                            @else
                                <td style="border-left: 1px solid #000;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                        @endif
                        <td>
                            <a href="#" class='btn btn-primary edit-product'
                                data-id="{{ $quotationProduct['quoteProdId'] }}"><i class='fas fa-edit'></i>
                            </a>
                            <a href="#" class="btn btn-danger delete-product ml-2"
                                data-id="{{ $quotationProduct['quoteProdId'] }}"><i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @if (is_array($totalcalculations))
                    <tr style="border-top: 1px solid #000;">
                        <td align="center" colspan="3"> <b>Total</b> </td>
                        <td colspan="" align="right"><b>{{ @$totalcalculations['total_make1_Quantity'] }}</b></td>
                        <td colspan="2" align="right">
                            <b>{{ currencyPosition(@$totalcalculations['make1price']) }}</b></td>
                        <td align="right">
                            <b>{{ currencyPosition(@$totalcalculations['make1totalAmount']) }}</b></td>
                        @if (@$totalcalculations['make1Tax'] > 0)
                            <td align="right"> <b>{{ currencyPosition(@$totalcalculations['make1Tax']) }}</b>
                            </td>
                        @else
                            <td align="right" colspan=""> NA</td>
                        @endif
                        <td align="right" colspan="1">
                            <b>{{ currencyPosition(@$totalcalculations['make1priceWithTax']) }}</b></td>
                        @if ($foundFlag == true)
                            @if ($totalcalculations['make2totalAmount'] > 0)
                                <td colspan="2" align="right" style="border-left: 1px solid #000;">
                                    <b>{{ @$totalcalculations['total_make2_Quantity'] }}</b></td>
                                <td align="right" colspan="">
                                    <b>{{ currencyPosition(@$totalcalculations['make2price']) }}</b></td>
                                <td align="right">
                                    <b>{{ currencyPosition(@$totalcalculations['make2totalAmount']) }}</b></td>
                                @if (@$totalcalculations['make2Tax'] > 0)
                                    <td align="right"> <b>{{ currencyPosition(@$totalcalculations['make2Tax']) }}</b>
                                    </td>
                                @else
                                    <td align="right" colspan=""> NA</td>
                                @endif
                                <td align="right">
                                    <b>{{ currencyPosition(@$totalcalculations['make2priceWithTax']) }}</b></td>
                            @endif
                        @endif
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="11" align="center">No Products Found</td>
                </tr>
            @endif
            <div class="form-group disp_charges">
                @if (!empty($quotationCharges))
                    @include('admin.order.charges-table')
                @endif
            </div>
        </tbody>
    </table>
</div>
