@extends('back-end.layouts.app')

@section('title', __('lang.add_stock'))

@section('breadcrumbs')
    @parent

    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @if(isset($page))  {{translate($page)}}@else @lang('lang.add_stock_list') @endif </li>
@endsection
@section('content')

    <section class="forms px-3 py-1">
        <div class="container-fluid">

            <div class="card my-2">
                <div class="card-body p-2">
                    <form action="">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('store_id', __('lang.store'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::select('store_id', $stores, request()->store_id, [
                                        'class' => 'form-control filters',
                                        'placeholder' => __('lang.all'),
                                        'data-live-search' => 'true',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('created_by', __('lang.added_by'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}

                                    {!! Form::select('created_by', $admins, request()->created_by, [
                                        'class' => 'form-control filters',
                                        'placeholder' => __('lang.all'),
                                        'data-live-search' => 'true',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('product_id', __('lang.products'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::select('product_id', $products, request()->product_id, [
                                        'class' => 'form-control filters',
                                        'placeholder' => __('lang.all'),
                                        'data-live-search' => 'true',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('start_date', __('lang.start_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('start_date', request()->start_date, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'id' => 'start_date',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('start_time', __('lang.start_time'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('start_time', null, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start time_picker sale_filter',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('end_date', __('lang.end_date'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('end_date', request()->end_date, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                        'id' => 'end_date',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3 px-5">
                                <div class="form-group">
                                    {!! Form::label('end_time', __('lang.end_time'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::text('end_time', null, [
                                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start time_picker sale_filter',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-12 px-5 d-flex justify-content-center align-items-center">
                                <button type="button"
                                    class="btn btn-danger col-md-3 clear_filters">@lang('lang.clear_filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table" id="add_stock_table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.po_ref_no')</th>
                                    <th>@lang('lang.invoice_no')</th>
                                    <th>@lang('lang.date_and_time')</th>
                                    <th>@lang('lang.invoice_date')</th>
                                    <th>@lang('lang.created_by')</th>
                                    <th class="currencies">@lang('lang.paying_currency')</th>
                                    <th class="sum">@lang('lang.value')</th>
                                    <th class="sum">@lang('lang.paid_amount')</th>
                                    <th class="sum">@lang('lang.pending_amount')</th>
                                    <th>@lang('lang.due_date')</th>
                                    <th>@lang('lang.notes')</th>
                                    <th class="notexport">@lang('lang.action')</th>
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
                                    <th class="table_totals" style="text-align: right">@lang('lang.total')</th>
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
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            add_stock_table = $('#add_stock_table').DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [
                    [2, 'desc']
                ],
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
                // aaSorting: [
                //     [2, 'desc']
                // ],
                "ajax": {
                    "url": "{{route('admin.add-stock.index')}}",
                    "data": function(d) {
                        d.store_id = $('#store_id').val();
                        d.supplier_id = $('#supplier_id').val();
                        d.created_by = $('#created_by').val();
                        d.product_id = $('#product_id').val();
                        d.start_date = $('#start_date').val();
                        d.start_time = $("#start_time").val();
                        d.end_date = $('#end_date').val();
                        d.end_time = $("#end_time").val();
                    }
                },
                columnDefs: [{
                    "targets": [0, 3, 9, 8],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'created_by',
                        name: 'users.name'
                    },
                    {
                        data: 'paying_currency_symbol',
                        name: 'paying_currency_symbol',
                        searchable: false
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
                createdRow: function(row, data, dataIndex) {

                },
                fnDrawCallback: function(oSettings) {
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

                            function parseEuroNumber(val) {
                                var isNegative=false;

                                if (typeof val === "string") {
                                    val = val.trim();
                                    val = val.replace(/[\u200E\u202D\u202C\u00A0]/g, '');
                                    if (val.includes('-')) {
                                        isNegative = true;
                                    }
                                    val = val.replace(/[^\d,-]/g, '');
                                    val = val.replace(/-/g, '');
                                    val = val.replace(/\./g, '').replace(',', '.');
                                }

                                let number = parseFloat(val) || 0;

                                if (isNegative) {
                                    number = -number;
                                }
                                return number ;
                            }

                            if (column.data().count()) {
                                var sum = column.data().reduce(function(a, b) {
                                    a = parseEuroNumber(a);
                                    b = parseEuroNumber(b);
                                    console.log(a + b);

                                    return a + b;
                                });
                                console.log( sum);
                                $(column.footer()).html(
                                    __currency_trans_from_en(sum, false)
                                );
                            }
                        });
                },
            });
            $(document).on('click', '.filters', function() {
                add_stock_table.ajax.reload();
            })
            $('#end_date, #start_date').change(function() {
                add_stock_table.ajax.reload();
            })
        });



        $('.time_picker').focusout(function(event) {
            add_stock_table.ajax.reload();
        });

        $(document).on('click', '.clear_filters', function() {
            $('.filters').val('');
            $('.filters').selectpicker('refresh')
            add_stock_table.ajax.reload();
        })
    </script>
@endsection
