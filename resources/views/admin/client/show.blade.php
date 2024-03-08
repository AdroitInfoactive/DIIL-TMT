@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Clients</h1>
        </div>

        <div>
            <a href="{{ route('client.index') }}" class="btn btn-primary my-3">Go Back</a>
            <a href="{{ route('client.edit', $client->id) }}" class='btn btn-primary ml-2 mr-2'>Edit</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Organisation Name </label><p>{{ $client->name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Email Id</label>
                                    <p>{{ $client->email }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>GST Number</label>
                                    <p>{{ $client->gst_no }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Address</label>
                                    <p>{{ $client->address }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Area</label>
                                    <p>{{ $client->area }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <p>{{ $client->city }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <p>{{ $client->state }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Country</label>
                                    <p>{{ $client->country }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <p>{{ $client->pincode }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Primary Contact</h6>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Person Name</label>
                                    <p>{{ $client->primary_name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <p>{{ $client->primary_mobile }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Whatsapp</label>
                                    <p>{{ $client->primary_whatsapp }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Secondary Contact</h6>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Person Name</label>
                                    <p>{{ $client->secondary_name }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <p>{{ $client->secondary_mobile }}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Whatsapp</label>
                                    <p>{{ $client->secondary_whatsapp }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Description </label>
                            <p>{!! $client->description !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            @if ($client->status == 1)
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
