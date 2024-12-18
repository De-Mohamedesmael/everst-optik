@extends('back-end.layouts.app')
@section('title', __('lang.pos'))
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ url('front/css/pos.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('front/css/pos-modals.css') }}">
    <style>
        .input-group-btn button.btn {
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>
@endsection

@section('content')
@php
$watsapp_numbers = Modules\Setting\Entities\System::getProperty('watsapp_numbers');
@endphp
<section class="forms pos-section no-print p-0">
    <div class="container-fluid">

        <div class="row">
            <audio id="mysoundclip1" preload="auto">
                <source src="{{ asset('audio/beep-timber.mp3') }}">
                </source>
            </audio>
            <audio id="mysoundclip2" preload="auto">
                <source src="{{ asset('audio/beep-07.mp3') }}">
                </source>
            </audio>
            <audio id="mysoundclip3" preload="auto">
                <source src="{{ asset('audio/beep-long.mp3') }}">
                </source>
            </audio>

            <div class="col-md-9">
                {!! Form::open([
                'url' =>route('admin.pos.store'),
                'method' => 'post',
                'files' => true,
                'class' => 'pos-form',
                'id' => 'add_pos_form',
                ]) !!}
                <div class="card">
                    <div class="card-body" style="padding: 0px 10px; !important; margin-bottom: 50px !important;">
                        <input type="hidden" name="default_customer_id" id="default_customer_id"
                            value="@if (!empty($walk_in_customer)) {{ $walk_in_customer->id }} @endif">
                        <input type="hidden" name="row_count" id="row_count" value="0">
                        <input type="hidden" name="customer_size_id_hidden" id="customer_size_id_hidden" value="">
                        <input type="hidden" name="enable_the_table_reservation" id="enable_the_table_reservation"
                            value="{{ Modules\Setting\Entities\System::getProperty('enable_the_table_reservation') }}">
                        <div class="row">

                            <div class="card mb-0 pb-2 py-2 flex-row d-flex flex-wrap justify-content-between align-items-center"
                                style="border-radius: 8px;width: 100%">

                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="mb-2 mb-lg-0  height-responsive d-flex justify-content-center align-items-center"
                                        style="background-color: #e6e6e6 ; border: none;
                                    border-radius: 6px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;

                                    width: 100%;
                                    ">
                                        {{-- {!! Form::label('store_id', __('lang.store') . ':*', []) !!} --}}
                                        {!! Form::select('store_id', $stores, $store_pos->store_id, [
                                        'class' => 'selectpicker ',
                                        'data-live-search' => 'true',
                                        'required',
                                        'placeholder' => __('lang.please_select'),
                                        ]) !!}

                                    </div>
                                </div>

                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                                        style="background-color: #e6e6e6 ; border: none;
                                    border-radius: 6px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;

                                    width: 100%;
                                    ">
                                        {{-- {!! Form::label('store_pos_id', __('lang.pos') . ':*', []) !!} --}}
                                        {!! Form::select('store_pos_id', $store_poses, $store_pos->id, [
                                        'class' => 'selectpicker ',
                                        'data-live-search' => 'true',
                                        // 'required',
                                        'placeholder' => __('lang.pos'),
                                        ]) !!}
                                    </div>
                                </div>


                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                                        style="background-color: #e6e6e6 ; border: none;
                                    border-radius: 6px;
                                    color: #373737;
                                    box-shadow: 0 8px 6px -5px #bbb;

                                    width: 100%;
                                    ">
                                        <input type="hidden" name="setting_invoice_lang" id="setting_invoice_lang"
                                            value="{{ !empty(Modules\Setting\Entities\System::getProperty('invoice_lang')) ? Modules\Setting\Entities\System::getProperty('invoice_lang') : 'en' }}">
                                        {{-- {!! Form::label('invoice_lang', __('lang.invoice_lang') . ':', []) !!} --}}
                                        {!! Form::select(
                                        'invoice_lang',
                                        $languages + ['ar_and_en' => 'Arabic and English'],
                                        !empty(Modules\Setting\Entities\System::getProperty('invoice_lang')) ?
                                        Modules\Setting\Entities\System::getProperty('invoice_lang') : 'en',
                                        ['class' => 'selectpicker', 'data-live-search' => 'true'],
                                        ) !!}
                                    </div>
                                </div>


                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="form-group mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"
                                        style="background-color: #e6e6e6 ; border: none;
                                    border-radius: 6px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;

                                    width: 100%;
                                   ">
                                        <input type="hidden" name="exchange_rate" id="exchange_rate" value="1">
                                        <input type="hidden" name="default_currency_id" id="default_currency_id"
                                            value="{{ !empty(Modules\Setting\Entities\System::getProperty('currency')) ? Modules\Setting\Entities\System::getProperty('currency') : '' }}">
                                        {{-- {!! Form::label('received_currency_id', __('lang.received_currency') . ':',
                                        []) !!} --}}
                                        {!! Form::select(
                                        'received_currency_id',
                                        $exchange_rate_currencies,
                                        !empty(Modules\Setting\Entities\System::getProperty('currency')) ?
                                        Modules\Setting\Entities\System::getProperty('currency') : null,
                                        ['class' => ' selectpicker', 'data-live-search' => 'true'],
                                        ) !!}
                                    </div>
                                </div>

{{--                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">--}}
{{--                                    <div class="form-group tax mb-2 mb-lg-0 height-responsive d-flex justify-content-center align-items-center"--}}
{{--                                        style="background-color: #e6e6e6 ; border: none;--}}
{{--                                    border-radius: 6px;--}}
{{--                                    color: #373737;--}}
{{--                                                box-shadow: 0 8px 6px -5px #bbb;--}}

{{--                                    width: 100%;--}}
{{--                                    ">--}}
{{--                                        <select class="form-control" name="tax_id" id="tax_id"--}}
{{--                                            style="background-color: transparent">--}}
{{--                                            <option value="">No Tax</option>--}}
{{--                                            @foreach ($taxes as $tax)--}}
{{--                                            <option data-rate="{{ $tax['rate'] }}" @if (!empty($transaction) &&--}}
{{--                                                $transaction->tax_id == $tax['id']) selected @endif--}}
{{--                                                value="{{ $tax['id'] }}">--}}
{{--                                                {{ $tax['name'] }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
                                        <input type="hidden" name="tax_id_hidden" id="tax_id_hidden" value="">
                                        <input type="hidden" name="tax_method" id="tax_method" value="">
                                        <input type="hidden" name="tax_rate" id="tax_rate" value="0">
                                        <input type="hidden" name="tax_type" id="tax_type" value="">
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-6 col-lg-2 d-flex justify-content-center align-items-center">
{{--                                    <div class="col-6">--}}
{{--                                        <button type="button"--}}
{{--                                            class="btn btn-link btn-sm d-flex justify-content-center align-items-center height-responsive"--}}
{{--                                            style="background-color: #e6e6e6 ; border: none;--}}
{{--                                    border-radius: 16px;--}}
{{--                                    color: #373737;--}}
{{--                                                box-shadow: 0 8px 6px -5px #bbb;--}}
{{--                                    padding: 12px;--}}
{{--                                    width: 100%;--}}
{{--                                   " data-toggle="modal" data-target="#delivery-cost-modal">--}}

{{--                                            <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 640 512">--}}
{{--                                                <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->--}}
{{--                                                <path--}}
{{--                                                    d="M280 32c-13.3 0-24 10.7-24 24s10.7 24 24 24h57.7l16.4 30.3L256 192l-45.3-45.3c-12-12-28.3-18.7-45.3-18.7H64c-17.7 0-32 14.3-32 32v32h96c88.4 0 160 71.6 160 160c0 11-1.1 21.7-3.2 32h70.4c-2.1-10.3-3.2-21-3.2-32c0-52.2 25-98.6 63.7-127.8l15.4 28.6C402.4 276.3 384 312 384 352c0 70.7 57.3 128 128 128s128-57.3 128-128s-57.3-128-128-128c-13.5 0-26.5 2.1-38.7 6L418.2 128H480c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32H459.6c-7.5 0-14.7 2.6-20.5 7.4L391.7 78.9l-14-26c-7-12.9-20.5-21-35.2-21H280zM462.7 311.2l28.2 52.2c6.3 11.7 20.9 16 32.5 9.7s16-20.9 9.7-32.5l-28.2-52.2c2.3-.3 4.7-.4 7.1-.4c35.3 0 64 28.7 64 64s-28.7 64-64 64s-64-28.7-64-64c0-15.5 5.5-29.7 14.7-40.8zM187.3 376c-9.5 23.5-32.5 40-59.3 40c-35.3 0-64-28.7-64-64s28.7-64 64-64c26.9 0 49.9 16.5 59.3 40h66.4C242.5 268.8 190.5 224 128 224C57.3 224 0 281.3 0 352s57.3 128 128 128c62.5 0 114.5-44.8 125.8-104H187.3zM128 384a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />--}}
{{--                                            </svg>--}}

{{--                                        </button>--}}
{{--                                    </div>--}}
                                    <div class="col-6">
                                        <button type="button" id="print_and_draft"
                                            class="btn btn-link btn-sm d-flex justify-content-center align-items-center height-responsive"
                                            style="background-color: #e6e6e6 ; border: none;
                                    border-radius: 16px;
                                    color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb;
                                    padding: 12px;
                                    width: 100%;
                                   ">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em"
                                                viewBox="0 0 512 512">
                                                <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                <path
                                                    d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                                            </svg>
                                        </button>
                                        <input type="hidden" id="print_and_draft_hidden" name="print_and_draft_hidden"
                                            value="print_and_draft">
                                    </div>
                                </div>

                            </div>


                            <div style="border-radius: 8px;width: 100%"
                                class="py-2 d-flex card mb-0 flex-row justify-content-between align-items-start">
                                <div class="col-12 d-flex flex-column flex-lg-row">

                                    <div class="col-lg-4 mb-2 mb-lg-0 customer-div">

                                        <div class="col-12 form-group input-group my-group d-flex flex-row justify-content-center height-responsive"
                                            style="background-color: #e6e6e6 ; border: none;
                                        border-radius: 16px;
                                        color: #373737;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        width: 100%;
                                        margin: auto;
                                        flex-wrap: nowrap;
                                        padding-right:25px">
                                            {!! Form::select('customer_id', $customers, !empty($walk_in_customer) ?
                                            $walk_in_customer->id : null, [
                                            'class' => 'selectpicker ',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'id' => 'customer_id',
                                            'required',
                                            ]) !!}

                                            <span class="input-group-btn">
                                                @can('customer_module.customer.create_and_edit')
                                                <a style="background-color: var(--complementary-color-1);
                                        width: 100%;
                                        height: 100%;
                                        border-radius: 16px;
                                        padding: 6px 6px;
                                    cursor: pointer;
                                        " class="d-flex btn-modal justify-content-center align-items-center top-0 right-0"
                                                    data-href="{{ route('admin.customers.create') }}?quick_add=1"
                                                    data-container=".view_modal">
                                                    <svg class="plus" xmlns="http://www.w3.org/2000/svg" height="2em"
                                                        viewBox="0 0 448 512">
                                                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                        <style>
                                                            .plus {
                                                                fill: #ffffff
                                                            }
                                                        </style>
                                                        <path
                                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                                    </svg>
                                                </a>
                                                @endcan
                                            </span>

                                        </div>
                                    </div>

                                    <div class="col-lg-2 mb-2 mb-lg-0">
                                        <div style="width: 100%"
                                            class="col-12  p-0 input-group my-group d-flex flex-row justify-content-center height-responsive">
                                            <button type="button" style="background-color: #e6e6e6 ;
                                    border: none;
                                        border-radius: 16px;
                                        color: #373737;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        padding: 10px 6px;
                                        width: 100%;"
                                                class="height-responsive d-flex justify-content-center align-items-center"
                                                data-toggle="modal"
                                                data-target="#contact_details_modal">@lang('lang.details')
                                            </button>
                                        </div>

                                    </div>



                                    <div class="col-lg-2 mb-2 mb-lg-0">

                                        <div class="col-lg-12 mb-0 ml-1 input-group my-group d-flex justify-content-between                     height-responsive text-center"
                                            style="background-color: white; border: none;
                                      border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    width: 100%;
                                            flex-wrap: nowrap;font-size: 10px;padding:0">


                                            <label
                                                class="d-none justify-content-center align-items-center height-responsive"
                                                style="background-color: var(--secondary-color);
                                                width: 100%;

                                                border-radius: 16px;
                                                padding: 10px 12px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                padding:0;

                                                " for="customer_type_name">
                                                @lang('lang.customer_type'):
                                            </label>
                                            <span style="color: #000;width: 100%;"
                                                class="customer_type_name d-flex justify-content-center align-items-center height-responsive"></span>

                                        </div>
                                    </div>

                                    <div class="col-lg-2 mb-2 mb-lg-0">
                                        <div class="col-12 p-0 ml-1 input-group my-group d-flex flex-row justify-content-between height-responsive text-center"
                                            style="background-color: white; border: none;
                                 border: 1px solid #bbb;
                                    border-radius: 16px;
                                    color: white;
                                    box-shadow: 0 8px 6px -5px #bbb;
                                    width: 100%;
                                  ">
                                            <label
                                                class="d-flex justify-content-center justify-content-md-between align-items-center height-responsive te"
                                                for="customer_balance" style="background-color: var(--secondary-color);
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;
                                                padding: 10px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                color: #fff !important;
                                                ">@lang('lang.balance'):
                                            </label>
                                            <span style="color: #000; padding-right: 15px"
                                                class="customer_balance d-flex justify-content-start align-items-center">{{
                                                @num_format(0) }}</span>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class=" card row table_room_show hide">
                                <div class="col-md-4">
                                    <div class=""
                                        style="padding: 5px 5px; background:#0082ce; color: #fff; font-size: 20px; font-weight: bold; text-align: center; border-radius: 5px;">
                                        <span class="room_name"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for=""
                                        style="font-size: 20px !important; font-weight: bold; text-align: center; margin-top: 3px;">@lang('lang.table'):
                                        <span class="table_name"></span></label>
                                </div>
                                <input type="hidden" name="service_fee_rate" id="service_fee_rate" value="0">
                                <input type="hidden" name="service_fee_value" id="service_fee_value" value="0">
                            </div>

                            <div class="card rounded mb-0 mt-2 p-2 d-flex flex-column justify-content-between align-items-center"
                                style="border-radius: 8px;width: 100%">

                                <div class="search-box input-group form-group input-group my-group d-flex justify-content-between"
                                    style="background-color: #e6e6e6 ; border: none;
                                                                        border-radius: 16px;
                                                                        color: white;
                                                                        box-shadow: 0 8px 6px -5px #bbb ;
                                                                        width: 90%;
                                                                        margin: auto;
                                                                        width: 100%">
                                    <button type="button" style="background-color: var(--complementary-color-1);
                                                                                width: fit-content;
                                                                                height: 100%;
                                                                                border: none;
                                                                                border-radius: 16px;
                                                                                padding: 10px 20px;
                                                                                margin-bottom: 0;
                                                                                font-weight: 600;
                                                                                color: white
                                                                                " id="search_button">
                                        <i class="fa fa-search"></i>
                                    </button>


                                    <input type="text" name="search_product" id="search_product"
                                        placeholder="@lang('lang.enter_product_name_to_print_labels')"
                                        class="form-control ui-autocomplete-input"
                                        style="border: none; background-color: transparent" autocomplete="off">
                                    @if (isset($weighing_scale_setting['enable']) && $weighing_scale_setting['enable'])
                                    <button type="button" class="btn btn-default bg-white btn-flat"
                                        id="weighing_scale_btn" data-toggle="modal" data-target="#weighing_scale_modal"
                                        title="@lang('lang.weighing_scale')"><i
                                            class="fa fa-balance-scale fa-lg"></i></button>
                                    @endif


                                    <button type="button" class="text-black btn-modal pr-3" style="
                                                                                     background-color: transparent;
                                                                               border:none;
                                                                       outline: none;
                                                                                         "
                                        data-href="{{route('admin.products.create') }}?quick_add=1"
                                        data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                </div>


                            </div>

                            <div class="card mb-1 py-2 d-flex flex-column flex-lg-row justify-content-between align-items-start"
                                style="border-radius: 8px;width: 100%;min-height: 450px">
                                <div class="table-responsive transaction-list">
                                    <table class="table table-borderless" id="product_table" style="width: 100%;"
                                        class="table table-hover table-striped order-list table-fixed">
                                        <thead>
                                            <tr style="width: 100%">
                                                <th class="text-center text-black"
                                                    style="width:  17%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.products')</th>
                                                <th class="text-center text-black"
                                                    style="width:  12%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.quantity')</th>
                                                <th class="text-center text-black"
                                                    style="width:  12%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.price')</th>
                                                <th class="text-center text-black"
                                                    style="width:  11%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.discount')</th>
                                                <th class="text-center text-black"
                                                    style="width:  10%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.category_discount')</th>
                                                <th class="text-center text-black"
                                                    style="width:  9%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.sub_total')</th>

                                                <th class="text-center text-black"
                                                    style="width:  9%;  font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.current_stock')</th>
                                                <th class="text-center text-black"
                                                    style="width:  9%; font-size: 11px !important; font-weight: 700;">
                                                    @lang('lang.action')</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class=" py-2 col-12 pos-conditions" style="border-radius: 8px;">
                                <div class="card mb-2">
                                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mx-auto"
                                        style="width: 80%;">

                                        <div
                                            class="col-md-6 d-flex align-items-center justify-content-between p-0 text-center font-responsive mb-2 mb-lg-0">
                                            <div
                                                class="col-2 flex-column d-flex align-items-center text-center mb-2 mb-lg-0">
                                                <span class="totals-title mr-2" style="color: #000;font-weight: 600;">{{
                                                    __('lang.items') }}</span>
                                                <span id="item" class="border border-5 px-2 py-2 rounded">0</span>
                                            </div>

                                            <div
                                                class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                                                <span class="totals-title mr-2 "
                                                    style="color: #000;font-weight: 600;">{{ __('lang.quantity')
                                                    }}</span>
                                                <span id="item-quantity"
                                                    class="border border-5 px-2 py-2 rounded">0</span>
                                            </div>

                                            <div
                                                class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                                                <span class="totals-title mr-2" style="color: #000;font-weight: 600;">{{
                                                    __('lang.total') }}</span>
                                                <span id="subtotal"
                                                    class="border border-5 px-1 py-2 rounded">0.00</span>
                                            </div>

                                            <div
                                                class="col-2 flex-column d-flex align-items-center text-center  mb-2 mb-lg-0">
                                                <span class="totals-title mr-2" style="color: #000;font-weight: 600;">{{
                                                    __('lang.tax') }}
                                                </span>
                                                <span id="tax" class="border border-5 px-1 py-2 rounded">0.00</span>
                                            </div>



                                        </div>

                                        <div
                                            class="col-md-6 d-flex align-items-center text-center  mb-2 mb-lg-0 table_room_hide">


                                            <div class="col-6 font-responsive" style="padding: 0">
                                                @php
                                                $default_invoice_toc = Modules\Setting\Entities\System::getProperty(
                                                'invoice_terms_and_conditions',
                                                );
                                                if (!empty($default_invoice_toc)) {
                                                $toc_hidden = $default_invoice_toc;
                                                } else {
                                                $toc_hidden = array_key_first($tac);
                                                }
                                                @endphp
                                                <input type="hidden" name="terms_and_condition_hidden"
                                                    id="terms_and_condition_hidden" value="{{ $toc_hidden }}">

                                                {!! Form::label('terms_and_condition_id',
                                                __('lang.terms_and_conditions'), [
                                                'class' => 'label mb-0',
                                                ]) !!}
                                                <div style="background-color: #e6e6e6 ; border: none;
                                            border-radius: 6px;
                                            color: #373737;
                                            box-shadow: 0 8px 6px -5px #bbb ;

                                            width: 100%;
                                            height: 30px;">

                                                    <select name="terms_and_condition_id" id="terms_and_condition_id"
                                                        style="width: 100%" class=" selectpicker terms"
                                                        data-live-search="true">
                                                        <option value="">@lang('lang.please_select')</option>
                                                        @foreach ($tac as $key => $item)
                                                        <option value="{{ $key }}">{{ $item }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="tac_description_div mt-2"><span></span></div>
                                            </div>


                                            <div class="col-6 font-responsive mb-4" style="padding: 0;padding-left:2px">
                                                {!! Form::label('commissioned_employees',
                                                __('lang.commissioned_employees'), ['class' => 'label mb-0']) !!}
                                                <div class="" style="background-color: #e6e6e6 ; border: none;
                                            border-radius: 6px;
                                            color: #373737;
                                            box-shadow: 0 8px 6px -5px #bbb ;

                                            width: 100%;
                                            height: 30px;">
                                                    {!! Form::select('commissioned_employees[]', $employees, false, [
                                                    'class' => ' selectpicker terms',
                                                    'style' => 'width:100%',
                                                    'data-live-search' => 'true',
                                                    'multiple',
                                                    'id' => 'commissioned_employees',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-lg-4 hide shared_commission_div">
                                                <div class="i-checks" style="margin-top: 37px;">
                                                    <input id="shared_commission" name="shared_commission"
                                                        type="checkbox" value="1" class="form-control-custom">
                                                    <label for="shared_commission"><strong>
                                                            @lang('lang.shared_commission')
                                                        </strong></label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card">

                                    <div class="d-flex flex-column flex-lg-row mx-auto py-2 mb-1" style="width: 80%">

                                        <div
                                            class="col-lg-6 d-flex justify-content-between align-items-center mb-1 mb-lg-0">
                                            <div class="col-8 table_room_hide d-flex justify-content-between pl-0 height-responsive"
                                                style="background-color: white; border: none;
                                                     border: 1px solid #bbb;
                                                        border-radius: 16px;
                                                        color: white;
                                                        box-shadow: 0 8px 6px -5px #bbb ;
                                                        width: 100%;">
                                                <span
                                                    class="totals-title d-flex justify-content-center align-items-center height-responsive pl-2 pl-lg-2 promotion-padding"
                                                    style="background-color: var(--secondary-color);
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;

                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">{{ __('lang.sales_promotion') }}</span>
                                                <span id="sales_promotion-cost_span"
                                                    style="color: #000; padding-right: 30px;width: 45%"
                                                    class="d-flex justify-content-start align-items-center  height-responsive">0.00</span>
                                                <input type="hidden" id="sales_promotion-cost" value="0">
                                            </div>

                                            <div class="col-4 pl-lg-6  height-responsive" style="width: 100%; ">
                                                @if (auth()->user()->can('sp_module.sales_promotion.view') ||
                                                auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
                                                auth()->user()->can('sp_module.sales_promotion.delete'))
                                                <button type="button" style="background-color: #e6e6e6 ;
                                                    border: none;
                                                border-radius: 16px;
                                                color: #373737;
                                                box-shadow: 0 8px 6px -5px #bbb ;
                                                width: 100%;" class="height-responsive" data-toggle="modal"
                                                    data-target="#discount_modal">@lang('lang.random_discount')</button>
                                                @endif
                                                {{-- <span id="discount">0.00</span> --}}
                                            </div>
                                        </div>


                                        <div class="col-lg-6 pl-lg-6  height-responsive payment-amount table_room_hide d-flex justify-content-between pl-0 height-responsive"
                                            style="background-color: white; border: none;
                                                    border: 1px solid #bbb;
                                                    border-radius: 16px;
                                                    color: white;
                                                    box-shadow: 0 8px 6px -5px #bbb ;
                                                    width: 100%;
                                                    margin-top: 0">

                                            <span
                                                class=" height-responsive d-flex justify-content-center align-items-center"
                                                style="background-color: var(--secondary-color);
                                                width: fit-content;
                                                height: 100%;
                                                border-radius: 16px;
                                                padding: 10px 20px;
                                                margin-bottom: 0;
                                                font-weight: 600;
                                                ">{{ __('lang.grand_total') }}
                                            </span>
                                            <span style="color: #000; padding-right: 30px;width: 45%"
                                                class="d-flex justify-content-start align-items-center  height-responsive final_total_span">0.00</span>

                                        </div>

                                    </div>
                                </div>


                            </div>

                            <div class="col-md-12 main_settings">
                                <div class="row" style="display: none;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" id="final_total" name="final_total" />
                                            <input type="hidden" id="grand_total" name="grand_total" />
                                            <input type="hidden" id="gift_card_id" name="gift_card_id" />
                                            <input type="hidden" id="coupon_id" name="coupon_id">
                                            <input type="hidden" id="total_tax" name="total_tax" value="0.00">
                                            <input type="hidden" id="total_item_tax" name="total_item_tax" value="0.00">
                                            <input type="hidden" id="status" name="status" value="final" />
                                            <input type="hidden" id="total_sp_discount" name="total_sp_discount"
                                                value="0" />
                                            <input type="hidden" id="total_pp_discount" name="total_pp_discount"
                                                value="0" />
                                            <input type="hidden" name="dining_table_id" id="dining_table_id" value="">
                                            <input type="hidden" name="dining_action_type" id="dining_action_type"
                                                value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 table_room_show hide"
                                    style="border-top: 2px solid #e4e6fc; margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <b>@lang('lang.total'): <span class="subtotal">0.00</span></b>
                                            </div>
                                            <div class="row">
                                                <b>@lang('lang.discount'): <span class="discount_span">0.00</span></b>
                                            </div>
                                            <div class="row">
                                                <b>@lang('lang.service'): <span
                                                        class="service_value_span">0.00</span></b>
                                            </div>
                                            <div class="row">
                                                <b>@lang('lang.grand_total'): <span
                                                        class="final_total_span">0.00</span></b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <button type="button" name="action" value="print"
                                                    id="dining_table_print" class="btn mr-2 text-white"
                                                    style="background: orange;">@lang('lang.print')</button>
                                                <button type="button" name="action" value="save" id="dining_table_save"
                                                    class="btn mr-2 text-white btn-success">@lang('lang.save')</button>
                                                <button data-method="cash" style="background: #0082ce" type="button"
                                                    class="btn mr-2 payment-btn text-white" data-toggle="modal"
                                                    data-target="#add-payment" data-backdrop="static"
                                                    data-keyboard="false"
                                                    id="cash-btn">@lang('lang.pay_and_close')</button>
                                                @if (auth()->user()->can('sp_module.sales_promotion.view') ||
                                                auth()->user()->can('sp_module.sales_promotion.create_and_edit') ||
                                                auth()->user()->can('sp_module.sales_promotion.delete'))
                                                <button style="background-color: #d63031" type="button"
                                                    class="btn mr-2 btn-md payment-btn text-white" data-toggle="modal"
                                                    data-target="#discount_modal">@lang('lang.random_discount')</button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button style="background-color: #ff0000; color:#fff !important; " type="button"
                                                class="btn text-white" id="cancel-btn" onclick="return confirmCancel()">
                                                @lang('lang.cancel')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="payment-options row table_room_hide"
                        style=" width:  100%;">

                    </div>

                </div>

                @include('sale::back-end.pos.partials.payment_modal')
                @include('sale::back-end.pos.partials.discount_modal')
                {{-- @include('sale::back-end.pos.partials.tax_modal') --}}
                @include('sale::back-end.pos.partials.coupon_modal')
                @include('sale::back-end.pos.partials.contact_details_modal')
                @include('sale::back-end.pos.partials.weighing_scale_modal')
                @include('sale::back-end.pos.partials.non_identifiable_item_modal')
                @include('sale::back-end.pos.partials.sale_note')

                {!! Form::close() !!}

                <!-- products list -->
                <div
                    class="col-md-3">

                    @include('sale::back-end.pos.partials.right_side')
                </div>

                <!-- recent transaction modal -->
                <div id="recentTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog modal-xl" role="document" style="min-width: 95%;">
                        <div class="modal-content">
                            <div
                                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <h5 class="modal-title">@lang('lang.recent_transactions')
                                    <span class="header-pill"></span>
                                </h5>
                                <button type="button"
                                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                                    data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <span class="position-absolute modal-border"></span>
                            </div>
                            <div class="modal-body ">
                                <div class="col-md-12 modal-filter">
                                    <div
                                        class="row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('start_date', __('lang.start_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('start_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start filter_transactions',
                                                'id' => 'rt_start_date',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('end_date', __('lang.end_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('end_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start filter_transactions',
                                                'id' => 'rt_end_date',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('rt_customer_id', __('lang.customer'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::select('rt_customer_id', $customers, false, [
                                                'class' => 'form-control filter_transactions selectpicker',
                                                'id' => 'rt_customer_id',
                                                'data-live-search' => 'true',
                                                'placeholder' => __('lang.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('rt_method', __('lang.payment_type'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::select('rt_method', $payment_types, request()->method, [
                                                'class' => 'form-control filter_transactions',
                                                'placeholder' => __('lang.all'),
                                                'data-live-search' => 'true',
                                                'id' => 'rt_method',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('rt_created_by', __('lang.cashier'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::select('rt_created_by', $cashiers, false, [
                                                'class' => 'form-control selectpicker filter_transactions',
                                                'id' => 'rt_created_by',
                                                'data-live-search' => 'true',
                                                'placeholder' => __('lang.all'),
                                                ]) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @include('sale::back-end.pos.partials.recent_transactions')
                                </div>
                            </div>

                            <div class="modal-footer mb-3 d-flex justify-content-center align-content-center gap-3">
                                <button type="button" class="col-3 py-1 btn btn-danger"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!-- draft transaction modal -->
                <div id="draftTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog" role="document" style="min-width: 95%;">
                        <div class="modal-content">
                            <div
                                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <h5 class="modal-title  px-2 position-relative d-flex align-items-center"
                                    style="gap: 5px;">@lang('lang.draft_transactions')
                                    <span class="header-pill"></span>
                                </h5>
                                <button type="button"
                                    class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                                    data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <span class="position-absolute modal-border"></span>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12 modal-filter">
                                    <div
                                        class="row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('draft_start_date', __('lang.start_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('start_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'draft_start_date',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('draft_end_date', __('lang.end_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('end_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'draft_end_date',
                                                ]) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @include('sale::back-end.pos.partials.view_draft')
                                </div>
                            </div>

                            <div class="modal-footer mb-3 d-flex justify-content-center align-content-center gap-3">
                                <button type="button" class="col-3 py-1 btn btn-danger"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!-- onlineOrder transaction modal -->
                <div id="onlineOrderTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog" role="document" style="min-width: 95%;">
                        <div class="modal-content">
                            <div
                                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <h5 class="modal-title px-2 position-relative d-flex align-items-center"
                                    style="gap: 5px;">@lang('lang.online_order_transactions')
                                    <span class="header-pill"></span>
                                </h5>
                                <button type="button"
                                    class="close  btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                                    data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <span class="position-absolute modal-border"></span>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12 modal-filter">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('online_order_start_date', __('lang.start_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('start_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'online_order_start_date',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('online_order_end_date', __('lang.end_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('end_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'online_order_end_date',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer mb-3 d-flex justify-content-center align-content-center gap-3">
                                <button type="button" class="col-3 py-1 btn btn-danger"
                                    data-dismiss="modal">@lang('lang.close')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <div id="dining_model" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">
                </div>
                <div id="dining_table_action_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal fade text-left">
                </div>
            </div>
        </div>
</section>

<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
<script src="{{ asset('js/onscan.min.js') }}"></script>
<script src="{{ asset('js/pos.js') }}"></script>
@endsection
