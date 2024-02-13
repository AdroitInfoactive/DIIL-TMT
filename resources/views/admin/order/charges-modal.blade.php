<div class="modal fade" id="charges_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add / Edit charges </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="add_charges_form">
                    @csrf
                    <div class="row">
                        @foreach (@$charges as $charge)
                            @php
                                $charge_value = $charge->value;
                            @endphp
                            <div class="col-8">
                                <div class="form-check">
                                    <input type="checkbox" class="charge_select" name="charges_new[]"
                                        id="charge_new_{{ @$charge->id }}" value="{{ @$charge->id }}"
                                        style="width: 20px; height: 20px";
                                        @if (isset($quotationCharges) && count($quotationCharges) > 0) @if (in_array(@$charge['id'], array_column(@$quotationCharges, 'id'))) checked @endif
                                        @endif>
                                    &nbsp;<label class="form-check-label" for="charge_new_{{ @$charge->id }}">
                                        {{ @$charge->name }}-({{ @$charge->description }})
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="hdn_chrg_values[{{ @$charge->id }}]"
                                id="hdn_chrg_values_{{ @$charge->id }}" value="{{ @$charge_value }}">
                            @if (@$charge->editable == 'y')
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check">
                                            @php
                                                $cls = 'd-none';
                                                if (@$charge->editable == 'y' && is_array($quotationCharges) && in_array(@$charge['id'], array_column(@$quotationCharges, 'id'))) {
                                                    $cls = '';
                                                    // print_r($quotationCharges);
                                                    // search entire $quotationCharges with existing id $charge->id if exists take value from the $quotationCharges
                                                    $charge_new = array_search($charge->id, array_column($quotationCharges, 'id'));
                                                    $charge_value = $quotationCharges[$charge_new]['value'];
                                                }
                                            @endphp
                                            <input type="text" name="charge_values[{{ @$charge->id }}]"
                                                id="charge_values_{{ @$charge->id }}" value="{{ @$charge_value }}"
                                                class="{{ $cls }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="row mt-3 justify-content-end">
                        <div class="mr-2">
                            <a href="#" class='btn btn-primary charges_save' data-id="">Save</a>
                        </div>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
