@extends('back-end.layouts.app')
@section('title', __('lang.sales_list'))
@section('content')
<section class="forms pos-section no-print">
    <div class="container-fluid px-2">
        <div
            class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
            <button class="text-decoration-none toggle-button mb-0" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#moreInfoCollapseFiltter" aria-expanded="false"
                    aria-controls="moreInfoCollapseFiltter">
                <div style="width: 20px">
                    <img class="w-100" src="{{ asset('front/white-filter.png') }}" alt="">
                </div>
                {{translate('Filter')}}
                <span class="section-header-pill"></span>
            </button>
        </div>
        <div class="collapse" id="moreInfoCollapseFiltter">
                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="hidden" name="type_trans" id="type_trans" value="{{request()->type_trans}}">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('category_id', __('lang.category') . ':', ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('category_id[]', $categories, request()->category_id, ['class'
                            =>
                            'form-control sale_filter selectpicker', 'data-live-search' => 'true',
                            'data-actions-box' => 'true', 'placeholder' => __('lang.all'), 'multiple', 'id'
                            =>
                            'category_id']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('brand_id', __('lang.brand') . ':', ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('brand_id[]', $brands, request()->brand_id, ['class' =>
                            'form-control sale_filter selectpicker', 'data-live-search' => 'true',
                            'data-actions-box' => 'true', 'placeholder' => __('lang.all'), 'multiple', 'id'
                            =>
                            'brand_id']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('customer_id', __('lang.customer'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('customer_id', $customers, request()->customer_id, ['class' =>
                            'form-control sale_filter', 'placeholder' => __('lang.all'), 'data-live-search'
                            =>
                            'true']) !!}
                        </div>
                    </div>
                    @if (session('system_mode') == 'restaurant')
                    @php
                    $customer_types = $customer_types->toArray() + ['dining_in' => __('lang.dining_in')];
                    @endphp
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('customer_type_id', __('lang.customer_type'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('customer_type_id', $customer_types,
                            request()->customer_type_id,
                            ['class' => 'form-control sale_filter', 'placeholder' => __('lang.all'),
                            'data-live-search' => 'true']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('store_id', __('lang.store'), ['class' => app()->isLocale('ar') ?
                            'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('store_id', $stores, request()->store_id, ['class' =>
                            'form-control
                            sale_filter', 'placeholder' => __('lang.all'), 'data-live-search' => 'true'])
                            !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('status', __('lang.status'), ['class' => app()->isLocale('ar') ?
                            'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('status', ['final' => 'Completed', 'pending' => 'Pending'],
                            request()->status, ['class' => 'form-control sale_filter', 'placeholder' =>
                            __('lang.all'), 'data-live-search' => 'true']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('method', __('lang.payment_type'), ['class' => app()->isLocale('ar')
                            ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('method', $payment_types, request()->method, ['class' =>
                            'form-control sale_filter', 'placeholder' => __('lang.all'), 'data-live-search'
                            =>
                            'true']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('payment_status', __('lang.payment_status'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('payment_status', $payment_status_array,
                            request()->payment_status,
                            ['class' => 'form-control sale_filter', 'placeholder' => __('lang.all'),
                            'data-live-search' => 'true']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('tax_id', __('lang.tax') . ':', ['class' => app()->isLocale('ar') ?
                            'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('tax_id[]', $taxes, request()->tax_id, ['class' =>
                            'form-control
                            sale_filter selectpicker', 'data-live-search' => 'true', 'data-actions-box' =>
                            'true', 'placeholder' => __('lang.all'), 'multiple', 'id' => 'tax_id']) !!}
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_date', __('lang.generation_start_date'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('start_date', request()->start_date, ['class' => 'form-control
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_time', __('lang.generation_start_time'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('start_time', null, ['class' => 'form-control time_picker
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_date', __('lang.generation_end_date'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('end_date', request()->end_date, ['class' => 'form-control
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_time', __('lang.generation_end_time'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('end_time', null, ['class' => 'form-control time_picker
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('payment_start_date', __('lang.payment_start_date'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('start_date', request()->start_date, ['class' => 'form-control
                            datepicker sale_filter', 'id' => 'payment_start_date']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('payment_start_time', __('lang.payment_start_time'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('payment_start_time', null, ['class' => 'form-control time_picker
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('payment_end_date', __('lang.payment_end_date'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('end_date', request()->end_date, ['class' => 'form-control
                            sale_filter', 'id' => 'payment_end_date']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('payment_end_time', __('lang.payment_end_time'), ['class' =>
                            app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::text('payment_end_time', null, ['class' => 'form-control time_picker
                            sale_filter']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('created_by', __('lang.cashier'), ['class' => app()->isLocale('ar')
                            ? 'mb-1 label-ar' : 'mb-1 label-en'
                            ]) !!}
                            {!! Form::select('created_by', $cashiers, false, ['class' => 'form-control
                            sale_filter selectpicker', 'id' => 'created_by', 'data-live-search' => 'true',
                            'placeholder' => __('lang.all')]) !!}
                        </div>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-end mb-11px">
                        <button type="submit" class="btn btn-primary w-100"
                            id="submit-filter">@lang('lang.filter')</button>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-end mb-11px">
                        <button type="button"
                            class="btn btn-danger w-100 clear_filter">@lang('lang.clear_filter')</button>
                    </div>
                </div>
        </div>



        <div class="card my-3">

            <div class="table-responsive" style="height: 60vh">
                <table id="sales_table" class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('lang.date_and_time')</th>
                        <th>@lang('lang.reference')</th>
                        <th>@lang('lang.store')</th>
                        <th>@lang('lang.customer')</th>
                        <th>@lang('lang.phone')</th>
                        <th>@lang('lang.sale_status')</th>
                        <th>@lang('lang.payment_status')</th>
                        <th>@lang('lang.payment_type')</th>
                        <th>@lang('lang.ref_number')</th>
                        <th class="currencies">@lang('lang.received_currency')</th>
                        <th class="sum">@lang('lang.grand_total')</th>
                        <th class="sum">@lang('lang.paid')</th>
                        <th class="sum">@lang('lang.due_sale_list')</th>
                        <th>@lang('lang.payment_date')</th>
                        <th>@lang('lang.cashier')</th>
                        <th>@lang('lang.commission')</th>
                        <th>@lang('lang.products')</th>
                        <th>@lang('lang.sku')</th>
                        <th>@lang('lang.sale_note')</th>
                        <th>@lang('lang.files')</th>
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
                        <td></td>
                        <td></td>
                        <th class="table_totals" style="text-align: right">@lang('lang.totals')</th>
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

        <div
            class="bottom-controls mt-1 p-1 d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap">
            <!-- Pagination and other controls can go here -->
            <div class="col-md-8 mb-0 text-center">
                <h4 class="mb-0">@lang('lang.number_of_orders'): <span class="number_of_orders_span"
                        style="margin-right: 15px;">0</span>
                    @lang('lang.number_of_customer'): <span class="number_of_customer_span"
                        style="margin-right: 15px;">0</span>
                </h4>
            </div>
        </div>
    </div>
</section>
<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

    <script>
    $(document).ready(function() {
            get_total_details();
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
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent()
                        .attr('autocomplete', 'off');
                },
                ajax: {
                    url: "{{route('admin.sale.index')}}",
                    data: function(d) {
                        d.category_id = $("#category_id").val();
                        d.brand_id = $("#brand_id").val();
                        d.tax_id = $("#tax_id").val();
                        d.customer_id = $("#customer_id").val();
                        d.customer_type_id = $("#customer_type_id").val();
                        d.store_id = $("#store_id").val();
                        d.status = $("#status").val();
                        d.method = $("#method").val();
                        d.payment_status = $("#payment_status").val();
                        d.start_date = $("#start_date").val();
                        d.start_time = $("#start_time").val();
                        d.end_date = $("#end_date").val();
                        d.end_time = $("#end_time").val();
                        d.payment_start_date = $("#payment_start_date").val();
                        d.payment_start_time = $("#payment_start_time").val();
                        d.payment_end_date = $("#payment_end_date").val();
                        d.payment_end_time = $("#payment_end_time").val();
                        d.created_by = $("#created_by").val();
                        d.type_trans = $("#type_trans").val();
                    },
                },
                columnDefs: [{
                        targets: "date",
                        type: "date-eu",
                    },

                        {
                            targets: [14],
                            orderable: false,
                            searchable: false,
                        }
                ],
                columns: [{
                        data: "transaction_date",
                        name: "transaction_date"
                    },
                    {
                        data: "invoice_no",
                        name: "invoice_no"
                    },
                    {
                        data: "store_name",
                        name: "stores.name"
                    },
                    {
                        data: "customer_name",
                        name: "customers.name"
                    },
                    {
                        data: "mobile_number",
                        name: "customers.mobile_number"
                    },
                    {
                        data: "status",
                        name: "transactions.status"
                    },
                    {
                        data: "payment_status",
                        name: "transactions.payment_status"
                    },
                    {
                        data: "method",
                        name: "transaction_payments.method"
                    },
                    {
                        data: "ref_number",
                        name: "transaction_payments.ref_number"
                    },
                    {
                        data: "received_currency_symbol",
                        name: "received_currency_symbol",
                        searchable: false
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
                        data: "created_by",
                        name: "admins.name"
                    },

                    {
                        data: "commissions",
                        name: "commissions",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "products",
                        name: "products.name"
                    },
                    {
                        data: "sku",
                        name: "products.sku",
                        visible: false
                    },
                    {
                        data: "sale_note",
                        name: "sale_note",
                    },
                    {
                        data: "files",
                        name: "files",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "action",
                        name: "action"
                    },
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
                initComplete: function (settings, json) {
                // Move elements into the .top-controls div after DataTable initializes
                $('.top-controls').append($('.dataTables_length').addClass('d-flex col-lg-3 col-9 mb-3 mb-lg-0 justify-content-center'));
                $('.top-controls').append($('.dt-buttons').addClass('col-lg-6 col-12 mb-3 mb-lg-0 d-flex dt-gap justify-content-center'));
                $('.top-controls').append($('.dataTables_filter').addClass('col-lg-3 col-9'));


                $('.bottom-controls').append($('.dataTables_paginate').addClass('col-lg-2 col-9 p-0'));
                $('.bottom-controls').append($('.dataTables_info'));
                }
            });


            $(document).on('click', '#submit-filter', function() {
                sales_table.ajax.reload();
                get_total_details();
            });
            $('#sales_table').on('column-visibility.dt', function (e, settings, column, state) {
                // Here, you can use cookies or local storage to save the column visibility state
                // Example using local storage:
                var savedColumnPreferences = JSON.parse(localStorage.getItem('columnPreferences')) || {};
                savedColumnPreferences[column] = state;
                localStorage.setItem('columnPreferences', JSON.stringify(savedColumnPreferences));
            });
            // Restore column visibility preferences on page load
            var savedColumnPreferences = JSON.parse(localStorage.getItem('columnPreferences')) || {};
            for (var column in savedColumnPreferences) {
                var columnIdx = dataTable.column(column).index();
                dataTable.column(columnIdx).visible(savedColumnPreferences[column]);
            }
        })

        $(document).on('change', '#customer_type_id', function() {
            let customer_type_id = $(this).val();
            if (customer_type_id === 'dining_in') {
                $('.dining_filters').removeClass('hide');
            } else {
                $('.dining_filters').addClass('hide');
            }
        })
        $(document).on('click', '.clear_filter', function() {
            $('.sale_filter').val('');
            $('.sale_filter').selectpicker('refresh');
            sales_table.ajax.reload();
        });
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

        function get_total_details() {
            $.ajax({
                method: 'GET',
                url: "{{route('admin.sale.getTotalDetails')}}",
                data: {
                    category_id : $("#category_id").val(),
                    brand_id : $("#brand_id").val(),
                    tax_id : $("#tax_id").val(),
                    customer_id : $("#customer_id").val(),
                    customer_type_id : $("#customer_type_id").val(),
                    store_id : $("#store_id").val(),
                    status_ : $("#status").val(),
                    method : $("#method").val(),
                    payment_status : $("#payment_status").val(),
                    start_date : $("#start_date").val(),
                    start_time : $("#start_time").val(),
                    end_date : $("#end_date").val(),
                    end_time : $("#end_time").val(),
                    payment_start_date : $("#payment_start_date").val(),
                    payment_start_time : $("#payment_start_time").val(),
                    payment_end_date : $("#payment_end_date").val(),
                    payment_end_time : $("#payment_end_time").val(),
                    created_by : $("#created_by").val(),
                    type_trans : $("#type_trans").val(),
                },
                success: function(result) {
                    $('.number_of_customer_span').text(result.customer_count)
                    $('.number_of_orders_span').text(result.sales_count)
                },
            });
        }
</script>
@endsection
