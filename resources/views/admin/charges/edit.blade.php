@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Charges</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Charge</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('charges.update', $charge->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" value="{{ $charge->name }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"> {!! $charge->description !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Calculation Type *</label>
                        <select name="calculation_type" class="form-control calculation_type">
                            <option value="">Select</option>
                            <option value="v" @selected($charge->calculation_type == 'v')>Value</option>
                            <option value="p" @selected($charge->calculation_type == 'p')>Percentage</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Calculation On *</label>
                        <select name="calculation_on" class="form-control calculation_on">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="form-group referred_tax" style="display: none;">
                        <label>Referred Tax</label>
                        <select name="referred_tax" class="form-control">
                            <option value="">Select</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Editable *</label>
                        <select name="editable" class="form-control">
                            <option value="n" @selected($charge->editable == 'n')>No</option>
                            <option value="y" @selected($charge->editable == 'y')>Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value *</label>
                        <input type="text" name="value" value="{{ $charge->value }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control" id="">
                            <option value="1" @selected($charge->status == 1)>Active</option>
                            <option value="0" @selected($charge->status == 0)>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // ----------------------- set calculation on values onload ---------------------
            var calc_typ = $('.calculation_type').val();
            if (calc_typ == 'v') {
                var options = `
                    <option value="">Select</option>
                    <option value="f" @selected($charge->calculation_on == 'f')>Fixed</option>
                    <option value="w" @selected($charge->calculation_on == 'w')>Net Weight(PMT)</option>
                    `;
            } else {
                var options = `
                    <option value="">Select</option>
                    <option value="n" @selected($charge->calculation_on == 'n')>Net Price</option>
                    <option value="g" @selected($charge->calculation_on == 'g')>Gross Price</option>
                    <option value="t" @selected($charge->calculation_on == 't')>Tax</option>
                    `;
            }
            $('.calculation_on').html(options);
            // ------------------------------------------------------------------------------
            // ---------------------- set tax fiels if tax is selected ----------------------
            var calc_on = $('.calculation_on').val();
            if (calc_on == 't') {
                $('.referred_tax').show();
                var selectedTaxId = "{{ $charge->referred_tax }}";
                $('.referred_tax option').each(function() {
                    if ($(this).val() == selectedTaxId) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });
            } else {
                $('.referred_tax').hide();
            }
            // ------------------------------------------------------------------------------
            // ----------------------- on calculation type change ----------------------------
            $('.calculation_type').on('change', function() {
                if ($(this).val() == 'v') {
                    var options = `
                    <option value="">Select</option>
                    <option value="f">Fixed</option>
                    <option value="w">Net Weight(PMT)</option>
                    `;
                    $('.referred_tax').hide();
                } else {
                    var options = `
                    <option value="">Select</option>
                    <option value="n">Net Price</option>
                    <option value="g">Gross Price</option>
                    <option value="t">Tax</option>
                    `;
                }
                $('.calculation_on').html(options);
            })
            // --------------------- on calculation on change --------------------------------
            $('.calculation_on').on('change', function() {
                if ($(this).val() == 't') {
                    $('.referred_tax').show();
                } else {
                    $('.referred_tax').hide();
                }
            })
        })
    </script>
@endpush
