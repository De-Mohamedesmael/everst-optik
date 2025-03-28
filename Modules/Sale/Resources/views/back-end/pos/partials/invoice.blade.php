<style>
    @media print {
        * {
            font-size: {{$font}};
            line-height: {{$line_height1}};
            font-family: 'Times New Roman';
        }

        td,
        th {
            padding: 2px 0;
        }

        .hidden-print {
            display: none !important;
        }

        @page {
            margin: 0;
        }

        body {
            margin: 0.5cm;
            margin-bottom: 1.6cm;
        }

        .page {
            position: absolute;
            top: 0;
            right: 0;
        }

        #header_invoice_img {
            max-width: 80mm;
        }
    }

    #receipt_section * {
        font-size: 14px;
        line-height: {{$line_height2}};
        font-family: 'Ubuntu', sans-serif;
        text-transform: capitalize;
        color: black !important;
    }

    #receipt_section .btn {
        padding: 7px 10px;
        text-decoration: none;
        border: none;
        display: block;
        text-align: center;
        margin: 7px;
        cursor: pointer;
    }

    #receipt_section .btn-info {
        background-color: #999;
        color: #FFF;
    }

    #receipt_section .btn-primary {
        background-color: #6449e7;
        color: #FFF;
        width: 100%;
    }

    #receipt_section td,
    #receipt_section th,
    #receipt_section tr,
    #receipt_section table {
        border-collapse: collapse;
    }

    #receipt_section tr {
        border-bottom: 1px dotted #ddd;
    }

    #receipt_section td,
    #receipt_section th {
        padding: 7px 0;
        width: 50%;
    }

    #receipt_section table {
        width: 100%;
    }

    #receipt_section tfoot tr th:first-child {
        text-align: left;
    }

    .centered {
        text-align: center;
        align-content: center;
    }

    small {
        font-size: 11px;
    }
</style>
@php
if (empty($invoice_lang)) {
    $invoice_lang = request()
        ->session()
        ->get('language');
}
@endphp
<div style="max-width:350px;margin:0 auto; padding: 0px 15px; color: black !important;">

    <div id="receipt-data">
        <div class="centered">
            @include('back-end.layouts.partials.print_header')

            <p>{{ $transaction->store->name }}
                {{ $transaction->store->location }}</p>
            <p>{{ $transaction->store->phone_number }} </p>

        </div>
        <div style="width: 70%; float:left; font-weight: bold;">
            <p>@lang('lang.date', [], $invoice_lang): {{ $transaction->transaction_date }}<br>
                @lang('lang.reference', [], $invoice_lang): {{ $transaction->invoice_no }}<br>
                @if (!empty($transaction->customer) && $transaction->customer->is_default == 0)
                    {{ $transaction->customer->name }} <br>
                    {{ $transaction->customer->address }} <br>
                    {{ $transaction->customer->mobile_number }} <br>
                @endif
                @if (!empty($transaction->sale_note))
                    {{ $transaction->sale_note }} <br>
                @endif
            </p>

        </div>

        <div class="table_div" style=" padding: 0 7px; width:100%; height:100%;">
            <table style="margin: 0 auto; text-align: center !important">
                <thead>
                    <tr>
                        <th style="width: 30%; padding: 0 50px !important;">@lang('lang.item', [], $invoice_lang) </th>
                        @if (empty($print_gift_invoice))
                            <th style="width: 20%; text-align:center !important;"> @lang('lang.price', [], $invoice_lang)
                            </th>
                        @endif
                        <th style="width: 20%; text-algin: center;">@lang('lang.qty', [], $invoice_lang) </th>
                        @if (empty($print_gift_invoice))
                            <th style="width: 30%; text-algin: center;">@lang('lang.amount', [], $invoice_lang) </th>
                        @endif
                        @if ($transaction->transaction_sell_lines->where('product_discount_type', '!=', 'surplus')->whereNotNull('discount_category')->sum('product_discount_amount') > 0)
                        <th style="width: 20%; text-algin: center;">@lang('lang.category_discount', [], $invoice_lang) </th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach ($transaction->transaction_sell_lines as $line)
                        <tr>
                            <td style="width: 30%;font-size:{{$data_font}}">

                                        {{ $line->product->translated_name($line->product->id, $invoice_lang) }}
                            </td>
                            @if (empty($print_gift_invoice))
                                <td style="text-align:center !important;vertical-align:bottom; width: 20%;font-size:{{$data_font}}">
                                    {{ @num_format($line->sell_price)  }}</td>
                            @endif
                            <td style="text-align:center;vertical-align:bottom; width: 20%;font-size:{{$data_font}}">
                                {{ preg_match('/\.\d*[1-9]+/', (string)$line->quantity) ? $line->quantity : @num_format($line->quantity)  }}</td>
                            @if (empty($print_gift_invoice))
                                <td style="text-align:center;vertical-align:bottom; width: 30%;font-size:{{$data_font}}">
                                    @if ($line->product_discount_type != 'surplus')
                                        {{@num_format(($line->sub_total + $line->product_discount_amount))  }}
                                    @else
                                        {{ @num_format($line->sub_total)}}
                                    @endif
                                </td>
                            @endif
                            @if(!empty($line->discount_category) && !empty($line->product_discount_amount) && $line->product_discount_amount>0)
                            <td style="text-align:center;vertical-align:bottom; width: 20%;font-size:{{$data_font}}">({{@num_format($line->product_discount_amount) }})</td>
                            @endif
                        </tr>

                    @endforeach
                </tbody>
                @if (empty($print_gift_invoice))
                    <tfoot>
                        <tr>
                            <th style="font-size:20px;" colspan="3">@lang('lang.total', [], $invoice_lang)</th>
                            <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                {{ @num_format($transaction->grand_total + $transaction->transaction_sell_lines->where('product_discount_type', '!=', 'surplus')->sum('product_discount_amount')) }}
                                {{ $transaction->received_currency->symbol }}
                            </th>
                        </tr>
                        @if ($transaction->transaction_sell_lines->where('product_discount_type', '!=', 'surplus')->sum('product_discount_amount') > 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.discount', [], $invoice_lang)</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->transaction_sell_lines->where('product_discount_type', '!=', 'surplus')->sum('product_discount_amount')) }}
                                    {{ $transaction->received_currency->symbol }}
                                </th>
                            </tr>





                        @endif
                        @if ($transaction->total_item_tax != 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.tax', [], $invoice_lang)</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->total_item_tax) }}
                                    {{ $transaction->received_currency->symbol }}
                                </th>
                            </tr>
                        @endif
                        @if ($transaction->total_tax != 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">{{ $transaction->tax->name ?? '' }}</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->total_tax) }}
                                    {{ $transaction->received_currency->symbol }}</th>
                            </tr>
                        @endif
                        @if ($transaction->service_fee_value > 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.service')</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->service_fee_value) }}
                                    {{ $transaction->received_currency->symbol }}</th>
                            </tr>
                        @endif
                        @if ($transaction->discount_amount != 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.order_discount', [], $invoice_lang)
                                </th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->discount_amount) }}
                                    {{ $transaction->received_currency->symbol }}
                                </th>
                            </tr>
                        @endif
                        @if ($transaction->total_sp_discount != 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.sales_promotion', [], $invoice_lang)</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->total_sp_discount) }}
                                    {{ $transaction->received_currency->symbol }}
                                </th>
                            </tr>
                        @endif

                        @if (!empty($transaction->rp_redeemed_value))
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.redeemed_point_value', [], $invoice_lang)
                                </th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">
                                    {{ @num_format($transaction->rp_redeemed_value) }}
                                </th>
                            </tr>
                        @endif
                        @if($last_due != 0)
                            <tr>
                                <th style="font-size: 20px;" colspan="3">@lang('lang.balance', [], $invoice_lang)</th>
                                <th style="font-size: {{$font}}; text-align:right;" colspan="2">

                                        {{ @num_format($last_due) }}
                                    {{ $transaction->received_currency->symbol }}
                                </th>
                            </tr>
                        @endif
                        <tr>
                            <th style="font-size: 20px;" colspan="3">@lang('lang.grand_total', [], $invoice_lang)</th>
                            <th style="font-size: {{$font}}; text-align:right;" colspan="2">

                                    {{ @num_format($transaction->final_total) }}
                                {{ $transaction->received_currency->symbol }}
                            </th>
                        </tr>
                        <tr>

                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <div style="">
            <table style="margin: 0 auto; ">
                <tbody>
                    @if (empty($print_gift_invoice))
                            @foreach ($transaction->transaction_payments as $payment_data)
                                @if ($payment_data->method != 'deposit')
                                    <tr style="background-color:#ddd;">
                                        <td style="font-size: {{$font}}; padding: 7px;">
                                            @if (!empty($payment_data->method))
                                                {{ __('lang.' . $payment_data->method, [], $invoice_lang) }}
                                            @endif
                                        </td>
                                        <td style="font-size: {{$font}}; padding: 10px; text-align: right;" colspan="2">
                                            {{ @num_format($payment_data->amount + $payment_data->change_amount) }}
                                            {{ $transaction->received_currency->symbol }}</td>
                                    </tr>
                                @endif
                                @if (!empty($payment_data->change_amount) && $payment_data->change_amount > 0 && $payment_data->method != 'deposit')
                                    <tr>
                                        <td style="font-size: {{$font}}; padding: 7px;width:30%">@lang('lang.change', [], $invoice_lang)</td>
                                        <td colspan="2"
                                            style="font-size: {{$font}}; padding: 7px;width:40%; text-align: right;">
                                            {{ @num_format($payment_data->change_amount) }}
                                            {{ $transaction->received_currency->symbol }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @if (!empty($transaction->add_to_deposit) && $transaction->add_to_deposit > 0)
                            <tr>
                                <td style="font-size: {{$font}}; padding: 7px;width:30%">@lang('lang.deposit', [], $invoice_lang)
                                </td>
                                <td colspan="2" style="font-size: {{$font}}; padding: 7px;width:40%; text-align: right;">
                                    {{ @num_format($transaction->add_to_deposit) }}</td>
                            </tr>
                        @endif
                        @if (!empty($transaction->used_deposit_balance) && $transaction->used_deposit_balance > 0)
                            <tr>
                                <td style="font-size: {{$font}}; padding: 7px;width:30%">@lang('lang.used_deposit_balance', [], $invoice_lang)</td>
                                <td colspan="2" style="font-size: {{$font}}; padding: 7px;width:40%; text-align: right;">
                                    {{ @num_format($transaction->used_deposit_balance) }}</td>
                            </tr>
                        @endif
                        @if ($transaction->is_quotation != 1)
                            @if ($transaction->payment_status != 'paid' && $transaction->final_total - $transaction->transaction_payments->sum('amount') > 0)
                                <tr>
                                    <td style="font-size: {{$font}}; padding: 5px;width:30%">@lang('lang.due_sale_list', [], $invoice_lang)</td>
                                    <td colspan="2" style="font-size:{{$font}}; padding: 5px;width:40%; text-align: right;">
                                        {{ @num_format($transaction->final_total - $transaction->transaction_payments->sum('amount')) }}
                                        {{ $transaction->received_currency->symbol }}
                                    </td>
                                </tr>
                            @endif
                        @endif

                        @if( $total_due != 0)
                            <tr>
                                <td style="font-size: {{$font}}; padding: 5px;width:30%">@lang('lang.remaining_balance', [], $invoice_lang)</td>
                                <td colspan="2" style="font-size: {{$font}}; padding: 5px;width:40%; text-align: right;">
                                    {{ @num_format($total_due) }}
                                    {{ $transaction->received_currency->symbol }}
                                </td>
                            </tr>

                        @endif
                    @endif <!-- end of print gift invoice -->
                    <tr>
                        <td class="centered" colspan="3">

                                @lang('lang.thank_you_and_come_again', [], $invoice_lang)
                        </td>
                    </tr>
                    @if (!empty($transaction->terms_and_conditions))
                        <tr>
                            @php
                             $terms_and_conditions=str_replace("\n", '', strip_tags( $transaction->terms_and_conditions->description));
                            @endphp
                            <td  class="centered" colspan="3" style="text-align: justify;">{!! $terms_and_conditions !!}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="centered" colspan="3">
                            <img style="margin-top:10px;"
                                src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transaction->invoice_no, 'C128') }}"
                                width="300" alt="barcode" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @include('back-end.layouts.partials.print_footer')
        <div style="width: 100%; text-align: center;">
            <p><span class="">Proudly Developed at <a style="text-decoration: none;"
                        >sherifshalaby.tech</a></span></p>
        </div>
    </div>
</div>
