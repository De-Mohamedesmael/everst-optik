@extends('back-end.layouts.app')
@section('title', __('lang.add_new_sell_return'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/products.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.sale-return.index') }}">
            @lang('lang.sell_return')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        {{translate('add_new_sell_return')}}</li>
@endsection
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open([
                            'url' => route('admin.sale-return.store'),
                            'method' => 'post',
                            'files' => true,
                            'class' => 'pos-form',
                            'id' => 'sell_return_form',
                        ]) !!}

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('store_id', __('lang.store'), []) !!}
                                {!! Form::select('store_id', $stores, $sale->store_id, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang.all'),
                                    'data-live-search' => 'true',
                                ]) !!}
                            </div>
                        </div>

                        <input type="hidden" name="default_customer_id" id="default_customer_id"
                               value="@if (!empty($walk_in_customer)) {{ $walk_in_customer->id }} @endif">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    @lang('lang.invoice_no'): {{ $sale->invoice_no }}
                                </div>
                                <div class="col-md-4">
                                    @lang('lang.customer'): {{ $sale->customer->name ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12" style="margin-top: 20px ">
                                    <div class="table-responsive">
                                        <table id="product_table" style="width: 100% " class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th style="width: 30%">{{ __('lang.products') }}</th>
                                                <th style="width: 20%">{{ __('lang.product_code') }}</th>
                                                <th style="width: 15%">{{ __('lang.quantity') }}</th>
                                                <th style="width: 15%">{{ __('lang.returned_quantity') }}</th>
                                                <th style="width: 15%">{{ __('lang.price') }}</th>
                                                <th style="width: 15%">{{ __('lang.discount') }}</th>
                                                <th style="width: 10%">{{ __('lang.sub_total') }}</th>
                                                <th style="width: 20%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @include('sale::back-end.sell_return.partials.product_row', [
                                                'products' => $sale->transaction_sell_lines,
                                            ])
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <th style="text-align: right">@lang('lang.total')</th>
                                                <th><span class="grand_total_span">{{ 0 }}</span>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row" style="display: none;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" id="transaction_id" name="transaction_id"
                                                   value="{{ $sale->id }}" />
                                            <input type="hidden" id="final_total" name="final_total"
                                                   value="{{ 0 }}" />
                                            <input type="hidden" id="grand_total" name="grand_total"
                                                   value="{{ 0 }}" />
                                            <input type="hidden" id="store_pos_id" name="store_pos_id"
                                                   value="{{ $sale->store_pos_id }}" />
                                            <input type="hidden" id="customer_id" name="customer_id"
                                                   value="{{ $sale->customer_id }}" />
                                            <input type="hidden" id="gift_card_id" name="gift_card_id"
                                                   value="{{ $sale->gift_card_id }}" />
                                            <input type="hidden" id="gift_card_amount" name="gift_card_amount"
                                                   value="{{ $sale->transaction_payments->where('method', 'gift_card')->sum('amount') }}" />

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 @if ($sale->delivery_cost_given_to_deliveryman) d-none @endif">
                                {!! Form::label('delivery_cost_actual', __('lang.The_actual_delivery_cost'), []) !!}
                                {!! Form::text('delivery_cost_actual', @num_format($sale->delivery_cost), [
                                    'class' => 'form-control',
                                    'readonly',
                                ]) !!}
                            </div>
                            <div class="col-md-5 @if ($sale->delivery_cost_given_to_deliveryman) d-none @endif">
                                {!! Form::label('delivery_cost', __('lang.Discount_from_the_cost_of_delivery'), []) !!}
                                {!! Form::text('delivery_cost', @num_format($sell_return->delivery_cost ?? 0), [
                                    'class' => 'form-control',
                                    'max' => @num_format($sale->delivery_cost),
                                ]) !!}
                            </div>
                            <div class="@if ($sale->delivery_cost_given_to_deliveryman) col-md-6 @else col-md-2 @endif">
                                {!! Form::hidden('discount_type', $sale->discount_type, ['class' => 'form-control', 'id' => 'discount_type']) !!}
                                {!! Form::hidden('discount_value', $sale->discount_value, ['class' => 'form-control', 'id' => 'discount_value']) !!}

                                {!! Form::label('discount_amount', __('lang.discount'), []) !!}
                                {!! Form::text(
                                    'discount_amount',
                                    !empty($sell_return->discount_amount)
                                        ? @num_format($sell_return->discount_amount)
                                        : @num_format($sale->discount_amount),
                                    ['class' => 'form-control'],
                                ) !!}
                            </div>
                            <div class="@if ($sale->delivery_cost_given_to_deliveryman) col-md-6 @else col-md-1 @endif">
                                {!! Form::label('total_tax', __('lang.tax'), []) !!}
                                {!! Form::text(
                                    'total_tax',
                                    !empty($sell_return->total_tax) ? @num_format($sell_return->total_tax) : @num_format($sale->total_tax),
                                    ['class' => 'form-control'],
                                ) !!}
                                <input type="hidden" name="tax_method" id="tax_method" value="{{ $sale->tax_method }}">
                                <input type="hidden" name="tax_rate" id="tax_rate" value="{{ $sale->tax_rate }}">
                                <input type="hidden" name="tax_type" id="tax_type" value="{{ $sale->tax_type }}">
                            </div>
                        </div>
                        <br>
                        <input type="hidden" name="total_amount_paid" id="total_amount_paid"
                               value="{{ $sale->transaction_payments->sum('amount') }}">
                        <div class="row">
                            <div class="col-md-12">
                                @if (!empty($sell_return) && $sell_return->transaction_payments->count() > 0)

                                        @include('addstock::back-end.transaction_payment.partials.payment_form', [
                                            'payment' => $sell_return->transaction_payments->first(),
                                        ])

                                @else
                                    @include('addstock::back-end.transaction_payment.partials.payment_form')
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>@lang('lang.notes')</label>
                                <textarea rows="3" class="form-control" name="notes" id="notes">{{ !empty($sell_return) ? $sell_return->notes : '' }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('files', __('lang.files') . ':', []) !!} <br>
                                    <input type="file" name="files[]" id="files" multiple>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="sbumit" class="btn btn-primary save-btn">@lang('lang.save')</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- This will be printed -->
    <section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
    <script src="{{ asset('js/sell_return.js') }}"></script>
    <script>
        $(document).ready(function() {
            calculate_sub_totals()
        })

        $('#delivery_cost').change(function() {
            let max = parseFloat($(this).attr("max"));
            let value_qty = parseFloat($(this).val());
            if (max < value_qty) {
                $(this).val(max);
                swal(
                    "warning",
                    "Max quantity is " + " :" + max,
                    "warning"
                );
            }
            calculate_sub_totals();
        });
    </script>
@endsection
