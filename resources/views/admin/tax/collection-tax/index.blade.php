@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Collection Taxes ({{ $tax->name }})</h1>
        </div>

        <div>
            <a href="{{ route('tax.index') }}" class="btn btn-primary my-3 ">Go Back</a>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Create Collection Taxes</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('collection-tax.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $tax->id }}" name="tax_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Name *</label>
                                        <input type="text" name="name" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">value *</label>
                                        <input type="text" name="value" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collectionTaxes as $collectionTax)
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ @$collectionTax->name }}</td>
                                        <td>{{ @$collectionTax->value }}</td>
                                        <td>
                                            <a href='{{ route('collection-tax.destroy', $collectionTax->id) }}'
                                                class='btn btn-danger delete-item mx-2'><i class='fas fa-trash'></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($collectionTaxes) === 0)
                                    <tr>
                                        <td colspan='4' class="text-center">No data found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Add / Update Collection Taxes Products</h4>
                    </div>
                    <div class="card-body">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $('body').on('change', '.update-product', function() {
                var id = $(this).data('id');
                var checked = $(this).is(':checked');
                var tax_id = $(this).closest('tr').find('input[name="tax_id"]').val();
                $.ajax({
                    url: "{{ route('collection-tax.update-product') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        checked: checked,
                        tax_id: tax_id
                    },
                    beforeSend: function() {

                    },
                    success: function(response) {
                        console.log(response);
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        toastr.error(errorMessage);
                    },
                    complete: function() {

                    }
                })
            })
        })
    </script>
@endpush
