@extends('layouts.app')
@section('title', __('lang.delivery_list'))

@section('content')

<section class="forms py-2">

    <div class="container-fluid px-2">

        <x-page-title>
            <h4 class="print-title">@lang('lang.delivery_list')</h4>

        </x-page-title>

        <x-collapse collapse-id="Filter" button-class="d-flex btn-secondary" group-class="mb-1" body-class="py-1">

            <x-slot name="button">
                {{-- @lang('lang.filter') --}}
                <div style="width: 20px">
                    <img class="w-100" src="{{ asset('front/white-filter.png') }}" alt="">
                </div>
            </x-slot>

            <div class="col-md-12">

                <form action="">
                    <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('customer_id', __('lang.customer'), ['class' => app()->isLocale('ar') ?
                                'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                {!! Form::select('customer_id', $customers, request()->customer_id, ['class' =>
                                'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('status', __('lang.status'), ['class' => app()->isLocale('ar') ? 'mb-1
                                label-ar' : 'mb-1 label-en'
                                ]) !!}
                                {!! Form::select('status', ['final' => 'Completed', 'pending' => 'Pending'],
                                request()->status, ['class' =>
                                'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('payment_status', __('lang.payment_status'), ['class' =>
                                app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                {!! Form::select('payment_status', $payment_status_array, request()->payment_status,
                                ['class' =>
                                'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('start_date', __('lang.start_date'), ['class' => app()->isLocale('ar') ?
                                'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                {!! Form::text('start_date', request()->start_date, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('end_date', __('lang.end_date'), ['class' => app()->isLocale('ar') ?
                                'mb-1
                                label-ar' : 'mb-1 label-en'
                                ]) !!}
                                {!! Form::text('end_date', request()->end_date, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-center align-items-end mb-11px">

                            <button type="submit" class="btn btn-primary w-100">@lang('lang.filter')</button>
                        </div>
                        <div class="col-md-3 d-flex justify-content-center align-items-end mb-11px">
                            <a href="{{route('admin.sale.index')}}"
                                class="btn btn-danger w-100">@lang('lang.clear_filter')</a>
                        </div>
                    </div>
                </form>
            </div>
        </x-collapse>

        <div
            class="top-controls py-1 d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap">

        </div>
        <div class="card mt-1 mb-0">
            <div class="card-body py-2 px-4">
                <div class="table-responsive no-print">
                    <table id="sales_table" class="table dataTable">
                        <thead>
                            <tr>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.reference')</th>
                                <th>@lang('lang.customer')</th>
                                <th>@lang('lang.sale_status')</th>
                                <th>@lang('lang.delivery_status')</th>
                                <th>@lang('lang.payment_status')</th>
                                <th class="sum">@lang('lang.grand_total')</th>
                                <th class="sum">@lang('lang.paid')</th>
                                <th class="sum">@lang('lang.due')</th>
                                <th class="notexport">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td>{{@format_date($sale->transaction_date)}}</td>
                                <td>{{$sale->invoice_no}} @if(!empty($sale->return_parent))<a
                                        data-href="{{route('admin.sale-return.show', $sale->id)}}"
                                        data-container=".view_modal" class="btn btn-modal"
                                        style="color: #007bff;">R</a>@endif
                                </td>
                                <td>@if(!empty($sale->customer)){{$sale->customer->name}}@endif</td>
                                <td>{{ucfirst($sale->status)}}</td>
                                <td>{{ucfirst($sale->delivery_status)}}</td>
                                <td>@if(!empty($payment_status_array[$sale->payment_status])){{$payment_status_array[$sale->payment_status]}}@endif
                                </td>
                                <td>{{@num_format($sale->final_total)}}</td>
                                <td>{{@num_format($sale->transaction_payments->sum('amount'))}}</td>
                                <td>{{@num_format($sale->final_total - $sale->transaction_payments->sum('amount'))}}
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
                                            @can('sale.pos.create_and_edit')
                                            <li>

                                                <a data-href="{{route('admin.sale.print', $sale->id)}}"
                                                    class="btn print-invoice"><i class="dripicons-print"></i>
                                                    @lang('lang.generate_invoice')</a>
                                            </li>
                                            <li class="divider"></li>
                                            @endcan
                                            @can('sale.pos.view')
                                            <li>

                                                <a data-href="{{route('admin.sale.show', $sale->id)}}"
                                                    data-container=".view_modal" class="btn btn-modal"><i
                                                        class="fa fa-eye"></i>
                                                    @lang('lang.view')</a>
                                            </li>
                                            <li class="divider"></li>
                                            @endcan
                                            @can('sale.pos.create_and_edit')
                                            <li>

                                                <a href="{{route('admin.sale.edit', $sale->id)}}" class="btn"><i
                                                        class="dripicons-document-edit"></i> @lang('lang.edit')</a>
                                            </li>
                                            <li class="divider"></li>
                                            @endcan
                                            @can('sale.pay.create_and_edit')
                                            @if($sale->payment_status != 'paid')
                                            <li>
                                                <a data-href="{{action('TransactionPaymentController@addPayment', ['id' => $sale->id])}}"
                                                    data-container=".view_modal" class="btn btn-modal"><i
                                                        class="fa fa-plus"></i>
                                                    @lang('lang.add_payment')</a>
                                            </li>
                                            @endif
                                            @endcan
                                            @can('sale.pay.view')
                                            @if($sale->payment_status != 'pending')
                                            <li>
                                                <a data-href="{{action('TransactionPaymentController@show', $sale->id)}}"
                                                    data-container=".view_modal" class="btn btn-modal"><i
                                                        class="fa fa-money"></i>
                                                    @lang('lang.view_payments')</a>
                                            </li>
                                            @endif
                                            @endcan
                                            @can('sale.pos.delete')
                                            <li>
                                                <a data-href="{{route('admin.sale.destroy', $sale->id)}}"
                                                    data-check_password="{{action('UserController@checkPassword', Auth::user()->id)}}"
                                                    class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                                    @lang('lang.delete')</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right">@lang('lang.totals')</th>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div
            class="bottom-controls mt-1 p-1 d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap">
            <!-- Pagination and other controls can go here -->
        </div>
    </div>
</section>

<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
<script>
    $(document).on('click', '.print-invoice', function(){
        $.ajax({
            method: 'get',
            url: $(this).data('href'),
            data: {  },
            success: function(result) {
                if(result.success){
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

    table
    .column( '0:visible' )
    .order( 'desc' )
    .draw();
</script>
@endsection
