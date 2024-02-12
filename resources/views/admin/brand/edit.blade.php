@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Make/Brand</h1>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Update Brand</h4>

        </div>
        <div class="card-body">
            <form action="{{ route('brand.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ @$vendor->name }}">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="10">{!! @$vendor->description !!}</textarea>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" id="">
                        <option @selected($vendor->status === 1) value="1">Active</option>
                        <option @selected($vendor->status === 0) value="0">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</section>
@endsection
