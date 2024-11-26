@extends('back-end.layouts.app')
@section('title', __('lang.attendance'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
                style="text-decoration: none;color: #476762" href="{{ route('admin.hr.employees.index') }}">/
            @lang('lang.employees')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.attendance')</li>
@endsection
@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <a class="btn btn-primary"
           href="{{ action('AttendanceController@create') }}">{{translate('add_attendance')}}</a>
    </div>
@endsection
@section('content')
    <section class="forms py-0">

        <div class="container-fluid">

            <div class="card mb-2 mt-2">
                <div class="card-body p-2">
                    <table class="table dataTable pt-2">
                        <thead>
                        <tr>
                            <th>@lang('lang.date')</th>
                            <th>@lang('lang.employee_name')</th>
                            <th>@lang('lang.check_in_time')</th>
                            <th>@lang('lang.check_out_time')</th>
                            <th>@lang('lang.status')</th>
                            <th>@lang('lang.created_by')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td>
                                    {{ @format_date($attendance->date) }}
                                </td>
                                <td>
                                    {{ $attendance->employee_name }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i:s A') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('h:i:s A') }}
                                </td>
                                <td>
                                    <span class="badge @attendance_status($attendance->status)">{{ __('lang.' . $attendance->status) }}</span>
                                    @if ($attendance->status == 'late')
                                        @php
                                            $check_in_data = [];
                                            $employee = Modules\Hr\Entities\Employee::find($attendance->employee_id);
                                            if (!empty($employee)) {
                                                $check_in_data = $employee->check_in;
                                            }
                                            $day_name = Illuminate\Support\Str::lower(
                                                \Carbon\Carbon::parse($attendance->date)->format('l'),
                                            );
                                            $late_time = 0;
                                            if (!empty($check_in_data[$day_name])) {
                                                $check_in_time = $check_in_data[$day_name];
                                                $late_time = \Carbon\Carbon::parse(
                                                    $attendance->check_in,
                                                )->diffInMinutes($check_in_time);
                                            }
                                        @endphp
                                        @if ($late_time > 0)
                                            +{{ $late_time }}
                                        @endif
                                    @endif
                                    @if ($attendance->status == 'on_leave')
                                        @php
                                            $leave = Modules\Customer\Entities\Leave::leftjoin(
                                                'leave_types',
                                                'leave_type_id',
                                                'leave_types.id',
                                            )
                                                ->where('employee_id', $attendance->employee_id)
                                                ->where('start_date', '>=', $attendance->date)
                                                ->where('start_date', '<=', $attendance->date)
                                                ->select('leave_types.name')
                                                ->first();
                                        @endphp
                                        @if (!empty($leave))
                                            {{ $leave->name }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ ucfirst($attendance->created_by) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </section>
@endsection

@section('javascript')
    <script></script>
@endsection
