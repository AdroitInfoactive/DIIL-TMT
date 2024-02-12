@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Product</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}">
                    </div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control" value="{{ $product->code }}">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" id="" cols="30" rows="10">{!! @$product->description !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Tax</label>
                        <div class="form-check">

                            <input class="form-check-input" name="charge_tax" type="checkbox" id="defaultCheck1"
                                value="{{ $product->charge_tax }}" {{ $product->charge_tax == 1 ? 'checked' : '' }}>

                            <label class="form-check-label" for="defaultCheck1">
                                Charge tax on this product
                            </label>
                        </div>
                    </div>
                    @php
                    if (@$product->charge_tax == 1)
                        $display = 'block';
                    else
                        $display = 'none';
                    @endphp
                    <div class="form-group" id="taxOptions" style="display:{{ $display }}">
                        <label>Tax</label>
                        <select name="tax_id" class="form-control" id="tax_id">
                            <option value="">Select Tax Category</option>
                            @foreach (@$taxes as $tax)
                                <option @selected($product->tax_id == $tax->id) value="{{ @$tax->id }}">{{ @$tax->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>





                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control" id="">
                            <option @selected($product->status === 1) value="1">Active</option>
                            <option @selected($product->status === 0) value="0">Inactive</option>
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
            $('#defaultCheck1').on('click', function() {
                if ($(this).is(':checked')) {
                    $('#taxOptions').show();
                    $('#defaultCheck1').val(1); // Set the value to 1 when checkbox is checked
                } else {
                    $('#taxOptions').hide();
                    $('#tax_id').val("");
                    $('#defaultCheck1').val(0); // Set the value to 0 when checkbox is unchecked
                }
            });
        });
    </script>
@endpush
