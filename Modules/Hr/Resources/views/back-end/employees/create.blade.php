@extends('back-end.layouts.app')
@section('title', __('lang.add_employee'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/pos-modals.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.hr.employees.index') }}">/
            @lang('lang.employees')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.add_employee')</li>
@endsection


@section('content')
    <div class="animate-in-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">

                    <form class="form-group" id="new_employee_form" action="{{ route('admin.hr.employees.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card mb-3">
                            <div class="card-body p-2">
                                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="fname">@lang('lang.name')*</label>
                                        <input type="text"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="name" id="name" required placeholder="Name">
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="store_id">@lang('lang.store')</label>
                                        {!! Form::select(
                                            'store_id[]',
                                            $stores,
                                            !empty($stores) && count($stores) > 0 ? array_key_first($stores) : false,
                                            ['class' => 'form-control selectpicker', 'multiple', 'data-live-search' => 'true', 'id' => 'store_id'],
                                        ) !!}
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="email">@lang('lang.email')*
                                            <small>(@lang('lang.it_will_be_used_for_login'))</small></label>
                                        <input type="email"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="email" id="email" required placeholder="Email">
                                    </div>


                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="password">@lang('lang.password')*</label>
                                        <input type="password"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="password" id="password" required placeholder="Create New Password">
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="pass">@lang('lang.confirm_password')*</label>
                                        <input type="password"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            id="password_confirmation" name="password_confirmation" required
                                            placeholder="Conform Password">
                                    </div>



                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="date_of_start_working">@lang('lang.date_of_start_working')</label>
                                        <input type="date_of_start_working"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="date_of_start_working" id="date_of_start_working"
                                            placeholder="@lang('lang.date_of_start_working')">
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="date_of_birth">@lang('lang.date_of_birth')</label>
                                        <input type="date_of_birth"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="date_of_birth" id="date_of_birth" placeholder="@lang('lang.date_of_birth')">
                                    </div>



                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="job_type">@lang('lang.job_type')</label>
                                        {!! Form::select('job_type_id', $jobs, null, [
                                            'class' => 'form-control selectpicker',
                                            'placeholder' => __('lang.select_job_type'),
                                            'data-live-search' => 'true',
                                        ]) !!}
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="mobile">@lang('lang.mobile')*</label>
                                        <input type="mobile"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                            name="mobile" id="mobile" required placeholder="@lang('lang.mobile')">
                                    </div>



                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="upload_files">@lang('lang.upload_files')</label>
                                        {!! Form::file('upload_files[]', [
                                            'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'multiple',
                                        ]) !!}
                                    </div>
                                    <div class="col-md-4 px-5">
                                        <label
                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                            for="photo">@lang('lang.profile_photo')</label>
                                        <input type="file" name="photo" id="photo"
                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($leave_types->count() > 0 )
                            <div class="card mb-3">
                                <div class="card-body p-2">
                                    <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                        @foreach ($leave_types as $leave_type)
                                            <div class="col-md-4 px-5">
                                                <div class="form-group">
                                                    <div class="i-checks">
                                                        <input id="number_of_leaves{{ $leave_type->id }}"
                                                            name="number_of_leaves[{{ $leave_type->id }}][enabled]"
                                                            type="checkbox" value="1" class="form-control-custom">
                                                        <label
                                                            class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                            for="number_of_leaves{{ $leave_type->id }}"><strong>{{ $leave_type->name }}</strong></label>
                                                        <input type="number"
                                                            class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                                            name="number_of_leaves[{{ $leave_type->id }}][number_of_days]"
                                                            id="number_of_leaves" placeholder="{{ $leave_type->name }}"
                                                            readonly value="{{ $leave_type->number_of_days_per_year }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endisset
                        <div class="row px-5 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <!-- Button trigger modal -->

                            <button type="button" class="btn btn-main col-md-3" data-toggle="modal"
                                    data-target=".salary-details"> @lang('lang.salary_details')</button>


                            @include('hr::back-end.employees.partial.salary_details')
                        </div>

                        <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="px-5 col-md-12 mb-0">
                                <div
                                    class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                    <button class="text-decoration-none toggle-button mb-0" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#workingDaysCollapse"
                                        aria-expanded="false" aria-controls="workingDaysCollapse">
                                        <i class="fas fa-arrow-down"></i>
                                        @lang('lang.select_working_day_per_week')
                                        <span class="section-header-pill"></span>
                                    </button>
                                </div>
                                <div
                                    class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                    <div class="col-md-12 mb-0">
                                        <div class="collapse" id="workingDaysCollapse">
                                            <div class="card mb-3">
                                                <div class="card-body p-2">
                                                    <table style="width: 75%;margin: auto;">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>@lang('lang.check_in')</th>
                                                                <th> @lang('lang.check_out')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($week_days as $key => $week_day)
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <div class="i-checks">
                                                                                <input
                                                                                    id="working_day_per_week{{ $key }}"
                                                                                    name="working_day_per_week[{{ $key }}]"
                                                                                    type="checkbox" value="1"
                                                                                    class="form-control-custom">
                                                                                <label
                                                                                    for="working_day_per_week{{ $key }}"><strong>{{ $week_day }}</strong></label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('check_in[' . $key . ']', null, [
                                                                            'class' => 'form-control input-md check_in
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        time_picker',
                                                                        ]) !!}

                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('check_out[' . $key . ']', null, [
                                                                            'class' => 'form-control input-md check_out
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        time_picker',
                                                                        ]) !!}

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="px-5 col-md-12 mb-0">
                                <div
                                    class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                    <button class="text-decoration-none toggle-button mb-0" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#userRightsCollapse"
                                        aria-expanded="false" aria-controls="userRightsCollapse">
                                        <i class="fas fa-arrow-down"></i>
                                        @lang('lang.user_rights')
                                        <span class="section-header-pill"></span>
                                    </button>
                                </div>
                                <div class="collapse" id="userRightsCollapse">
                                    <div class="card mb-3">
                                        <div class="card-body p-2">
                                            <div
                                                class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                                <div class="col-md-12 mb-0">
                                                    @include('hr::back-end.employees.partial.permission')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-2 gap-5 justify-content-center align-items-center">

                            <div class="col-md-4">
                                <input type="submit" id="submit-btn" class="btn btn-main" value="@lang('lang.save')"
                                    name="submit">
                            </div>
                            <div class="col-md-4">
                                <input type="submit" id="send-btn" class="btn btn-main w-100"
                                    value="@lang('lang.send_credentials')" name="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('javascript')
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#userRightsCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#userRightsCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#userRightsCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#userRightsCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#workingDaysCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#workingDaysCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#workingDaysCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#workingDaysCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>


    <script>
        $('#date_of_start_working').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });
        $('#date_of_birth').datepicker({
            language: '{{ session('language') }}',
        });

        $('#fixed_wage').change(function() {
            console.log($(this).prop('checked'));
        })

        $('.checked_all').change(function() {
            tr = $(this).closest('tr');
            var checked_all = $(this).prop('checked');

            tr.find('.check_box').each(function(item) {
                if (checked_all === true) {
                    $(this).prop('checked', true)
                } else {
                    $(this).prop('checked', false)
                }
            })
        })
        $('.all_module_check_all').change(function() {
            var all_module_check_all = $(this).prop('checked');
            $('#permission_table > tbody > tr').each((i, tr) => {
                $(tr).find('.check_box').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.module_check_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.checked_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })

            })
        })
        $('.module_check_all').change(function() {
            let moudle_id = $(this).closest('tr').data('moudle');
            if ($(this).prop('checked')) {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', true);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', true);
            } else {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', false);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', false);
            }
        })
        $(document).on('change', '.view_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_view').prop('checked', true);
            } else {
                $('.check_box_view').prop('checked', false);
            }
        });
        $(document).on('change', '.create_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_create').prop('checked', true);
            } else {
                $('.check_box_create').prop('checked', false);
            }
        });
        $(document).on('change', '.delete_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_delete').prop('checked', true);
            } else {
                $('.check_box_delete').prop('checked', false);
            }
        });

        $(document).on('click', '#submit-btn, #send-btn', function(e) {
            jQuery('#new_employee_form').validate({
                rules: {
                    password: {
                        minlength: 6
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: "#password"
                    }
                }
            });
            if ($('#new_employee_form').valid()) {
                $('form#new_employee_form').submit();
            }
        });

        $(document).on('focusout', '.check_in', function() {
            $('.check_in').val($(this).val())
        })
        $(document).on('focusout', '.check_out', function() {
            $('.check_out').val($(this).val())
        })
    </script>
@endsection
