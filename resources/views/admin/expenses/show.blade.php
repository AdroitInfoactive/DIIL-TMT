@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Rxpenses</h1>
        </div>
        <div>
            <a href="{{ route('expense.index') }}" class="btn btn-primary my-3">Go Back</a>
            <a href="{{ route('expense.edit', $expense->id) }}" class='btn btn-primary ml-2 mr-2'>Edit</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Name </label>
                                    <p>{!! $expense->name !!}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Expenses date </label>
                                    <p>{!! date('d/m/Y', strtotime($expense->received_date)) !!}</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Expenses Amount </label>
                                    <p>{!! currencyPosition($expense->received_amount) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Description </label>
                                    <p>{{ $expense->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
