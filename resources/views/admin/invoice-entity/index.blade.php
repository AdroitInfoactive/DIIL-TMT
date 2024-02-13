@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Inoice Entities</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>All Inoice Entities</h4>
                {{-- <div class="card-header-action">
                    <a href="{{ route('invoice-entity.create') }}" class="btn btn-primary">
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
