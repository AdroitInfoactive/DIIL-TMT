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
            <h1>Settings</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>All Settings</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-2">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#general-setting"
                                    role="tab" aria-controls="home" aria-selected="true">General Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#logo-setting" role="tab"
                                    aria-controls="home" aria-selected="true">Logo Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#erase-setting" role="tab"
                                    aria-controls="home" aria-selected="true">Erase Data</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-10">
                        <div class="tab-content no-padding" id="myTab2Content">
                            @include('admin.setting.sections.general-setting')
                            @include('admin.setting.sections.logo-setting')
                            @include('admin.setting.sections.erase-setting')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(':checkbox').on('change', function() {
            // check children
            $(this).closest('li').find(':checkbox').prop('checked', this.checked);

            // check parent if all children checked
            var $ul = $(this).closest('ul');
            var $siblings = $ul.find('> li > label > :checkbox');
            var allChecked = $siblings.length == $siblings.filter(':checked').length;
            $ul.closest('li').find('> label > :checkbox').prop('checked', allChecked);
        });
        $('.delete-data').on('click', function(e) {
            e.preventDefault();
            modules = $('input[name="modules[]"]:checked').map(function() {
                return $(this).val();
            }).get();
            if (modules.length == 0) {
                alert('Please select at least one module to delete.');
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "Your data will be deleted. You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('setting.delete-data') }}",
                        method: 'POST',
                        // get values from selected check boxes
                        data: {
                            _token: '{{ csrf_token() }}',
                            modules: modules
                        },
                        beforeSend: function() {
                            document.getElementById("overlay").style.display = "flex";
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                // $('#slider-table').DataTable().draw();
                                // make checkboxes unchecked
                                $('input[type="checkbox"]').prop('checked', false);
                                location.reload();
                            } else if (response.status === 'error') {
                                toastr.error(response.message);
                            }
                            document.getElementById("overlay").style.display = "none";
                        },
                        complete: function() {
                            document.getElementById("overlay").style.display = "none";
                        },
                        error: function(error) {
                            document.getElementById("overlay").style.display = "none";
                        }
                    });
                }
            });
        })
    </script>
@endpush
