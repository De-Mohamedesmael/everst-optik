@extends('layouts.app')
@section('title', __('lang.money_safes'))


@push('css')
    <style>
        .table-top-head {
            top: 32px;
        }

        .rightbar {
            z-index: 2;
        }

        .wrapper1 {
            margin-top: 30px;
        }

        @media(max-width:768px) {
            .wrapper1 {
                margin-top: 140px
            }
        }
    </style>
@endpush

@section('page_title')
    @lang('lang.money_safes')
@endsection

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.money_safes')</li>
@endsection

@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createMoneySafeModal">
            @lang('lang.add_money_safe')
        </button>
    </div>
@endsection

@section('content')
    @include('setting::back-end.money_safe.create')
    <div class="animate-in-page">

        <!-- End Breadcrumbbar -->
        <!-- Start Contentbar -->
        <div class="contentbar">
            <!-- Start row -->
            <div class="row">
                <!-- Start col -->
                <div class="col-lg-12">
                    <div class="card m-b-30">
                        <div class="card-header">
                            <h6 class="card-title  @if (app()->isLocale('ar')) text-end @else text-start @endif">
                                @lang('lang.money_safe')</h6>
                        </div>
                        <div class="card-body">
                            {{-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel & Note.</h6> --}}
                            <div class="wrapper1 @if (app()->isLocale('ar')) dir-rtl @endif">
                                <div class="div1"></div>
                            </div>
                            <div class="wrapper2 @if (app()->isLocale('ar')) dir-rtl @endif">
                                <div class="div2 table-scroll-wrapper">
                                    <!-- content goes here -->
                                    <div style="min-width:1300px;max-height: 90vh;overflow: auto">
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('lang.name')</th>
                                                    <th>@lang('lang.type')</th>
                                                    <th>@lang('lang.currency')</th>
                                                    <th>@lang('lang.balance')</th>
                                                    <th>@lang('added_by')</th>
                                                    <th>@lang('updated_by')</th>
                                                    <th>@lang('lang.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($money_safe as $index => $m_safe)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('lang.name')">
                                                                {{ $m_safe->name }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('lang.type')">

                                                                @lang('lang.' . $m_safe->type . '')
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('lang.currency')">

                                                                {{ $m_safe->currency->currency }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('lang.balance')">
                                                                {{ $m_safe->currency->symbol }}
                                                                {{ @num_format($m_safe->latest_balance) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('added_by')">

                                                                @if ($m_safe->created_by > 0 and $m_safe->created_by != null)
                                                                    {{ $m_safe->created_at->diffForHumans() }} <br>
                                                                    {{ $m_safe->created_at->format('Y-m-d') }}
                                                                    ({{ $m_safe->created_at->format('h:i') }})
                                                                    {{ $m_safe->created_at->format('A') == 'AM' ? __('am') : __('pm') }}
                                                                    <br>
                                                                    {{ $m_safe->createBy?->name }}
                                                                @else
                                                                    {{ __('no_update') }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="custom-tooltip d-flex justify-content-center align-items-center"
                                                                style="font-size: 12px;font-weight: 600"
                                                                data-tooltip="@lang('updated_by')">

                                                                @if ($m_safe->edited_by > 0 and $m_safe->edited_by != null)
                                                                    {{ $m_safe->updated_at->diffForHumans() }} <br>
                                                                    {{ $m_safe->updated_at->format('Y-m-d') }}
                                                                    ({{ $m_safe->updated_at->format('h:i') }})
                                                                    {{ $m_safe->updated_at->format('A') == 'AM' ? __('am') : __('pm') }}
                                                                    <br>
                                                                    {{ $m_safe->updateBy?->name }}
                                                                @else
                                                                    {{ __('no_update') }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-default btn-sm dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    style="font-size: 12px;font-weight: 600"
                                                                    aria-expanded="false">خيارات
                                                                    <span class="caret"></span>
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                                    user="menu" x-placement="bottom-end"
                                                                    style="position: absolute; transform: translate3d(73px, 31px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <li>
                                                                        <a data-href="{{ route('money_safe.get-add-money-to-safe', $m_safe->id) }}"
                                                                            data-container=".view_modal"
                                                                            class="btn drop_down_item @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif btn-modal"
                                                                            data-toggle="modal"> <i class="fas fa-plus"></i>
                                                                            @lang('lang.add_to_money_safe')</a>
                                                                    </li>
                                                                    <li>
                                                                        <a data-href="{{ route('money_safe.get-take-money-to-safe', $m_safe->id) }}"
                                                                            data-container=".view_modal"
                                                                            class="btn drop_down_item @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif btn-modal"
                                                                            data-toggle="modal"> <i
                                                                                class="fas fa-minus"></i>
                                                                            @lang('lang.take_money_safe')</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('money_safe.watch-money-to-safe-transaction', $m_safe->id) }}"
                                                                            class="btn drop_down_item @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif"
                                                                            target="_blank"> <i class="fas fa-eye"></i>
                                                                            @lang('lang.watch_statement')</a>
                                                                    </li>
                                                                    <li>
                                                                        <a data-href="{{ route('money_safe.edit', $m_safe->id) }}"
                                                                            data-container=".view_modal"
                                                                            class="btn drop_down_item @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif btn-modal"
                                                                            data-toggle="modal"><i
                                                                                class="dripicons-document-edit"></i>
                                                                            @lang('lang.update')</a>
                                                                    </li>
                                                                    <li>
                                                                        <a data-href="{{ route('money_safe.destroy', $m_safe->id) }}"
                                                                            {{-- data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}" --}}
                                                                            class="btn drop_down_item @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif text-red delete_item"><i
                                                                                class="fa fa-trash"></i>
                                                                            @lang('lang.delete')</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="view_modal no-print">
                                        </div>
                                    </div>
                                </div>
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

@endsection
