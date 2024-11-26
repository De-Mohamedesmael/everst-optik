@extends('back-end.layouts.app')
@section('title', __('lang.forfeit_leaves'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('content')
    <section class="forms py-0">

        <div class="container-fluid">
            <div
                class="  d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                <h5 class="print-title mb-0 position-relative print-title" style="margin-right: 30px">@lang('lang.list_of_employees_in_forfeit_leave')
                    <span class="header-pill"></span>
                </h5>
            </div>
            <div class="card my-3">
                <div class="card-body p-2">
                    <div id="sales">
                        <form action="">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-3 px-5">


                                    {!! Form::label('start_date', __('lang.start_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('start_date', request()->start_date, [
                                        'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}

                                </div>
                                <div class="col-md-3 px-5">


                                    {!! Form::label('end_date', __('lang.end_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('end_date', request()->end_date, [
                                        'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}

                                </div>

                                <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-main col-md-12">@lang('lang.filter')</button>
                                </div>

                                <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">
                                    <a href="{{ action('ForfeitLeaveController@index') }}"
                                        class="btn btn-main col-md-12">@lang('lang.clear_filter')</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-body p-2">

                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>@lang('lang.employee_name')</th>
                                <th>@lang('lang.leave_type')</th>
                                <th>@lang('lang.year')</th>
                                <th>@lang('lang.number_of_days')</th>
                                <th>@lang('lang.who_forfeited')</th>
                                <th>@lang('lang.upload_files')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($forfeit_leaves as $leave)
                                <tr>
                                    <td>
                                        {{ $leave->name }}
                                    </td>
                                    <td>
                                        {{ $leave->leave_type_name }}
                                    </td>
                                    <td>
                                        {{ $leave->start_date }}
                                    </td>
                                    <td>
                                        {{ @num_format($leave->number_of_days) }}
                                    </td>
                                    <td>{{ ucfirst($leave->created_by) }}</td>
                                    <td>
                                        <a data-href="{{ action('GeneralController@viewUploadedFiles', ['model_name' => 'ForfeitLeave', 'model_id' => $leave->id, 'collection_name' => 'forfeit_leave']) }}"
                                            data-container=".view_modal"
                                            class="btn btn-danger btn-modal text-white">@lang('lang.view')</a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </section>
    <div class="modal fade second_modal" role="dialog" aria-hidden="true"></div>

@endsection

@section('javascript')
    <script></script>
@endsection
