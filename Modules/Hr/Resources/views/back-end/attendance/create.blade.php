@extends('back-end.layouts.app')
@section('title', __('lang.add_attendance'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.hr.employees.index') }}">
            @lang('lang.employees')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        <a style="text-decoration: none;color: #476762" href="{{ action('AttendanceController@index') }}">@lang('lang.attendance')</a></li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        {{translate('add_attendance')}}</li>
@endsection
@section('content')
    <section class="forms px-3 py-1">

        <div class="container-fluid">

            <div
                class="  d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                <h5 class="print-title mb-0 position-relative print-title" style="margin-right: 30px">@lang('lang.attendance')
                    <span class="header-pill"></span>
                </h5>
            </div>
            <div class="card my-2">
                <div class="card-body p-2">
                    <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <div class="col-sm-12">
                            {!! Form::open(['url' => action('AttendanceController@store'), 'method' => 'post']) !!}
                            <input type="hidden" name="index" id="index" value="0">


                            <table class="table table_tbody" id="attendance_table">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.employee')</th>
                                        <th>@lang('lang.checkin')</th>
                                        <th>@lang('lang.checkout')</th>
                                        <th>@lang('lang.status')</th>
                                        <th>@lang('lang.created_by')</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @include('hr::back-end.attendance.partials.attendance_row',["row_index"=>0])

                                </tbody>
                            </table>
                            <div class="row justify-content-center align-items-center mb-3">

                                <button type="button" class="btn btn-main col-md-3 add_row" id="add_row">+
                                    @lang('lang.add_row')</button>

                            </div>
                            <div class="row justify-content-center align-items-center  mt-3 attendance-btn">

                                <input type="submit" class="btn btn-main mt-3 col-md-3" value="@lang('lang.save')"
                                    name="submit">
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('javascript')
    <script>
        $('#add_row').click(function() {
            row_index = parseInt($('#index').val());
            row_index = row_index + 1;
            $('#index').val(row_index);
            $.ajax({
                method: 'get',
                url: '/hrm/attendance/get-attendance-row/' + row_index,
                data: {},
                contentType: 'html',
                success: function(result) {
                    $('#attendance_table tbody').append(result);
                    $("#attendances_"+row_index).selectpicker("refresh");
                    $("#attendances_status_"+row_index).selectpicker("refresh");
                },
            });
        });
        // +++++++++++++ remove "row" to table +++++++++++++
        $('.table_tbody').on('click', '.deleteRow', function() {
            console.log('454d564fdf');
            $(this).parent().parent().remove();
        });
        // Assuming check_in and check_out are the IDs of your input fields
        $('#check_in_id, #check_out_id').on('change', function() {
            var checkInValue = $('#check_in_id').val();
            var checkOutValue = $('#check_out_id').val();
            // Check if both check_in and check_out values are set
            if (checkInValue && checkOutValue) {
                // Set the value of the status select box to "present"
                $('#status_id').val('present').trigger(
                    'change'); // Use trigger('change') to trigger the change event
            }
        });
    </script>
@endsection
