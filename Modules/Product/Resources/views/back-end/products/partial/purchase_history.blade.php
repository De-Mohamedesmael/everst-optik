<div class="modal-dialog" role="document" style="max-width: 80%;">
    <div class="modal-content">
        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">{{ __('lang.customer_details') }}
                <span class=" header-pill"></span>
            </h5>

            <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                    aria-hidden="true" style="border-radius: 10px !important;"><i class="dripicons-cross"></i></span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('lang.po_ref_no')</th>
                                <th>@lang('lang.invoice_no')</th>
                                <th>@lang('lang.date_and_time')</th>
                                <th>@lang('lang.invoice_date')</th>
                                <th>@lang('lang.value')</th>
                                <th>@lang('lang.created_by')</th>
                                <th>@lang('lang.paid_amount')</th>
                                <th>@lang('lang.pending_amount')</th>
                                <th>@lang('lang.due_date')</th>
                                <th class="notexport">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($add_stocks as $add_stock)
                            <tr>
                                <td>@if(!empty($add_stock->po_no)&& !empty($add_stock->purchase_order_id))<a
                                        href="{{action('PurchaseOrderController@show', $add_stock->purchase_order_id)}}">{{$add_stock->po_no}}</a>
                                    @endif</td>
                                <td>{{$add_stock->invoice_no}}</td>
                                <td> {{@format_datetime($add_stock->created_at)}}</td>
                                <td> {{@format_date($add_stock->transaction_date)}}</td>

                                <td>
                                    {{@num_format($add_stock->final_total)}}
                                </td>
                                <td>
                                    {{ucfirst($add_stock->created_by_user->name ?? '')}}
                                </td>
                                <td>
                                    {{@num_format($add_stock->transaction_payments->sum('amount'))}}
                                </td>
                                <td>
                                    {{@num_format($add_stock->final_total - $add_stock->transaction_payments->sum('amount'))}}
                                </td>
                                <td>@if(!empty($add_stock->due_date) && $add_stock->payment_status != 'paid')
                                    {{@format_date($add_stock->due_date)}} @endif</td>
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
                                            @can('stock.add_stock.view')
                                                <li>
                                                    <a href="{{route('admin.add-stock.show', $add_stock->id)}}"
                                                       class=""><i class="fa fa-eye btn"></i> @lang('lang.view')</a>
                                                </li>
                                            @endcan
                                            @can('stock.add_stock.create_and_edit')
                                                <li>
                                                    <a href="{{route('admin.add-stock.edit', $add_stock->id)}}"><i
                                                            class="dripicons-document-edit btn"></i>@lang('lang.edit')</a>
                                                </li>
                                            @endcan
                                            @can('stock.add_stock.delete')
                                                <li>
                                                    <a data-href="{{route('admin.add-stock.destroy', $add_stock->id)}}"
                                                       data-check_password="{{route('admin.check-password', Auth::user()->id)}}"
                                                       class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                                        @lang('lang.delete')</a>
                                                </li>
                                            @endcan
                                            @can('stock.pay.create_and_edit')
                                                @if($add_stock->payment_status != 'paid')
                                                    <li>
                                                        <a data-href="{{route('admin.transaction.addPayment', ['id' => $add_stock->id])}}"
                                                           data-container=".view_modal" class="btn btn-modal"><i
                                                                class="fa fa-money"></i>
                                                            @lang('lang.pay')</a>
                                                    </li>
                                                @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="text-center">
                                <td colspan="10">@lang('lang.no_data_found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" >@lang( 'lang.close' )</button>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
