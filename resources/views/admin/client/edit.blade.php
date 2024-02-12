@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Clients</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Client</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('client.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Organisation Name * </label>
                                <input type="text" name="name" value="{{ $client->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Email Id *</label>
                                <input type="text" name="email" value="{{ $client->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>GST Number</label>
                                <input type="text" name="gst_no" value="{{ $client->gst_no }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Address *</label>
                                <input type="text" name="address" value="{{ $client->address }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Area</label>
                                <input type="text" name="area" value="{{ $client->area }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" name="city" value="{{ $client->city }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>State *</label>
                                <input type="text" name="state" value="{{ $client->state }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Country *</label>
                                <input type="text" name="country" value="{{ $client->country }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Pincode *</label>
                                <input type="text" name="pincode" value="{{ $client->pincode }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Primary Contact</h6>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Person Name *</label>
                                <input type="text" name="primary_name" value="{{ $client->primary_name }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Mobile *</label>
                                <input type="text" name="primary_mobile" value="{{ $client->primary_mobile }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Whatsapp</label>
                                <input type="text" name="primary_whatsapp" value="{{ $client->primary_whatsapp }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Secondary Contact</h6>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Person Name</label>
                                <input type="text" name="secondary_name" value="{{ $client->secondary_name }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" name="secondary_mobile" value="{{ $client->secondary_mobile }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Whatsapp</label>
                                <input type="text" name="secondary_whatsapp" value="{{ $client->secondary_whatsapp }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Description </label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"> {!! $client->description !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control">
                            <option value="1" @selected($client->status === 1)>Active</option>
                            <option value="0" @selected($client->status === 0)>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
