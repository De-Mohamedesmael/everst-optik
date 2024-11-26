@extends('back-end.layouts.app')
@section('title', __('lang.wages_and_compensations'))
@section('breadcrumbs')
    @parent

    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.wages_and_compensations')</li>
@endsection

@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <a class="btn btn-primary" href="{{ action('WagesAndCompensationController@create') }}">
            <i class="fa fa-plus"></i> @lang('lang.add')</a>
    </div>
@endsection
@section('content')
    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div class="card mb-2 mt-2">
                        <div class="card-body p-2">
                            <form action="">
                                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.date_of_creation') @lang('lang.start_date')</label>
                                            {!! Form::text('doc_start_date', null, [
                                                'class' => 'form-control datepicker  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.date_of_creation') @lang('lang.end_date')</label>
                                            {!! Form::text('doc_end_date', null, [
                                                'class' => 'form-control datepicker  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.employees')</label>
                                            {!! Form::select('employee_id', $employees, null, ['class' => 'form-control', 'placeholder' => __('lang.all')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.store')</label>
                                            {!! Form::select('store_id', $stores, null, ['class' => 'form-control', 'placeholder' => __('lang.all')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.job')</label>
                                            {!! Form::select('job_type_id', $jobs, null, ['class' => 'form-control', 'placeholder' => __('lang.all')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.payment_type')</label>
                                            {!! Form::select('payment_type', $payment_types, null, [
                                                'class' => 'form-control',
                                                'placeholder' => __('lang.all'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.status')</label>
                                            {!! Form::select('status', ['paid' => 'Paid', 'pending' => 'Pending'], null, [
                                                'class' => 'form-control',
                                                'placeholder' => __('lang.all'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.payment_start_date')</label>
                                            {!! Form::text('payment_start_date', null, [
                                                'class' => 'form-control datepicker  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5">
                                        <div class="form-group">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="">@lang('lang.payment_end_date')</label>
                                            {!! Form::text('payment_end_date', null, [
                                                'class' => 'form-control datepicker  modal-input app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">

                                        <button type="submit" class="btn btn-main col-md-12">@lang('lang.filter')</button>
                                    </div>
                                    <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">
                                        <a href="{{ action('WagesAndCompensationController@index') }}"
                                            class="btn btn-danger col-md-12">@lang('lang.clear_filter')</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                    <div class="card my-3">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.date_of_creation')</th>
                                            <th>@lang('lang.employee_name')</th>
                                            <th>@lang('lang.photo')</th>
                                            <th>@lang('lang.account_period')</th>
                                            <th>@lang('lang.job_title')</th>
                                            <th>@lang('lang.amount_paid')</th>
                                            <th>@lang('lang.type_of_payment')</th>
                                            <th>@lang('lang.date_of_payment')</th>
                                            <th>@lang('lang.paid_by')</th>
                                            <th>@lang('lang.view_uploads')</th>
                                            <th>@lang('lang.status')</th>
                                            <th class="notexport">@lang('lang.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($wages_and_compensations as $wages_and_compensation)
                                            <tr>
                                                <td>{{ @format_date($wages_and_compensation->date_of_creation) }}</td>
                                                <td>
                                                    {{ $wages_and_compensation->employee_name }}
                                                </td>
                                                @php
                                                    $employee = Modules\Hr\Entities\Employee::find(
                                                        $wages_and_compensation->employee_id,
                                                    );
                                                @endphp
                                                <td>
                                                    <img src="@if (!empty($employee->getFirstMediaUrl('employee_photo'))) {{ $employee->getFirstMediaUrl('employee_photo') }}@else{{ asset('uploads/' . session('logo')) }} @endif"
                                                        style="width: 50px; border: 2px solid #fff;" />
                                                </td>
                                                <td>
                                                    @if ($wages_and_compensation->payment_type == 'salary')
                                                        {{ $wages_and_compensation->account_period }}
                                                    @else
                                                        @if (!empty($wages_and_compensation->acount_period_start_date))
                                                            {{ @format_date($wages_and_compensation->acount_period_start_date) }}
                                                        @endif
                                                        -
                                                        @if (!empty($wages_and_compensation->acount_period_end_date))
                                                            {{ @format_date($wages_and_compensation->acount_period_end_date) }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $wages_and_compensation->job_title }}</td>
                                                <td>
                                                    {{ session()->get('currency.symbol') }}
                                                    {{ @num_format($wages_and_compensation->net_amount) }}
                                                </td>
                                                <td>
                                                    @if (!empty($payment_types[$wages_and_compensation->payment_type]))
                                                        {{ $payment_types[$wages_and_compensation->payment_type] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ @format_date($wages_and_compensation->payment_date) }}
                                                </td>
                                                <td>
                                                    @if (!empty($wages_and_compensation->transaction))
                                                        {{ $wages_and_compensation->transaction->source->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a data-href="{{ action('GeneralController@viewUploadedFiles', ['model_name' => 'WagesAndCompensation', 'model_id' => $wages_and_compensation->id, 'collection_name' => 'wages_and_compensation']) }}"
                                                        data-container=".view_modal"
                                                        class="btn btn-warning btn-modal text-white">@lang('lang.view')</a>
                                                </td>
                                                <td>
                                                    {{ ucfirst($wages_and_compensation->status) }}
                                                </td>
                                                <td>
                                                    @if ($wages_and_compensation->status != 'paid')
                                                        @can('hr_management.wages_and_compensation.create_and_edit')
                                                            <a href="{{ action('WagesAndCompensationController@changeStatusToPaid', $wages_and_compensation->id) }}"
                                                                class="btn btn-success text-white">@lang('lang.paid')</a>
                                                        @endcan
                                                    @endif
                                                    @can('hr_management.wages_and_compensation.view')
                                                        <a href="{{ action('WagesAndCompensationController@show', $wages_and_compensation->id) }}"
                                                            class="btn btn-secondary text-white edit_job"><i
                                                                class="fa fa-eye"></i></a>
                                                    @endcan
                                                    @can('hr_management.wages_and_compensation.create_and_edit')
                                                        <a href="{{ action('WagesAndCompensationController@edit', $wages_and_compensation->id) }}"
                                                            class="btn btn-primary text-white edit_job"><i
                                                                class="fa fa-pencil-square-o"></i></a>
                                                    @endcan
                                                    @can('hr_management.wages_and_compensation.delete')
                                                        <a data-href="{{ action('WagesAndCompensationController@destroy', $wages_and_compensation->id) }}"
                                                            data-check_password="{{ route('admin.check-password', auth('admin')->user()->id) }}"
                                                            class="btn btn-danger text-white delete_item"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endcan

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

    </section>
@endsection

@section('javascript')
    <script></script>
@endsection
