@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Receipts</h1>
        </div>
        <div>
            <a href="{{ route('receipt.index') }}" class="btn btn-primary my-3">Go Back</a>
            <a href="{{ route('receipt.edit', $receipt->id) }}" class='btn btn-primary ml-2 mr-2'>Edit</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Client * </label>
                                    <p>{{ $receipt->client?->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Recived date </label>
                                    <p>{!! date('d/m/Y', strtotime($receipt->received_date)) !!}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Recived Amount </label>
                                    <p>{!! currencyPosition($receipt->received_amount) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Transaction Type </label>
                                    <p>{!! $receipt->transaction_type !!}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Transaction Reference Number </label>
                                    <p>{{ $receipt->transaction_reference }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Description </label>
                                    <p>{{ $receipt->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
