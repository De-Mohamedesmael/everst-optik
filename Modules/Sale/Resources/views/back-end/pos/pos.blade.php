@extends('back-end.layouts.app')
@section('title', __('lang.pos'))
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ url('front/css/pos.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('front/css/pos-modals.css') }}">
    <style>
        :root {
            --secondary-color: #578981;
        }
        .payment-options button {
            color: #fff;
        }
        .input-group-btn button.btn {
            padding: 0 !important;
            margin: 0 !important;
        }
        .nav-tabs .nav-item .nav-link.active {
            border-color: transparent;
            border-bottom: 2px solid var(--secondary-color);
            background: var(--secondary-color);
            color: #fff;
        }

         .lens-vu-price {
             text-align: center;
             color: #06312a;
             padding: 5px;
         }
        .lens-vu {
            padding: 5px 0;
        }
        .lens-vu-item {
            font-size: 11px;
            color: #034137;
            padding: 1px 0;
        }
        .card {
            box-shadow: none !important;
        }
        button#submit-btn-pay {
            border-radius: 6px !important;
        }
        .toast {
            background-color: #030303;
        }
        .toast-info {
            background-color: #3276b1;
        }
        .toast-info2 {
            background-color: #2f96b4;
        }
        .toast-error {
            background-color: #bd362f;
        }
        .toast-success {
            background-color: #51a351;
        }
        .toast-warning {
            background-color: #f89406;
        }


        label#customer_id-error {
            position: absolute;
            bottom: -35px;
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


                                        <input type="hidden" name="tax_id_hidden" id="tax_id_hidden" value="">
                                        <input type="hidden" name="tax_method" id="tax_method" value="">
                                        <input type="hidden" name="tax_rate" id="tax_rate" value="0">
                                        <input type="hidden" name="tax_type" id="tax_type" value="">




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
                                            {!! Form::select('customer_id', $customers,  null, [
                                            'class' => 'selectpicker ',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'id' => 'customer_id',
                                            'placeholder' => translate('select_customer'),
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






                                    <div class="col-lg-2 mb-2 mb-lg-0 " style="display: none">

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

                                    <div class="col-lg-3 mb-2 mb-lg-0">
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
                                    <div class="col-lg-2 mb-2 mb-lg-0">
                                        <div style="width: 100%"
                                             class="col-12  p-0 input-group my-group d-flex flex-row justify-content-center height-responsive">
                                            <button type="button" style="background-color: rgba(31,103,92,0.35) ;
                                        border: none;
                                        border-radius: 16px;
                                        color: #1f675c;
                                        box-shadow: 0 8px 6px -5px #bbb;
                                        padding: 10px 6px;
                                        width: 100%;
                                        font-size: 18px;
                                        font-weight: 700;"
                                                    class="height-responsive d-flex justify-content-center align-items-center"
                                                    data-toggle="modal"
                                                    data-target="#order_lens_modal">{{translate('order_lens')}}
                                            </button>
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
                         style=" width: @if (session('system_mode') == 'pos' || session('system_mode') == 'garments' || session('system_mode') == 'supermarket') 100%; @else 50%; @endif">
                        <div class="col-md-12 flex-wrap d-flex justify-content-start justify-content-lg-center align-items-center mb-3  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif"
                             style="font-size: 16px;font-weight: 600">
                            <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button data-method="cash"
                                        style="background: var(--secondary-color);display: flex;justify-content: center;gap: 10px;"
                                        type="button" class="btn btn-custom w-75 pos-button payment-btn" data-toggle="modal"
                                        data-target="#add-payment" data-backdrop="static" data-keyboard="false"
                                        id="cash-btn">
                                    <div style="width: 18px">
                                        <img class="w-100 h-100"
                                             src="{{ asset('front/images/icons Png/Icon awesome-money-check-alt.png') }}"
                                             alt="Pay">
                                    </div>
                                    @lang('lang.pay')
                                </button>
                            </div>
                            <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button data-method="cash" style="background: var(--secondary-color)" type="button"
                                        class="btn w-75 pos-button btn-custom" id="quick-pay-btn"><i
                                        class="fa fa-money"></i>
                                    @lang('lang.quick_pay')</button>
                            </div>
                            <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button data-method="pay-later"
                                        style="background-color: var(--secondary-color);display: flex;justify-content: center;gap: 10px;"
                                        type="button" class="btn  w-75 pos-button btn-custom" id="pay-later-btn">
                                    <div style="width: 18px">
                                        <img class="w-100 h-100"
                                             src="{{ asset('front/images/icons Png/Icon material-watch-later.png') }}"
                                             alt="Pay">
                                    </div>
                                    @lang('lang.pay_later')
                                </button>
                            </div>
                            <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button style="background-color: #ff0000;" type="button"
                                        class="btn  w-75 pos-button btn-custom" id="cancel-btn"
                                        onclick="return confirmCancel()"><i class="fa fa-close"></i>
                                    @lang('lang.cancel')</button>
                            </div>
                           <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button
                                    style="background-color: var(--secondary-color);display: flex;justify-content: center;gap: 10px;"
                                    type="button" class="btn  w-75 pos-button btn-custom" id="recent-transaction-btn">
                                    <div style="width: 18px">
                                        <img class="w-100 h-100"
                                             src="{{ asset('front/images/icons Png/Icon material-timer.png') }}"
                                             alt="Pay">
                                    </div>
                                    @lang('lang.recent_transactions')
                                </button>
                            </div>

                            <div
                                class="col-md-6 col-lg-2 mb-3 mb-lg-1 d-flex justify-content-center align-items-center">
                                <button data-method="lens"
                                        style="background-color: var(--secondary-color);display: flex;justify-content: center;gap: 10px;"
                                        type="button" class="btn  w-75 pos-button  btn-custom" id="view-lens-btn"
                                        data-href="#{{-- --}}">
                                    <div style="width: 18px">
                                        <img class="w-100 h-100"
                                             src="{{ asset('front/images/icons Png/Icon awesome-flag.png') }}" alt="Pay">
                                    </div>
                                    {{translate('view_lenses_order')}}
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

                @include('sale::back-end.pos.partials.payment_modal')
                @include('sale::back-end.pos.partials.discount_modal')
                @include('sale::back-end.pos.partials.contact_details_modal')
                @include('sale::back-end.pos.partials.weighing_scale_modal')
                @include('sale::back-end.pos.partials.non_identifiable_item_modal')
                @include('sale::back-end.pos.partials.sale_note')

                {!! Form::close() !!}
                @include('sale::back-end.pos.partials.order_lens')

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
                <div id="lensTransaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    class="modal text-left">

                    <div class="modal-dialog" role="document" style="min-width: 95%;">
                        <div class="modal-content">

                            <div
                                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <h5 class="modal-title  px-2 position-relative d-flex align-items-center"
                                    style="gap: 5px;">{{translate('lens_transactions')}}
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
                                                {!! Form::label('lens_start_date', __('lang.start_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('start_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'lens_start_date',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4 px-5">
                                            <div class="form-group">
                                                {!! Form::label('lens_end_date', __('lang.end_date'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                                text-start',
                                                ]) !!}
                                                {!! Form::text('end_date', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                                text-start',
                                                'id' => 'lens_end_date',
                                                ]) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    @include('sale::back-end.pos.partials.view_lens_order')
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

            </div>
        </div>
    </div>
</section>


<!-- This will be printed -->
@endsection

@section('javascript')
<script src="{{ asset('js/onscan.min.js') }}"></script>
<script src="{{ asset('js/pos.js') }}"></script>
<script>
    $(document).on("click", "#btn-lens-add", function (e) {
        e.preventDefault();
        formData = $('#orderLensFormCreate').serializeArray();
        $.ajax({
            type: "POST",
            url: "{{route('admin.pos.SaveLens')}}",
            data: formData,
            success: function (response) {
                if (response.success) {
                    var product_id = $('#lens_id').val();
                    var KeyLens = response.KeyLens;
                    get_label_product_row(product_id,null,1,0,null,KeyLens);
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: response.msg,
                        icon: 'error',
                    });
                }
            },
            error: function (response) {
                if (!response.success) {

                        Swal.fire({
                            title: 'Error',
                            text: response.msg,
                            icon: 'error',
                        });


                }
            },
        });

    });
</script>
@endsection
