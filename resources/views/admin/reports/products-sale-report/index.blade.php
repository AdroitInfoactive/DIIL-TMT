@extends('admin.layouts.master')
<style>
    .spinner {
        height: 60px;
        width: 60px;
        margin: auto;
        display: flex;
        position: absolute;
        -webkit-animation: rotation .6s infinite linear;
        -moz-animation: rotation .6s infinite linear;
        -o-animation: rotation .6s infinite linear;
        animation: rotation .6s infinite linear;
        border-left: 6px solid rgba(0, 174, 239, .15);
        border-right: 6px solid rgba(0, 174, 239, .15);
        border-bottom: 6px solid rgba(0, 174, 239, .15);
        border-top: 6px solid rgba(0, 174, 239, .8);
        border-radius: 100%;
    }

    @-webkit-keyframes rotation {
        from {
            -webkit-transform: rotate(0deg);
        }

        to {
            -webkit-transform: rotate(359deg);
        }
    }

    @-moz-keyframes rotation {
        from {
            -moz-transform: rotate(0deg);
        }

        to {
            -moz-transform: rotate(359deg);
        }
    }

    @-o-keyframes rotation {
        from {
            -o-transform: rotate(0deg);
        }

        to {
            -o-transform: rotate(359deg);
        }
    }

    @keyframes rotation {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(359deg);
        }
    }

    #overlay {
        position: absolute;
        display: none;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2;
        cursor: pointer;
    }
</style>
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reports</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Products Sale Report</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="{{ $from_date }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="{{ $to_date }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary reports-search">Search</button>
                    </div>
                </div>
                <div id="overlay" onclick="off()">
                    <div class="w-100 d-flex justify-content-center align-items-center">
                        <div class="spinner"></div>
                    </div>
                </div>
                <div class="card card-primary report-table">
                    <h4>Product Sale Report from {{ date('d-m-Y', strtotime($from_date)) }} to
                        {{ date('d-m-Y', strtotime($to_date)) }}</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <tr>
                                <th>S.No</th>
                                <th>Product</th>
                                <th>UOM</th>
                                <th>Make</th>
                                <th style="text-align: right;">Quantity Sold</th>
                            </tr>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->uom }}</td>
                                    <td>{{ $product->make }}</td>
                                    <td style="text-align: right;">{{ $product->quantity }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.reports-search').on('click', function() {
                let from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date == '') {
                    alert('Please select from date');
                    return;
                }
                if (to_date == '') {
                    alert('Please select to date');
                    return;
                }
                $.ajax({
                    url: "{{ route('reports.products-sale-report.get-report') }}",
                    method: "get",
                    data: {
                        from_date: from_date,
                        to_date: to_date
                    },
                    beforeSend: function() {
                        document.getElementById("overlay").style.display = "flex";
                    },
                    success: function(data) {
                        $('.report-table').html(data);
                        document.getElementById("overlay").style.display = "none";
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching reports, please try again.');
                        document.getElementById("overlay").style.display = "none";
                    },
                    complete: function() {
                        document.getElementById("overlay").style.display = "none";
                    }
                })
            })
        })
    </script>
@endpush
