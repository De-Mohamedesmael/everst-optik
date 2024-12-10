@extends('back-end.layouts.app')
@section('title', __('lang.watch_moneysafe_transaction'))



@section('breadcrumbs')
@parent
<li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
    {{translate('watch_moneysafe_transaction')}}</li>
@endsection

@section('content')
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">
        <!-- Start col -->
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="container-fluid">
                                @include('setting::back-end.money_safe.filters', ['id' => $moneySafeTransactions->id])
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive @if (app()->isLocale('ar')) dir-rtl @endif">
                        <table id="datatable-buttons" class="table table-striped table-bordered table-button-wrapper">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('lang.creation_date')</th>
                                    <th>@lang('lang.source')</th>
                                    <th>@lang('lang.job')</th>
                                    <th>@lang('lang.store')</th>
                                    <th>@lang('lang.type')</th>
                                    <th>@lang('lang.amount') &nbsp; {{ $basic_currency }}</th>
                                    <th>@lang('lang.balance')&nbsp; {{ $basic_currency }}</th>
                                    <th>@lang('added_by')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moneySafeTransactions->transactions as $index => $msafe_trans)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600"
                                            data-tooltip="@lang('lang.creation_date')">
                                            {{ $msafe_trans->transaction_date }}
                                    </td>
                                    </span>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600"
                                            data-tooltip="@lang('lang.source')">

                                            @lang('lang.' . $msafe_trans->source_type . '')
                                    </td>
                                    </span>
                                    <td class="textcenter">
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600" data-tooltip="@lang('lang.job')">
                                            {{ isset($msafe_trans->job_type_id) ? $msafe_trans->job_type->job_title : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600" data-tooltip="@lang('lang.store')">
                                            {{ $msafe_trans->store->name }}
                                    </td>
                                    </span>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600" data-tooltip="@lang('lang.type')">
                                            @lang('lang.' . $msafe_trans->type . '')
                                    </td>
                                    </span>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600"
                                            data-tooltip="@lang('lang.amount')">

                                            @if ($msafe_trans->type == 'add_money')
                                            <span class="text-primary">{{ $basic_currency }}&nbsp;{{
                                                @num_format($msafe_trans->amount) }}</span>
                                            @else
                                            <span class="text-danger">{{ $basic_currency }}&nbsp;{{
                                                @num_format($msafe_trans->amount) }}</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600"
                                            data-tooltip="@lang('lang.balance')">
                                            {{ $basic_currency }}&nbsp;{{ @num_format($msafe_trans->balance) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="custom-tooltip d-flex justify-content-center align-items-center"
                                            style="font-size: 12px;font-weight: 600" data-tooltip="@lang('added_by')">
                                            @if ($msafe_trans->created_by > 0 and $msafe_trans->created_by != null)
                                            {{ $msafe_trans->created_at->diffForHumans() }} <br>
                                            {{ $msafe_trans->created_at->format('Y-m-d') }}
                                            ({{ $msafe_trans->created_at->format('h:i') }})
                                            {{ $msafe_trans->created_at->format('A') == 'AM' ? __('am') : __('pm') }}
                                            <br>
                                            {{ $msafe_trans->createBy?->name }}
                                            @else
                                            {{ __('no_update') }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->
</div>
<!-- End Rightbar -->
</div>
@endsection
