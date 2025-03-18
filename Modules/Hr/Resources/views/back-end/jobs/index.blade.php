@extends('back-end.layouts.app')
@section('title', __('lang.jobs'))


@push('css')
<style>
    .table-top-head {
        top: 35px;
    }

    .wrapper1 {
        margin-top: 35px;
    }

    @media(max-width:767px) {
        .wrapper1 {
            margin-top: 120px;
        }
    }
</style>
@endpush

@section('page_title')
@lang('lang.jobs')
@endsection

@section('breadcrumbs')
@parent
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif"><a
        style="text-decoration: none;color: #3e5d58" href="#"> @lang('lang.employees')</a></li>
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    @lang('lang.jobs')</li>
@endsection

@section('button')
<div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
    <button type="button" class="btn btn-primary" data-toggle="modal"
        data-target=".add-job">@lang('lang.add_job')</button>
</div>
@endsection

@section('content')
@include('hr::back-end.jobs.create')
<div class="animate-in-page">

    <div class="container-fluid">
        <div class="col-md-12  no-print">
            <div class="card mt-1">
                <div
                    class="card-header d-flex align-items-center @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h6 class="print-title ">
                        @lang('lang.jobs')</h6>
                </div>
                <div class="card-body">
                    <div class="wrapper1 @if (app()->isLocale('ar')) dir-rtl @endif">
                        <div class="div1"></div>
                    </div>
                    <div class="wrapper2 @if (app()->isLocale('ar')) dir-rtl @endif">
                        <div class="div2 table-scroll-wrapper">
                            <!-- content goes here -->
                            <div style="min-width: 1300px;max-height: 90vh;overflow: auto">
                                <table id="datatable-buttons"
                                    class="table dataTable table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.job_title')</th>
                                            <th>@lang('lang.date_of_creation')</th>
                                            <th>@lang('lang.created_by')</th>
                                            <th class="notexport">@lang('lang.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobs as $job)
                                        <tr>
                                            <td>
                                                <span
                                                    class="custom-tooltip  d-flex justify-content-center align-items-center"
                                                    style="font-size: 12px;font-weight: 600"
                                                    data-tooltip="@lang('lang.job_title')">
                                                    @if (in_array($job->job_title, ['Cashier', 'Representative',
                                                    'Preparer']))
                                                    {{translate($job->job_title)}}
                                                    @else
                                                    {{ $job->job_title }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="custom-tooltip  d-flex justify-content-center align-items-center"
                                                    style="font-size: 12px;font-weight: 600"
                                                    data-tooltip="@lang('lang.date_of_creation')">
                                                    {{ @format_date($job->date_of_creation) }}
                                                </span>
                                            </td>

                                            <td>

                                                <span
                                                    class="custom-tooltip  d-flex justify-content-center align-items-center"
                                                    style="font-size: 12px;font-weight: 600"
                                                    data-tooltip="@lang('lang.created_by')">
                                                    {{ $job->createdBy?->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a data-href="{{ route('admin.hr.jobs.edit', $job->id) }}"
                                                    data-container=".view_modal"
                                                    class="btn btn-primary btn-modal text-white edit_job">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                @if (!in_array($job->job_title, ['Cashier', 'Representative',
                                                'Preparer']))
                                                {{-- @if (!in_array($job->id, [1, 2, 3, 4]))--}}
                                                <a data-href="{{ route('admin.hr.jobs.destroy', $job->id) }}"
                                                    class="btn btn-danger text-white delete_item">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endif

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
</div>

@endsection