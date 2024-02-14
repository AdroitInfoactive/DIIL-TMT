@extends('admin.layouts.master')
@section('content')
        <section class="section">
            <div class="section-header">
                <h1>User Profile</h1>
            </div>

            <div class="section-body">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Update User Profile</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            {{-- <input type="hidden" name="id" value="{{ $user->id }}"> --}}
                            <div class="form-group">
                                <div id="image-preview" class="image-preview">
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="avatar" id="image-upload" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ $user->name }}"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{$user->email }}"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.image-preview').css ({
                'background-image': 'url({{ asset($user->avatar) }})',
                'background-size': 'cover',
                'background-position': 'center center',
            })
        })
    </script>
@endpush
