@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Products</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Product</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"> {!! old('description') !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Tax</label>
                        <div class="form-check">
                            <input class="form-check-input" name="charge_tax" type="checkbox" id="defaultCheck1"
                                value="0" style="width: 20px; height: 20px;">
                            &nbsp;
                            <label class="form-check-label" for="defaultCheck1" style="padding-top: 6px;">
                                <code>Charge tax on this product</code>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="taxOptions" style="display:none">
                        <label>Tax</label>
                        <select name="tax_id" class="form-control" id="tax_id">
                            <option value="">Select Tax Category</option>
                            @foreach (@$taxes as $tax)
                                <option value="{{ @$tax->id }}">{{ @$tax->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control" id="">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
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
                    $('#defaultCheck1').val(0); // Set the value to 0 when checkbox is unchecked
                }
            });
        });
    </script>
@endpush
