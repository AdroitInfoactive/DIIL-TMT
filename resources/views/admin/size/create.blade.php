@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1> Unit Of Measure(UOM)</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create UOM</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('size.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"> {!! old('description') !!}</textarea>
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
