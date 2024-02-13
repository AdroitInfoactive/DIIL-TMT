<div class="modal fade" id="detail_tax_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Tax calculation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @php
                    $totalTaxValue = 0;
                    $cls = 'col-12';
                @endphp
               
                <div class="row">
                    <div class="<?php echo $cls; ?>">
                        <table class="table table-sm table-striped border">
                            <thead>
                                <tr>
                                    <th colspan="2" style="color: red;text-align: center">Make 1</th>
                                </tr>
                                <tr>
                                    <th>Tax Name</th>
                                    <th>Tax Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (@$make1totalTaxes != null)
                                    @foreach (@$make1totalTaxes as $taxName => $taxValue)
                                        <tr>
                                            <td>{{ @$taxName }}%</td>
                                            <td>{{ currencyPosition(@$taxValue) }}</td>
                                        </tr>
                                        @php
                                            $totalTaxValue += @$taxValue;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td><strong>Total Tax Value:</strong></td>
                                        <td><strong>{{ currencyPosition($totalTaxValue) }}</strong></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                  
                </div>
                <div class="row mt-3 justify-content-end">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
