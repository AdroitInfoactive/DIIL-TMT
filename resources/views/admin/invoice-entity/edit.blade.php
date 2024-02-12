@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Invoice Entity</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Invoice Entity</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('invoice-entity.update', $invoiceEntity->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Invoice Entity * </label>
                                <input type="text" name="name" value="{{ $invoiceEntity->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>GST Number</label>
                                <input type="text" name="gst_no" value="{{ $invoiceEntity->gst_no }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Address *</label>
                                <input type="text" name="address" value="{{ $invoiceEntity->address }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Area</label>
                                <input type="text" name="area" value="{{ $invoiceEntity->area }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" name="city" value="{{ $invoiceEntity->city }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>State *</label>
                                <input type="text" name="state" value="{{ $invoiceEntity->state }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Country *</label>
                                <input type="text" name="country" value="{{ $invoiceEntity->country }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Pincode *</label>
                                <input type="text" name="pincode" value="{{ $invoiceEntity->pincode }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Primary Contact</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Person Name *</label>
                                <input type="text" name="primary_name" value="{{ $invoiceEntity->primary_name }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email Id *</label>
                                <input type="text" name="primary_email" value="{{ $invoiceEntity->primary_email }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Mobile *</label>
                                <input type="text" name="primary_mobile" value="{{ $invoiceEntity->primary_mobile }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="primary_designation" value="{{ $invoiceEntity->primary_designation }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Bank Details</h6>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" value="{{ $invoiceEntity->account_name }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Account Number</label>
                                <input type="text" name="account_number" value="{{ $invoiceEntity->account_number }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc_code" value="{{ $invoiceEntity->ifsc_code }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $invoiceEntity->bank_name }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Branch</label>
                                <input type="text" name="branch" value="{{ $invoiceEntity->branch }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Description </label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"> {!! $invoiceEntity->description !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
