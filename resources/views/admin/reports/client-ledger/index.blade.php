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
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label>Client * </label>
                    <select name="client_id" id="client_id" class="select2 form-control">
                        <option value="">Select Client *</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @if (request('client_id') == $client->id) selected @endif>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control"
                        value="{{ request('from_date') }}" max="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                        value="{{ request('to_date') ? request('to_date') : date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
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
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                // Function to check if any of the input fields have values
                function hasValue() {
                    return $('#client_id').val() || $('#from_date').val();
                }

                // If any input field has a value, trigger the AJAX request
                if (hasValue()) {
                    triggerAjaxRequest();
                }

                // Event handler for the search button click
                $('.reports-search').on('click', function() {
                    // Trigger the AJAX request
                    triggerAjaxRequest();
                });

                // Function to trigger the AJAX request
                function triggerAjaxRequest() {
                    let client_id = $('#client_id').val();
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    if (client_id == '') {
                        alert('Please select client');
                        return;
                    }
                    if (to_date == '') {
                        // to date = current dat
                        to_date = new Date();
                    }
                    $.ajax({
                        url: "{{ route('reports.client-ledger.get-report') }}",
                        method: "get",
                        data: {
                            client_id: client_id,
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
                    });
                }
            });

        })
    </script>
@endpush
