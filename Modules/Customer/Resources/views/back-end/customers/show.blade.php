@extends('back-end.layouts.app')
@section('title', __('lang.customer_details'))


@section('styles')

<style>
    .nav-tabs .nav-item .nav-link.active {
        border-color: transparent;
        border-bottom: 2px solid var(--secondary-color);
    }
</style>
@endsection
@section('content')
<section class="forms px-3 py-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 px-1  no-print">
                <div
                    class="  d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h5 class="mb-0 position-relative print-title" style="margin-right: 30px">
                        @lang('lang.customer_details')
                        <span class="header-pill"></span>
                    </h5>
                </div>
                <form action="">
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('start_date', __('lang.start_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                        text-start',
                                        ]) !!}
                                        {!! Form::text('start_date', request()->start_date, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                        text-start',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('end_date', __('lang.end_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end :
                                        text-start',
                                        ]) !!}
                                        {!! Form::text('end_date', request()->end_date, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end :
                                        text-start',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">

                                    <button type="submit" class="btn btn-main col-md-12">@lang('lang.filter')</button>
                                </div>
                                <div class="col-md-3 px-5 d-flex justify-content-center align-items-center">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                        class="btn btn-danger col-md-12 ">@lang('lang.clear_filter')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <ul class="nav nav-tabs mb-2 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (empty(request()->show)) active @endif" href="#info-sale"
                                    role="tab" data-toggle="tab">@lang('lang.info')</a>
                            </li>
                            <li class="nav-item">
                                <a id="view-purchases-a"
                                    class="nav-link @if (request()->show == 'purchases') active @endif"
                                    href="#purchases" role="tab" data-toggle="tab">@lang('lang.purchases')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->show == 'sell_return') active @endif"
                                    href="#sell_return" role="tab" data-toggle="tab">@lang('lang.sell_return')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->show == 'discounts') active @endif"
                                    href="#store-discount" role="tab" data-toggle="tab">@lang('lang.discounts')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->show == 'view_payments') active @endif"
                                    href="#view-payments" role="tab" data-toggle="tab">@lang('lang.view_payments')</a>
                            </li>
                            <li class="nav-item">
                                <a id="view-prescriptions-a"
                                    class="nav-link @if (request()->show == 'view_prescriptions') active @endif"
                                    href="#view-prescriptions" role="tab" data-toggle="tab">{{translate('view
                                    prescriptions')}}</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade @if (empty(request()->show)) show active @endif"
                                id="info-sale">
                                <br>
                                @if ($balance < 0) <div class="col-md-12">
                                    <button
                                        data-href="{{ route('admin.transactionPayment.payCustomerDue', $customer->id) }}"
                                        class="btn btn-primary btn-modal"
                                        data-container=".view_modal">@lang('lang.pay')</button>
                            </div>
                            @endif
                            <br>
                            <div class="col-md-12 text-muted">
                                <div class="row">
                                    <div class="col-md-6 row justify-content-between" style="gap: 15px">
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.name'):</b>

                                            <span style="font-weight: 600;font-size: 20px" class="customer_name_span">
                                                {{ $customer->name }}
                                            </span>
                                        </div>

                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.customer_type'):</b> <span
                                                style="font-weight: 600;font-size: 20px"
                                                class="customer_customer_type_span">{{ $customer->customer_type->name ??
                                                '' }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.age'):</b> <span style="font-weight: 600;font-size: 20px"
                                                class="customer_email_span">{{
                                                $customer->age ?? '' }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.mobile'):</b> <span style="font-weight: 600;font-size: 20px"
                                                class="customer_mobile_span">{{
                                                $customer->mobile_number ?? '' }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.address'):</b> <span style="font-weight: 600;font-size: 20px"
                                                class="customer_address_span">{{
                                                $customer->address ?? '' }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.email'):</b> <span style="font-weight: 600;font-size: 20px"
                                                class="customer_email_span">{{
                                                $customer->email ?? '' }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.balance'):</b> <span style="font-weight: 600;font-size: 20px"
                                                class="balance @if ($balance < 0) text-red @endif">{{ $balance }}</span>
                                        </div>
                                        <div class="info-row mb-2 d-flex justify-content-start align-items-center"
                                            style="background: #476762;border-radius: 8px;padding: 5px;color: white;width: 48%;">
                                            <b>@lang('lang.created_by'):</b>
                                            <span style="font-weight: 600;font-size: 20px">
                                                {{ $customer->created_by_admin?->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="thumbnail">
                                            <img style="width: 200px; height: 200px;" class="img-fluid"
                                                src="@if ($customer->getFirstMediaUrl('customer_photo')) {{ $customer->getFirstMediaUrl('customer_photo') }} @else {{asset('/uploads/' . Modules\Setting\Entities\System::getProperty('logo'))}}@endif"
                                                alt="Customer photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel"
                            class="tab-pane fade @if (request()->show == 'purchases') show active @endif"
                            id="purchases">
                            <div class="table-responsive">
                                <table class="table" id="sales_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.reference_no')</th>
                                            <th>@lang('lang.customer')</th>
                                            <th>@lang('lang.products')</th>
                                            <th class="currencies">@lang('lang.received_currency')</th>
                                            <th class="sum">@lang('lang.discount')</th>
                                            <th class="sum">@lang('lang.grand_total')</th>
                                            <th class="sum">@lang('lang.paid')</th>
                                            <th class="sum">@lang('lang.due')</th>
                                            <th>@lang('lang.payment_date')</th>
                                            <th>@lang('lang.status')</th>
                                            <th>@lang('lang.points_earned')</th>
                                            <th>@lang('lang.cashier')</th>
                                            <th>@lang('lang.files')</th>
                                            <th>@lang('lang.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <th class="table_totals" style="text-align: right">
                                                @lang('lang.total')</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel"
                            class="tab-pane fade @if (request()->show == 'sell_return') show active @endif"
                            id="sell_return">
                            <div class="table-responsive">
                                <table class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.reference_no')</th>
                                            <th>@lang('lang.customer')</th>
                                            <th class="sum">@lang('lang.discount')</th>
                                            <th class="sum">@lang('lang.grand_total')</th>
                                            <th class="sum">@lang('lang.paid')</th>
                                            <th class="sum">@lang('lang.due')</th>
                                            <th>@lang('lang.payment_date')</th>
                                            <th>@lang('lang.status')</th>
                                            <th>@lang('lang.cashier')</th>
                                            <th>@lang('lang.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                        $total_purchase_payments = 0;
                                        $total_purchase_due = 0;
                                        @endphp
                                        @foreach ($sale_returns as $return)
                                        <tr>
                                            <td>{{ @format_date($return->transaction_date) }}</td>
                                            <td>{{ $return->invoice_no }}</td>
                                            <td>
                                                @if (!empty($return->customer))
                                                {{ $return->customer->name }}
                                                @endif
                                            </td>

                                            <td>{{ @num_format($return->discount_amount) }}</td>
                                            <td>{{ @num_format($return->final_total) }}</td>
                                            <td>{{ @num_format($return->transaction_payments->sum('amount')) }}
                                            </td>
                                            <td>{{ @num_format($return->final_total -
                                                $return->transaction_payments->sum('amount')) }}
                                            </td>
                                            <td>
                                                @if ($return->transaction_payments->count() > 0)
                                                {{ @format_date($return->transaction_payments->last()->paid_on) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($return->status == 'final')
                                                <span class="badge badge-success">@lang('lang.completed')</span>
                                                @else
                                                {{ ucfirst($return->status) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($return->transaction_payments->count() > 0)
                                                {{ $return->transaction_payments->last()->created_by_admin->name }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-
                                                    group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">@lang('lang.action')
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                        user="menu">
                                                        @can('sale.pos.view')
                                                        <li>

                                                            <a data-href="#{{-- action('SellReturnController@show', $return->return_parent_id) --}}"
                                                                data-container=".view_modal" class="btn btn-modal"><i
                                                                    class="fa fa-eye"></i>
                                                                @lang('lang.view')</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        @endcan
                                                        @can('sale.pos.create_and_edit')
                                                        <li>
                                                            <a href="#{{-- action('SellReturnController@add', $return->return_parent_id) --}}"
                                                                class="btn"><i class="dripicons-document-edit"></i>
                                                                @lang('lang.edit')</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        @endcan

                                                        @can('sale.pos.delete')
                                                        <li>
                                                            <a data-href="{{ route('admin.sale.destroy', $return->id) }}"
                                                                data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                                class="btn text-red delete_item"><i
                                                                    class="fa fa-trash"></i>
                                                                @lang('lang.delete')</a>
                                                        </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                        </tr>
                                        @php
                                        $total_purchase_payments += $return->transaction_payments->sum(
                                        'amount',
                                        );
                                        $total_purchase_due +=
                                        $return->final_total -
                                        $return->transaction_payments->sum('amount');
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <th style="text-align: right">@lang('lang.total')</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel"
                            class="tab-pane fade @if (request()->show == 'discounts') show active @endif"
                            id="store-discount">
                            <div class="table-responsive">
                                <table class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.reference_no')</th>
                                            <th>@lang('lang.customer')</th>
                                            <th>@lang('lang.products')</th>
                                            <th class="sum">@lang('lang.grand_total')</th>
                                            <th>@lang('lang.status')</th>
                                            <th>@lang('lang.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                        $total_discount_payments = 0;
                                        $total_discount_due = 0;
                                        @endphp
                                        @foreach ($discounts as $discount)
                                        <tr>
                                            <td>{{ @format_date($discount->transaction_date) }}</td>
                                            <td>{{ $discount->invoice_no }}</td>
                                            <td>
                                                @if (!empty($discount->customer))
                                                {{ $discount->customer->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($discount->transaction_sell_lines as $line)
                                                ({{ @num_format($line->quantity) }})
                                                @if (!empty($line->product))
                                                {{ $line->product->name }}
                                                @endif <br>
                                                @endforeach
                                            </td>
                                            <td>{{ @num_format($discount->final_total) }}</td>
                                            </td>
                                            <td>
                                                @if ($discount->status == 'final')
                                                <span class="badge badge-success">@lang('lang.completed')</span>
                                                @else
                                                {{ ucfirst($discount->status) }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">@lang('lang.action')
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                        user="menu">
                                                        @can('sale.pos.view')
                                                        <li>
                                                            <a data-href="{{ action('SellController@show', $discount->id) }}"
                                                                data-container=".view_modal" class="btn btn-modal"><i
                                                                    class="fa fa-eye"></i>
                                                                @lang('lang.view')</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        @endcan
                                                        @can('sale.pos.create_and_edit')
                                                        <li>
                                                            <a href="{{ action('SellController@edit', $discount->id) }}"
                                                                class="btn"><i class="dripicons-document-edit"></i>
                                                                @lang('lang.edit')</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        @endcan

                                                        @can('sale.pos.delete')
                                                        <li>
                                                            <a data-href="{{ action('SellController@destroy', $discount->id) }}"
                                                                data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                                class="btn text-red delete_item"><i
                                                                    class="fa fa-trash"></i>
                                                                @lang('lang.delete')</a>
                                                        </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                        </tr>
                                        @php
                                        $total_discount_payments += $discount->transaction_payments->sum(
                                        'amount',
                                        );
                                        $total_discount_due +=
                                        $discount->final_total -
                                        $discount->transaction_payments->sum('amount');
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <th style="text-align: right">@lang('lang.total')</th>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel"
                            class="tab-pane fade @if (request()->show == 'view_payments') show active @endif"
                            id="view-payments">
                            <div class="table-responsive">
                                <table class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.amount')</th>
                                            <th>@lang('lang.type')</th>
                                            <th>@lang('lang.payment_date')</th>
                                            <th>@lang('lang.payment_type')</th>
                                            <th>@lang('lang.bank_name')</th>
                                            <th>@lang('lang.ref_number')</th>
                                            <th>@lang('lang.bank_deposit_date')</th>

                                            <th>@lang('lang.files')</th>
                                            <th>@lang('lang.created_by')</th>
                                            <th width="50%">@lang('lang.date_of_creation')</th>
                                            <th>@lang('lang.action')</th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @isset($payments)
                                        @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ @num_format($payment->amount) }}</td>
                                            <td><span class="type-{{ $payment->type }}">{{ __('lang.type_' .
                                                    $payment->type) }}</span>
                                            </td>
                                            <td>{{ @format_date($payment->paid_on) }}</td>
                                            <td>{{ $payment_type_array[$payment->method] }}</td>
                                            <td>{{ $payment->bank_name }}</td>
                                            <td>{{ $payment->ref_number }}</td>
                                            <td>
                                                @if (!empty($payment->bank_deposit_date && ($payment->method ==
                                                'bank_transfer' || $payment->method == 'cheque')))
                                                {{ @format_date($payment->bank_deposit_date) }}
                                                @endif
                                            </td>


                                            <td>
                                                @php
                                                $payment_media = $payment->getMedia(
                                                'transaction_payment',
                                                );
                                                @endphp
                                                @if (!empty($payment_media))
                                                @foreach ($payment_media as $media)
                                                <a href="{{ $media->getUrl() }}">{{ $media->name }}</a>
                                                <br>
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_by_user->name }}</td>
                                            <td>{{ $payment->created_at->format('Y/m/d h:i A') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">@lang('lang.action')
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                        user="menu">
                                                        @can('sale.pay.create_and_edit')
                                                        <li>
                                                            <a data-href="{{ route('admin.transaction.addPayment', $payment->id) }}"
                                                                data-container=".view_modal" class="btn btn-modal"><i
                                                                    class="dripicons-document-edit"></i>
                                                                @lang('lang.edit')</a>
                                                        </li>
                                                        @endcan
                                                        @can('sale.pay.delete')
                                                        <li>
                                                            <a data-href="{{ route('admin.transaction-payment.destroy', $payment->id) }}"
                                                                data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                                                                class="btn text-red delete_item"><i
                                                                    class="fa fa-trash"></i>
                                                                @lang('lang.delete')</a>
                                                        </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>
                                        @endforeach
                                        @endisset
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div role="tabpanel"
                            class="tab-pane fade @if (request()->show == 'view_prescriptions') show active @endif"
                            id="view-prescriptions">
                            <div class="table-responsive">
                                <table class="table" id="prescriptions_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.invoice_no')</th>
                                            <th>@lang('lang.lens')</th>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.amount')</th>
                                            <th>@lang('lang.VA_amount')</th>
                                            <th>@lang('lang.created_by')</th>
                                            <th>@lang('lang.action')</th>


                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<section class="invoice print_section print-only" id="print_section"> </section>
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
<script>
    $(document).on('click', '.print-invoice', function() {
            $(".modal").modal("hide");
            $.ajax({
                method: "get",
                url: $(this).data("href"),
                data: {},
                success: function(result) {
                    if (result.success) {
                        pos_print(result.html_content);
                    }
                },
            });
        })

        function pos_print(receipt) {
            $("#receipt_section").html(receipt);
            __currency_convert_recursively($("#receipt_section"));
            __print_receipt("receipt_section");
        }

        $(document).ready(function() {
            $(document).on('click', '#view-purchases-a', function() {
                sales_table = $("#sales_table").DataTable({
                    lengthChange: true,
                    paging: true,
                    info: false,
                    bAutoWidth: false,
                    // order: [],
                    language: {
                        url: dt_lang_url,
                    },
                    lengthMenu: [
                        [10, 25, 50, 75, 100, 200, 500, -1],
                        [10, 25, 50, 75, 100, 200, 500, "All"],
                    ],
                    dom: "lBfrtip",
                    stateSave: true,
                    buttons: buttons,
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [0, "desc"]
                    ],
                    initComplete: function () {
                        $(this.api().table().container()).find('input').parent().wrap('<form>').parent()
                            .attr('autocomplete', 'off');
                    },
                    ajax: {
                        url: "/dashboard/customers/{{ $customer->id }}",
                        data: function (d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                        },
                    },
                    columnDefs: [{
                        targets: "date",
                        type: "date-eu",
                    }],
                    columns: [{
                        data: "transaction_date",
                        name: "transaction_date"
                    },
                        {
                            data: "invoice_no",
                            name: "invoice_no"
                        },
                        {
                            data: "customer_name",
                            name: "customers.name"
                        },
                        {
                            data: "products",
                            name: "products.name"
                        },
                        {
                            data: "received_currency_symbol",
                            name: "received_currency_symbol",
                            searchable: false
                        },
                        {
                            data: "discount_amount",
                            name: "discount_amount"
                        },
                        {
                            data: "final_total",
                            name: "final_total"
                        },
                        {
                            data: "paid",
                            name: "transaction_payments.amount",
                            searchable: false
                        },
                        {
                            data: "due",
                            name: "transaction_payments.amount",
                            searchable: false
                        },
                        {
                            data: "paid_on",
                            name: "transaction_payments.paid_on"
                        },
                        {
                            data: "status",
                            name: "transactions.status"
                        },
                        {
                            data: "rp_earned",
                            name: "rp_earned"
                        },

                        {
                            data: "created_by",
                            name: "admins.name"
                        },
                        {
                            data: "files",
                            name: "files"
                        },
                        {
                            data: "action",
                            name: "action"
                        },
                    ],
                    createdRow: function (row, data, dataIndex) {
                    },
                    footerCallback: function (row, data, start, end, display) {
                        var intVal = function (i) {
                            return typeof i === "string" ?
                                i.replace(/[\$,]/g, "") * 1 :
                                typeof i === "number" ?
                                    i :
                                    0;
                        };

                        this.api()
                            .columns(".currencies", {
                                page: "current"
                            }).every(function () {
                            var column = this;
                            let currencies_html = '';
                            $.each(currency_obj, function (key, value) {
                                currencies_html +=
                                    `<h6 class="footer_currency" data-is_default="${value.is_default}"  data-currency_id="${value.currency_id}">${value.symbol}</h6>`
                                $(column.footer()).html(currencies_html);
                            });
                        })
                        this.api()
                            .columns(".sum", {
                                page: "current"
                            })
                            .every(function () {
                                var column = this;
                                var currency_total = [];
                                $.each(currency_obj, function (key, value) {
                                    currency_total[value.currency_id] = 0;
                                });
                                column.data().each(function (group, i) {
                                    b = $(group).text();
                                    currency_id = $(group).data('currency_id');

                                    $.each(currency_obj, function (key, value) {
                                        if (currency_id == value.currency_id) {
                                            currency_total[value.currency_id] += intVal(
                                                b);
                                        }
                                    });
                                });
                                var footer_html = '';
                                $.each(currency_obj, function (key, value) {
                                    footer_html +=
                                        `<h6 class="currency_total currency_total_${value.currency_id}" data-currency_id="${value.currency_id}" data-is_default="${value.is_default}" data-conversion_rate="${value.conversion_rate}" data-base_conversion="${currency_total[value.currency_id] * value.conversion_rate}" data-orig_value="${currency_total[value.currency_id]}">${__currency_trans_from_en(currency_total[value.currency_id], false)}</h6>`
                                });
                                $(column.footer()).html(
                                    footer_html
                                );
                            });
                    },
                });
            });
        });

        // $(document).ready(function() {
        //     $(document).on('click', '.clear_filter', function() {
        //         $('.sale_filter').val('');
        //         $('.sale_filter').selectpicker('refresh');
        //         rewards_table.ajax.reload();
        //     });
        //     $(document).on('change', '.sale_filter', function() {
        //         rewards_table.ajax.reload();
        //     });
        // })
        $(document).on('click', '#view-prescriptions-a', function() {
            prescriptions_table = $("#prescriptions_table").DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                // order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                stateSave: true,
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, "desc"]
                ],
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent()
                        .attr('autocomplete', 'off');
                },
                ajax: {
                    url: "{{route('admin.customers.prescriptions', ['customer_id'=>$customer->id])}}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                },
                columnDefs: [{
                    targets: "date",
                    type: "date-eu",
                }],
                columns: [{
                    data: "invoice_no",
                    name: "invoice_no"
                },
                    {
                        data: "lens",
                        name: "lens"
                    },
                    {
                        data: "date",
                        name: "date"
                    },
                    {
                        data: "amount",
                        name: "amount"
                    },
                    {
                        data: "VA_amount",
                        name: "VA_amount",
                        // searchable: false
                    },
                    {
                        data: "created_by",
                        name: "admins.name"
                    },
                    {
                        data: "action",
                        name: "action"
                    }
                ],
                createdRow: function(row, data, dataIndex) {},
                footerCallback: function(row, data, start, end, display) {
                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                                i :
                                0;
                    };

                    this.api()
                        .columns(".currencies", {
                            page: "current"
                        }).every(function() {
                        var column = this;
                        let currencies_html = '';
                        $.each(currency_obj, function(key, value) {
                            currencies_html +=
                                `<h6 class="footer_currency" data-is_default="${value.is_default}"  data-currency_id="${value.currency_id}">${value.symbol}</h6>`
                            $(column.footer()).html(currencies_html);
                        });
                    })
                    this.api()
                        .columns(".sum", {
                            page: "current"
                        })
                        .every(function() {
                            var column = this;
                            var currency_total = [];
                            $.each(currency_obj, function(key, value) {
                                currency_total[value.currency_id] = 0;
                            });
                            column.data().each(function(group, i) {
                                b = $(group).text();
                                currency_id = $(group).data('currency_id');

                                $.each(currency_obj, function(key, value) {
                                    if (currency_id == value.currency_id) {
                                        currency_total[value.currency_id] += intVal(
                                            b);
                                    }
                                });
                            });
                            var footer_html = '';
                            $.each(currency_obj, function(key, value) {
                                footer_html +=
                                    `<h6 class="currency_total currency_total_${value.currency_id}" data-currency_id="${value.currency_id}" data-is_default="${value.is_default}" data-conversion_rate="${value.conversion_rate}" data-base_conversion="${currency_total[value.currency_id] * value.conversion_rate}" data-orig_value="${currency_total[value.currency_id]}">${__currency_trans_from_en(currency_total[value.currency_id], false)}</h6>`
                            });
                            $(column.footer()).html(
                                footer_html
                            );
                        });
                },
            });
        });
</script>
@endsection