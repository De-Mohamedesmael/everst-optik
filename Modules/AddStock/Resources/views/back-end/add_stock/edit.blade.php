@php use Modules\Product\Entities\ProductStore; @endphp
@extends('back-end.layouts.app')
@section('title', __('lang.edit_stock'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.add-stock.index') }}">
            {{translate('view_all_added_stocks')}}</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.edit_stock')</li>
@endsection
@section('content')
    <section class="forms px-3 py-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">

                    {!! Form::open([
                        'url' => route('admin.add-stock.update', $add_stock->id),
                        'method' => 'put',
                        'id' => 'edit_stock_form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="row_count" id="row_count"
                        value="{{ $add_stock->add_stock_lines->count() }}">
                    <input type="hidden" name="is_add_stock" id="is_add_stock" value="1">
                    <input type="hidden" name="is_raw_material" id="is_raw_material"
                        value="{{ $add_stock->is_raw_material }}">
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        {!! Form::label('store_id', __('lang.store') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('store_id', $stores, $add_stock->store_id, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'required',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        <div
                                            class="d-flex align-items-center @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                            {!! Form::label('po_no', __('lang.po_no'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            <div style="width: 30px;height: 30px;">
                                                <img class="w-100 h-100 dripicons-question"
                                                    src="{{ asset('front/images/icons/warning.png') }}" alt="warning!"
                                                    data-toggle="tooltip" title="@lang('lang.po_no_add_stock_info')">
                                            </div>
                                        </div>
                                        {!! Form::select('po_no', $po_nos, $add_stock->purchase_order_id, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        {!! Form::label('status', __('lang.status') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select(
                                            'status',
                                            ['received' => __('lang.received'), 'partially_received' => __('lang.partially_received')],
                                            $add_stock->status,
                                            [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'required',
                                                'style' => 'width: 80%',
                                                'placeholder' => __('lang.please_select'),
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-4 px-5">
                                    <div class="form-group">
                                        <input type="hidden" name="exchange_rate" id="exchange_rate" value="1">
                                        <input type="hidden" name="default_currency_id" id="default_currency_id"
                                            value="{{ !empty($add_stock->default_currency_id) ? $add_stock->default_currency_id : App\Models\System::getProperty('currency') }}">
                                        {!! Form::label('paying_currency_id', __('lang.paying_currency') . ':', []) !!}
                                        {!! Form::select(
                                            'paying_currency_id',
                                            $exchange_rate_currencies,
                                            !empty($add_stock->paying_currency_id)
                                                ? $add_stock->paying_currency_id
                                                : App\Models\System::getProperty('currency'),
                                            ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'required'],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="row justify-content-center align-items-center mb-3" style="gap: 10px">
                                <div class="col-md-8">
                                    <div class="search-box modal-input input-group">
                                        <button type="button"
                                            class="btn h-100 d-flex justify-content-center align-items-center"
                                            style="background-color: var(--complementary-color-1)" id="search_button"><i
                                                class="fa fa-search text-white"></i></button>
                                        <input type="text" name="search_product" id="search_product"
                                            placeholder="@lang('lang.enter_product_name_to_print_labels')" style="background-color: transparent"
                                            class="form-control  h-100 ui-autocomplete-input" autocomplete="off">
                                        <button type="button"
                                            class="btn text-black d-flex justify-content-center align-items-center btn-modal h-100"
                                            style="background-color: transparent"
                                            data-href="{{ route('admin.products.create') }}?quick_add=1"
                                            data-container=".view_modal"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    @include('product::back-end.products.partial.product_selection')
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-condensed" id="product_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width: 7%" class="col-sm-8">@lang('lang.image')</th>
                                                <th style="width: 10%" class="col-sm-8">@lang('lang.products')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.sku')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.quantity')</th>
                                                <th style="width:12%" class="col-sm-4">@lang('lang.purchase_price')</th>
                                                <th style="width:12%" class="col-sm-4">@lang('lang.selling_price')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.sub_total')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.new_stock')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.change_current_stock')</th>
                                                <th style="width: 10%" class="col-sm-4">@lang('lang.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($add_stock->add_stock_lines as $product)
                                                <tr class="product_row">
                                                    <td class="row_number"></td>
                                                    <td><img src="@if (!empty($product->product) && !empty($product->product->getFirstMediaUrl('products'))) {{ $product->product->getFirstMediaUrl('products') }}@else{{ asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) }} @endif"
                                                            alt="photo" width="50" height="50"></td>
                                                    <td>
                                                        <h6 style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-center">

                                                                {{ !empty($product->product) ? $product->product->name : __('lang.deleted') }}

                                                            <input type="hidden"
                                                                name="add_stock_lines[{{ $loop->index }}][add_stock_line_id]"
                                                                value="{{ $product->id }}">
                                                            <input type="hidden"
                                                                name="add_stock_lines[{{ $loop->index }}][product_id]"
                                                                value="{{ $product->product_id }}">

                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6 style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-center">
                                                            {{ !empty($product->product) ? $product->product->sku : '' }}
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control quantity  quantity_{{ $loop->index }}  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                                            min="{{ 1 }}"
                                                            name="add_stock_lines[{{ $loop->index }}][quantity]" required
                                                            value="@if (isset($product->quantity)) {{ preg_match('/\.\d*[1-9]+/', (string) $product->quantity) ? $product->quantity : @num_format($product->quantity) }}@else{{ 1 }} @endif"
                                                            index_id="{{ $loop->index }}">
                                                    </td>

                                                    <td>
                                                        <div style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-start">
                                                            <span class="text-secondary font-weight-bold pr-1">*</span>
                                                            <input type="text"
                                                                class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif purchase_price purchase_price_{{ $loop->index }}"
                                                                name="add_stock_lines[{{ $loop->index }}][purchase_price]"
                                                                required index_id="{{ $loop->index }}"
                                                                value="@if (isset($product->purchase_price)) {{ @num_format($product->purchase_price) }}@else{{ 0 }} @endif">
                                                            <input class="final_cost" type="hidden"
                                                                name="add_stock_lines[{{ $loop->index }}][final_cost]"
                                                                value="@if (isset($product->final_cost)) {{ @num_format($product->final_cost) }}@else{{ 0 }} @endif">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-start">
                                                            <span class="text-secondary font-weight-bold pr-1">*</span>
                                                            <input type="text"
                                                                class="form-control  modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif selling_price selling_price_{{ $loop->index }}"
                                                                name="add_stock_lines[{{ $loop->index }}][selling_price]"
                                                                required index_id="{{ $loop->index }}"
                                                                value="@if (isset($product->sell_price)) {{ @num_format($product->sell_price) }}@else{{ 0 }} @endif">

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-center">
                                                            <span
                                                                class="sub_total_span">{{ number_format($product->sub_total, 2,',','.') }}</span>
                                                        </h6>
                                                        <input type="hidden" class="form-control sub_total"
                                                            name="add_stock_lines[{{ $loop->index }}][sub_total]"
                                                            value="{{ number_format($product->sub_total, 2,',','.') }}">

                                                    </td>
                                                    @php
                                                        $current_stock = ProductStore::where(
                                                            'product_id',
                                                            $product->product_id,
                                                        )
                                                            ->where('store_id', $add_stock->store_id)
                                                            ->sum('qty_available');
                                                    @endphp
                                                    <td>
                                                        <input type="hidden" name="current_stock" class="current_stock"
                                                            value="@if (isset($current_stock)) {{ number_format($current_stock,\Modules\Setting\Entities\System::getProperty('numbers_length_after_dot'),',','.') }} @else{{ 0 }} @endif">
                                                        <h6 style="width: 100%;height: 100%;"
                                                            class="d-flex justify-content-center align-items-center">
                                                            <span class="current_stock_text">
                                                                @if (isset($current_stock))
                                                                    {{ number_format($current_stock, \Modules\Setting\Entities\System::getProperty('numbers_length_after_dot'),',','.') }}@else{{ 0 }}
                                                                @endif
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <div class="i-checks toggle-pill-color flex-col-centered">
                                                            <input name="stock_pricechange"
                                                                id="active" type="checkbox" class=""
                                                                value="1">
                                                            <label for="active"></label>
                                                            </div>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm p-1 mb-1 btn-danger  remove_row"
                                                            data-index="{{ $loop->index }}"><i
                                                                class="fa fa-times"></i></button>

                                                    </td>
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-between">
                                <h4 class="col-md-3">@lang('lang.items_count'): <span class="items_count_span"
                                        style="margin-right: 15px;">{{ $add_stock->add_stock_lines->count() }}</span>
                                </h4>
                                <h4 class="col-md-3">
                                    @lang('lang.items_quantity'): <span class="items_quantity_span"
                                        style="margin-right: 15px;">{{ $add_stock->add_stock_lines->sum('quantity') }}</span>
                                </h4>

                                <div class="col-md-3">

                                    <h3> @lang('lang.total'): <span
                                            class="final_total_span">{{ @num_format($add_stock->final_total) }}</span>
                                    </h3>
                                    <input type="hidden" name="final_total" id="final_total"
                                        value="{{ $add_stock->final_total }}">
                                    <input type="hidden" name="grand_total" id="grand_total"
                                        value="{{ $add_stock->grand_total }}">

                                </div>
                            </div>

                            <div
                                class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                                <h6 class="mb-0">
                                    @lang('lang.more_info')
                                    <span class="header-pill"></span>
                                </h6>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body p-2">
                                    <div
                                        class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('files', __('lang.files'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                <input
                                                    class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                                    type="file" name="files[]" id="files" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('invoice_no', __('lang.invoice_no'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('invoice_no', $add_stock->invoice_no, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' => __('lang.invoice_no'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('other_expenses', __('lang.other_expenses'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('other_expenses', @num_format($add_stock->other_expenses), [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' => __('lang.other_expenses'),
                                                    'id' => 'other_expenses',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('discount_amount', __('lang.discount'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('discount_amount', @num_format($add_stock->discount_amount), [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' => __('lang.discount'),
                                                    'id' => 'discount_amount',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('other_payments', __('lang.other_payments'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('other_payments', @num_format($add_stock->other_payments), [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' => __('lang.other_payments'),
                                                    'id' => 'other_payments',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('source_type', __('lang.source_type'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::select(
                                                    'source_type',
                                                    ['admin' => __('lang.admin'), 'pos' => __('lang.pos'), 'store' => __('lang.store'), 'safe' => __('lang.safe')],
                                                    $add_stock->source_type,
                                                    [
                                                        'class' => 'selectpicker form-control',
                                                        'data-live-search' => 'true',
                                                        'required',
                                                        'style' => 'width: 80%',
                                                        'placeholder' => __('lang.please_select'),
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('source_of_payment', __('lang.source_of_payment'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                <select name="source_id" id="source_id" class="selectpicker form-control"
                                                    data-live-search ="true" style="width: 80%" required>
                                                    @foreach ($admins as $key => $val)
                                                        @if ($add_stock->source_id == $key)
                                                            <option value="{{ $key }}" selected>
                                                                {{ $val }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $key }}">{{ $val }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                {{-- {!! Form::select('source_id', $admins, $add_stock->source_id, ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'required', 'style' => 'width: 80%', 'placeholder' => __('lang.please_select'), 'id' => 'source_id', 'required']) !!} --}}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('transaction_date', __('lang.date') . '*', [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('transaction_date', @format_date($add_stock->transaction_date), [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker',
                                                    'required',
                                                    'placeholder' => __('lang.date'),
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('payment_status', __('lang.payment_status') . '*', [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::select('payment_status', $payment_status_array, $add_stock->payment_status, [
                                                    'class' => 'selectpicker form-control',
                                                    'data-live-search' => 'true',
                                                    'required',
                                                    'style' => 'width: 80%',
                                                    'id' => 'payment_status',
                                                    'placeholder' => __('lang.please_select'),
                                                ]) !!}
                                            </div>
                                        </div>

                                        @php
                                            $transaction_payment = $add_stock->transaction_payments->first();
                                        @endphp
                                        <div class="col-md-3 payment_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('amount', __('lang.amount') . '*', [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('amount', !empty($transaction_payment) ? @num_format($transaction_payment->amount) : 0, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'placeholder' => __('lang.amount'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <input type="hidden" name="transaction_payment_id"
                                            value="@if (!empty($transaction_payment)) {{ $transaction_payment->id }} @endif">
                                        <div class="col-md-3 payment_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('method', __('lang.payment_type') . '*', [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::select(
                                                    'method',
                                                    $payment_type_array,
                                                    !empty($transaction_payment) ? $transaction_payment->method : null,
                                                    [
                                                        'class' => 'selectpicker form-control',
                                                        'data-live-search' => 'true',
                                                        'required',
                                                        'style' => 'width: 80%',
                                                        'placeholder' => __('lang.please_select'),
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-3 payment_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('paid_on', __('lang.payment_date'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text(
                                                    'paid_on',
                                                    !empty($transaction_payment) ? @format_date($transaction_payment->paid_on) : @format_date(date('Y-m-d')),
                                                    ['class' => 'form-control datepicker', 'placeholder' => __('lang.payment_date')],
                                                ) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-3 payment_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('upload_documents', __('lang.upload_documents'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                <input
                                                    class="form-control modal-input m-auto @if (app()->isLocale('ar')) text-end @else  text-start @endif"
                                                    type="file" name="upload_documents[]" id="upload_documents"
                                                    multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-3 not_cash_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('ref_number', __('lang.ref_number'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('ref_number', !empty($transaction_payment) ? $transaction_payment->ref_number : null, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start not_cash',
                                                    'placeholder' => __('lang.ref_number'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3 not_cash_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('bank_deposit_date', __('lang.bank_deposit_date'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text(
                                                    'bank_deposit_date',
                                                    !empty($transaction_payment) ? @format_date($transaction_payment->bank_deposit_date) : @format_date(date('Y-m-d')),
                                                    [
                                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start not_cash datepicker',
                                                        'placeholder' => __('lang.bank_deposit_date'),
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3 not_cash_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('bank_name', __('lang.bank_name'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('bank_name', !empty($transaction_payment) ? $transaction_payment->bank_name : null, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start not_cash',
                                                    'placeholder' => __('lang.bank_name'),
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div
                                            class="col-md-3 due_amount_div @if ($add_stock->transaction_payments->sum('amount') == $add_stock->final_total) hide @endif">
                                            <label
                                                class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                                                for="due_amount">@lang('lang.due')<span
                                                    class="due_amount_span">{{ @num_format($add_stock->final_total - $add_stock->transaction_payments->sum('amount')) }}</span></label>
                                        </div>

                                        <div class="col-md-3 due_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('due_date', __('lang.due_date'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::text('due_date', !empty($add_stock->due_date) ? @format_date($add_stock->due_date) : null, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start  datepicker',
                                                    'placeholder' => __('lang.due_date'),
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-3 due_fields hide">
                                            <div class="form-group">
                                                {!! Form::label('notify_before_days', __('lang.notify_before_days'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}

                                                {!! Form::text('notify_before_days', $add_stock->notify_before_days, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start ',
                                                    'placeholder' => __('lang.notify_before_days'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('notes', __('lang.notes'), [
                                                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                                ]) !!}
                                                {!! Form::textarea('notes', $add_stock->notes, [
                                                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                    'rows' => 3,
                                                ]) !!}
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row my-2 justify-content-center align-items-center">
                                        <div class="col-md-4 w-25">
                                            <button type="btn" name="submit" id="submit-edit-save" value="save"
                                                class="btn btn-primary py-1 w-100 pull-right btn-flat submit">@lang('lang.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>


    </section>
@endsection

@section('javascript')
    <script src="{{ asset('js/add_stock.js') }}"></script>
    <script src="{{ asset('js/product_selection.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '#add-selected-btn', function() {
            $('#select_products_modal').modal('hide');
            $.each(product_selected, function(index, value) {
                get_label_product_row(value.product_id, value.variation_id);
            });
            product_selected = [];
            product_table.ajax.reload();
        })
        @if (!empty($product_id) && !empty($variation_id))
            $(document).ready(function() {
                get_label_product_row({{ $product_id }}, {{ $variation_id }});
            })
        @endif
        $('#po_no').change(function() {
            let po_no = $(this).val();

            if (po_no) {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-purchase-order-details/' + po_no,
                    data: {},
                    contentType: 'html',
                    success: function(result) {
                        $("table#product_table tbody").empty().append(result);
                        calculate_sub_totals()
                    },
                });
            }
        });
        $(document).on("click", '#submit-btn-add-products', function(e) {
            e.preventDefault();
            var sku = $('#sku').val();
            if ($("#products-form-quick-add").valid()) {
                tinyMCE.triggerSave();
                $.ajax({
                    type: "POST",
                    url: "/product",
                    data: $("#products-form-quick-add").serialize(),
                    success: function(response) {
                        if (response.success) {
                            swal("Success", response.msg, "success");;
                            $("#search_product").val(sku);
                            $('input#search_product').autocomplete("search");
                            $('.view_modal').modal('hide');
                        }
                    },
                    error: function(response) {
                        if (!response.success) {
                            swal("Error", response.msg, "error");
                        }
                    },
                });
            }
        });
        $(document).on("change", "#category_id", function() {
            $.ajax({
                method: "get",
                url: "/category/get-sub-category-dropdown?category_id=" +
                    $("#category_id").val(),
                data: {},
                contentType: "html",
                success: function(result) {
                    $("#sub_category_id").empty().append(result).change();
                    $("#sub_category_id").selectpicker("refresh");

                    if (sub_category_id) {
                        $("#sub_category_id").selectpicker("val", sub_category_id);
                    }
                },
            });
        });

        //payment related script
        $(document).ready(function() {
            var payment_status = $("#payment_status").val();
            if (payment_status === 'paid' || payment_status === 'partial') {
                $('.not_cash_fields').addClass('hide');
                $('#method').change();
                $('#method').attr('required', true);
                $('#paid_on').attr('required', true);
                $('.payment_fields').removeClass('hide');

            } else {
                $('.payment_fields').addClass('hide');
            }
            if (payment_status === 'pending' || payment_status === 'partial') {
                $('.due_fields').removeClass('hide');
            } else {
                $('.due_fields').addClass('hide');
            }
            if (payment_status === 'pending') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
                $('#method').attr('required', false);
                $('#paid_on').attr('required', false);
            } else {
                $('#method').attr('required', true);
            }
            if (payment_status === 'paid') {
                $('.due_fields').addClass('hide');
            }
        })
        $('#payment_status').change(function() {
            var payment_status = $(this).val();

            if (payment_status === 'paid' || payment_status === 'partial') {
                $('.not_cash_fields').addClass('hide');
                $('#method').change();
                $('#method').attr('required', true);
                $('#paid_on').attr('required', true);
                $('.payment_fields').removeClass('hide');
            } else {
                $('.payment_fields').addClass('hide');
            }
            if (payment_status === 'pending' || payment_status === 'partial') {
                $('.due_fields').removeClass('hide');
            } else {
                $('.due_fields').addClass('hide');
            }
            if (payment_status === 'pending') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
                $('#method').attr('required', false);
                $('#paid_on').attr('required', false);
            } else {
                $('#method').attr('required', true);
            }
            if (payment_status === 'paid') {
                $('.due_fields').addClass('hide');
            }
        })
        $('#method').change(function() {
            var method = $(this).val();

            if (method === 'cash') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
            } else {
                $('.not_cash_fields').removeClass('hide');
                $('.not_cash').attr('required', true);
            }
        });

        $(document).ready(function() {
            $.ajax({
                method: 'get',
                url: '/add-stock/get-source-by-type-dropdown/{{ $add_stock->source_type }}',
                data: {},
                success: function(result) {
                    // console.log(result);
                    $("#source_id").empty().append(result);
                    $("#source_id").selectpicker("refresh");

                    var sourceId = {{ $add_stock->source_id }};
                    $("#source_id").val(sourceId);
                    $("#source_id").selectpicker("refresh");
                },
            });
            // $('#payment_status').change();
            // $('#source_type').change();
        })
        $('#source_type').change(function() {
            if ($(this).val() !== '') {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-source-by-type-dropdown/' + $(this).val(),
                    data: {},
                    success: function(result) {
                        $("#source_id").empty().append(result);
                        $("#source_id").selectpicker("refresh");
                    },
                });
            }
        });

        $(document).on('change', '.expiry_date', function() {
            if ($(this).val() != '') {
                let tr = $(this).parents('tr');
                tr.find('.days_before_the_expiry_date').val(15);
            }
        })
        $(document).ready(function() {
            $('form#edit_stock_form').submit(function() {
                $('.quantity').each(function() {
                    var inputValue = $(this).val();
                    var sanitizedValue = inputValue.replace(/,/g, '');
                    sanitizedValue = number_format(floatval(sanitizedValue), 2,',','.');
                    $(this).val(sanitizedValue);
                });
            });
        });
    </script>

    <script>
        function showDetails(i) {
            if ($(`#row_details_${i}`).is(":visible")) {
                $(`#row_details_${i}`).hide()
            } else {
                $(`#row_details_${i}`).show()
            }

            if ($(`#row_batch_details_${i}`).is(":visible")) {
                $(`#row_batch_details_${i}`).hide()
            } else {
                $(`#row_batch_details_${i}`).show()
            }
            $(`#arrow_${i}`).toggleClass("fa-arrow-down fa-arrow-up")
        }
    </script>
@endsection
