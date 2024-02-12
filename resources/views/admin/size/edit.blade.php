@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Unit Of Measure(UOM)</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update UOM</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('size.update', $size->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ @$size->name }}">
                    </div>

                    <div class="form-group">

                        <label>Description</label>
                        <textarea name="description" class="form-control" id="" cols="30" rows="10">{!! @$size->description !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control" id="">
                            <option @selected($size->status === 1) value="1">Active</option>
                            <option @selected($size->status === 0) value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
