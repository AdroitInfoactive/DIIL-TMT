<div class="table-responsive">
    <tr>
        <td colspan="20">
            <div class="section-title">Charges</div>
        </td>
    </tr>
    <tr>
        <th scope="col">#</th>
        <th scope="col" colspan="6"> Name</th>
        <th scope="col" style="text-align: right;">Value</th>
        <th scope="col" style="text-align: right;">Total</th>
        @php
            $quotationCharges = Session::get('chargesSession_' . auth()->user()->id);
        @endphp
    </tr>
    @php
        $chrge_make1_ttl = 0;
    @endphp
    @foreach (@$quotationCharges as $charge)
        <tr>
            <td>{{ ++$loop->index }}</td>
            <td colspan="6">{{ @$charge['name'] }} -({{ @$charge['description'] }})</td>
            <td align="right">
                @if (@$charge['calculation_type'] == 'v')
                    {{ currencyPosition(@$charge['value']) }}
                    @else{{ @$charge['value'] }} %
                @endif
            </td>
            @if (isset($charge['make1Calculations']))
                <td align="right">{{ currencyPosition(@$charge['make1Calculations']) }}</td>
                @php
                    $chrge_make1_ttl += @$charge['make1Calculations'];
                @endphp
            @endif
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="20">
            <div class="section-title">Total</div>
        </td>
    </tr>
    @php
        $totAmounts = Session:: get('totalProductSession_' . auth()->user()->id);
        $mk1ttl = @$totAmounts['make1priceWithTax'] + $chrge_make1_ttl;
    @endphp
    <tr>
        <td colspan="9" align="right">
            <input type="hidden" name="make1ttl" id="make1ttl" value="{{ $mk1ttl }}">
            <strong>{{ currencyPosition($mk1ttl) }}</strong>
        </td>
    </tr>
</div>
