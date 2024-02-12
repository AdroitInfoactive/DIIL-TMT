@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Invoice Entity</h1>
        </div>
        <div>
            <a href="{{ route('invoice-entity.index') }}" class="btn btn-primary my-3">Go Back</a>
            <a href="{{ route('invoice-entity.edit', $invoiceEntity->id) }}" class='btn btn-primary ml-2 mr-2'>Edit</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Invoice Entity * </label>
                                    <p>{{ $invoiceEntity->name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>GST Number</label>
                                    <p>{{ $invoiceEntity->gst_no }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <p>{{ $invoiceEntity->address }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Area</label>
                                    <p>{{ $invoiceEntity->area }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>City *</label>
                                    <p>{{ $invoiceEntity->city }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>State *</label>
                                    <p>{{ $invoiceEntity->state }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Country *</label>
                                    <p>{{ $invoiceEntity->country }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Pincode *</label>
                                    <p>{{ $invoiceEntity->pincode }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Primary Contact</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Person Name *</label>
                                    <p>{{ $invoiceEntity->primary_name }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Email Id *</label>
                                    <p>{{ $invoiceEntity->primary_email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mobile *</label>
                                    <p>{{ $invoiceEntity->primary_mobile }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <p>{{ $invoiceEntity->primary_designation }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Bank Details</h6>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <p>{{ $invoiceEntity->account_name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <p>{{ $invoiceEntity->account_number }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>IFSC Code</label>
                                    <p>{{ $invoiceEntity->ifsc_code }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <p>{{ $invoiceEntity->bank_name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p>{{ $invoiceEntity->branch }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Description </label>
                            <p>{!! $invoiceEntity->description !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            @if ($invoiceEntity->status == 1)
                                <p>Active</p>
                            @else
                                <p>Inactive</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
