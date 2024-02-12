@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Collection Taxes ({{ $tax->name }})</h1>
        </div>

        <div>
            <a href="{{ route('tax.index') }}" class="btn btn-primary my-3">Go Back</a>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card card-primary">

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>value</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collectionTaxes as $collectionTax)
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ @$collectionTax->name }}</td>
                                        <td>{{ @$collectionTax->value }}</td>

                                    </tr>
                                @endforeach
                                @if (count($collectionTaxes) === 0)
                                    <tr>
                                        <td colspan='3' class="text-center">No data found!</td>

                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
