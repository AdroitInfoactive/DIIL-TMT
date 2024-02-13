@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Accepted Orders</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Accepted Orders</h4>
               {{--  <div class="card-header-action">
                    <a href="{{ route('order.create') }}" class="btn btn-primary">
                        Create new
                    </a>
                </div> --}}
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
