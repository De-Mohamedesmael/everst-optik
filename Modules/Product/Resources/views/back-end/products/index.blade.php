@extends('back-end.layouts.app')
@section('title', __('lang.products'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
    <style>
        .dropdown-menu.edit-options li {
            padding: 0px !important;
        }
        .btn-group .dropdown-toggle::after {
            color: #fff;
        }

    </style>
@endsection


@section('breadcrumbs')
    @parent

    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
       @if(isset($page))  {{translate($page)}}@else @lang('lang.products') @endif </li>
@endsection

@section('button')
    <div class="row">
        @can('product_module.products.create_and_edit')
            <div class="col-md-6">
                <a style="color: white" href="{{ route('admin.products.create') }}"
                   class="btn btn-primary w-100 py-1"><i class="dripicons-plus"></i>
                    @lang('lang.add_product')</a>
            </div>
        @endcan
        @if (empty($page))
            <div class="col-md-6">
                <a style="color: white" href="{{  route('admin.products.getImport') }}"
                   class="btn btn-success w-100 py-1"><i class="fa fa-arrow-down"></i>
                    @lang('lang.import')</a>
            </div>
        @else
            <div class="col-md-6">
                <a style="color: white" href="{{ route('admin.add-stock.getImport') }}"
                   class="btn btn-success w-100 py-1"><i class="fa fa-arrow-down"></i>
                    @lang('lang.import')</a>
            </div>
        @endif

    </div>
@endsection
@section('content')
    <section class="forms px-3 py-1">
        <div class="container-fluid">
            @if (request()->segment(1) == 'products')
                <div
                    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h5 class="mb-0 position-relative" style="margin-right: 30px">
                        @lang('lang.product_lists')
                        <span class="header-pill"></span>
                    </h5>
                </div>
            @endif
            @if (request()->segment(1) == 'product-stocks')
                <div
                    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h5 class="mb-0 position-relative" style="margin-right: 30px">
                        @lang('lang.product_stocks')
                        <span class="header-pill"></span>
                    </h5>
                </div>
            @endif

            @include('product::back-end.products.partial.filter')

            <div class="card my-2">

                <div class="table-responsive" style="height: 60vh">
                    <table id="product_table" class="table table-hover">
                        <div style="overflow: auto; width: 100%;height: 10px; transform:rotateX(180deg);">
                        </div>
                        <thead>
                        <tr>
                            <th>@lang('lang.show_at_the_main_pos_page')</th>
                            <th>@lang('lang.image')</th>
                            <th style="">@lang('lang.name')</th>
                            <th>@lang('lang.product_code')</th>
                            <th>@lang('lang.categories')  </th>
                            <th>@lang('lang.select_to_delete')
                                <input type="checkbox" name="product_delete_all" class="product_delete_all mx-1"/>
                            </th>
                            <th>@lang('lang.purchase_history')</th>
                            <th>@lang('lang.batch_number')</th>
                            <th>@lang('lang.selling_price')</th>
                            <th>@lang('lang.tax')</th>
                            <th>@lang('lang.brand')</th>
                            <th>@lang('lang.color')</th>
                            <th>@lang('lang.size')</th>
                            <th class="sum">@lang('lang.current_stock')</th>
                            <th class="sum">@lang('lang.current_stock_value')</th>
                            <th>@lang('lang.manufacturing_date')</th>
                            <th>@lang('lang.discount')</th>
                            @can('product_module.purchase_price.view')
                                <th>@lang('lang.purchase_price')</th>
                            @endcan
                            <th>@lang('lang.active')</th>
                            <th>@lang('lang.created_by')</th>
                            <th>@lang('lang.date_of_creation')</th>
                            <th>@lang('lang.edited_by')</th>
                            <th>@lang('lang.edited_at')</th>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th style="text-align: right">@lang('lang.total')</th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

    </section>
@endsection
@push('javascripts')
    <script>
        $(document).on('click', '#delete_all', function () {
            var checkboxes = document.querySelectorAll('input[name="product_selected_delete"]');
            var selected_delete_ids = [];
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selected_delete_ids.push(checkboxes[i].value);
                }
            }
            if (selected_delete_ids.length == 0) {
                swal({
                    title: 'Warning',
                    text: LANG.sorry_you_should_select_products_to_continue_delete,
                    icon: 'warning',
                })
            } else {
                swal({
                    title: 'Are you sure?',
                    text: LANG.all_transactions_related_to_this_products_will_be_deleted,
                    icon: 'warning',
                }).then(willDelete => {
                    if (willDelete) {
                        var check_password = $(this).data('check_password');
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        swal({
                            title: 'Please Enter Your Password',
                            content: {
                                element: "input",
                                attributes: {
                                    placeholder: "Type your password",
                                    type: "password",
                                    autocomplete: "off",
                                    autofocus: false,
                                },
                            },
                            inputAttributes: {
                                autocapitalize: 'off',
                                autoComplete: 'off',
                            },
                            focusConfirm: true
                        }).then((result) => {
                            if (result) {
                                $.ajax({
                                    url: check_password,
                                    method: 'POST',
                                    data: {
                                        value: result
                                    },
                                    dataType: 'json',
                                    success: (data) => {

                                        if (data.success == true) {
                                            swal(
                                                'Success',
                                                'Correct Password!',
                                                'success'
                                            );
                                            $.ajax({
                                                method: 'POST',
                                                url: "{{  route('admin.products.multiDeleteRow') }}",
                                                dataType: 'json',
                                                data: {
                                                    "ids": selected_delete_ids
                                                },
                                                success: function (result) {
                                                    if (result.success ==
                                                        true) {
                                                        swal(
                                                            'Success',
                                                            result.msg,
                                                            'success'
                                                        );
                                                        setTimeout(() => {
                                                            location
                                                                .reload();
                                                        }, 1500);
                                                        location.reload();
                                                    } else {
                                                        swal(
                                                            'Error',
                                                            result.msg,
                                                            'error'
                                                        );
                                                    }
                                                },
                                            });

                                        } else {
                                            swal(
                                                'Failed!',
                                                'Wrong Password!',
                                                'error'
                                            )

                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }


        });
        $(document).on('click', '.delete_product', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "@lang('lang.all_transactions_related_to_this_product_will_be_deleted')",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    Swal.fire({
                        title: "{!! __('lang.please_enter_your_password') !!}",
                        input: 'password',
                        inputAttributes: {
                            placeholder: "{!! __('lang.type_your_password') !!}",
                            autocomplete: 'off',
                            autofocus: true,
                        },
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        Swal.fire({
                                            title: 'Success',
                                            text: "{{translate('Correct Password!')}}",
                                            icon: 'success',
                                        });

                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: data,
                                            success: function (result) {
                                                if (result.success ==
                                                    true) {
                                                    Swal.fire({
                                                        title: 'Success',
                                                        text: result.msg,
                                                        icon: 'success',
                                                    });

                                                    setTimeout(() => {
                                                        location
                                                            .reload();
                                                    }, 1500);
                                                    location.reload();
                                                } else {
                                                    Swal.fire({
                                                        title: 'Error',
                                                        text: result.msg,
                                                        icon: 'error',
                                                    });

                                                }
                                            },
                                        });

                                    } else {
                                        Swal.fire({
                                            title: 'Failed!',
                                            text: "{{translate('Wrong Password!')}}",
                                            icon: 'error',
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush

@section('javascript')
    <script>
        $('#product_table').on('change', '.product_delete_all', function () {
            var isChecked = $(this).prop('checked');
            product_table.rows().nodes().to$().find('.product_selected_delete').prop('checked', isChecked);
        });
        $(document).ready(function () {
            product_table = $('#product_table').DataTable({
                lengthChange: true,
                paging: true,
                info: false,
                bAutoWidth: false,
                order: [],
                language: {
                    url: dt_lang_url,
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, 200, 500, -1],
                    [10, 25, 50, 75, 100, 200, 500, "All"],
                ],
                dom: "lBfrtip",
                // stateSave: true,
                buttons: buttons,
                processing: true,
                serverSide: true,
                aaSorting: [
                    [2, 'asc']
                ],
                "ajax": {
                    "url": "{{  route('admin.products.index') }}",
                    "data": function (d) {
                        d.product_id = $('#product_id').val();
                        d.category_id = $('#category_id').val();
                        d.brand_id = $('#brand_id').val();
                        d.color_id = $('#color_id').val();
                        d.size_id = $('#size_id').val();
                        d.tax_id = $('#tax_id').val();
                        d.store_id = $('#store_id').val();
                        d.customer_type_id = $('#customer_type_id').val();
                        d.active = $('#active').val();
                        d.created_by = $('#created_by').val();
                        d.created_at = $('#dat').val();
                        d.show_zero_stocks = $('#show_zero_stocks').val();

                    }
                },
                columnDefs: [{
                    "targets": [0, 3],
                    "orderable": false,
                    "searchable": true
                }],
                columns: [{
                    data: 'show_at_the_main_pos_page',
                    name: 'show_at_the_main_pos_page'
                },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'sku',
                        name: 'products.sku'
                    },
                    {
                        data: 'categories_names',
                        name: 'categories_names'
                    },
                    {
                        data: "selection_checkbox_delete",
                        name: "selection_checkbox_delete",
                        searchable: false,
                        orderable: false,
                    },

                     {
                        data: 'purchase_history',
                        name: 'purchase_history'
                    },
                    {
                        data: 'batch_number',
                        name: 'add_stock_lines.batch_number'
                    },
                    {
                        data: 'sell_price',
                        name: 'sell_price'
                    },
                    {
                        data: 'tax',
                        name: 'taxes.name'
                    },
                    {
                        data: 'brand',
                        name: 'brands.name'
                    },

                    {
                        data: 'color',
                        name: 'colors.name'
                    },
                    {
                        data: 'size',
                        name: 'sizes.name'
                    },

                    {
                        data: 'current_stock',
                        name: 'current_stock',
                        searchable: false
                        @if (empty($page))
                        , visible: false
                        @endif
                    },
                    {
                        data: 'current_stock_value',
                        name: 'current_stock_value',
                        searchable: false
                    },

                    {
                        data: 'manufacturing_date',
                        name: 'add_stock_lines.manufacturing_date'
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        searchable: false,
                    },
                    @can('product_module.purchase_price.view')
                        {
                            data: 'default_purchase_price',
                            name: 'default_purchase_price',
                            searchable: false
                        },
                    @endcan

                    {
                        data: 'active',
                        name: 'active'
                    },
                    {
                        data: 'created_by',
                        name: 'admins.name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'edited_by_name',
                        name: 'edited.name'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ],
                createdRow: function (row, data, dataIndex) {

                },
                fnDrawCallback: function (oSettings) {
                    var intVal = function (i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                                i :
                                0;
                    };

                    this.api()
                        .columns(".sum", {
                            page: "current"
                        })
                        .every(function () {
                            var column = this;
                            if (column.data().count()) {
                                var sum = column.data().reduce(function (a, b) {
                                    a = intVal(a);
                                    if (isNaN(a)) {
                                        a = 0;
                                    }

                                    b = intVal(b);
                                    if (isNaN(b)) {
                                        b = 0;
                                    }

                                    return a + b;
                                });
                                $(column.footer()).html(
                                    __currency_trans_from_en(sum, false)
                                );
                            }
                        });
                },
            });

        });


        $(document).ready(function () {
            var hiddenColumnArray = JSON.parse('{!! addslashes(json_encode(Cache::get('key_' . auth()->id(), []))) !!}');

            $.each(hiddenColumnArray, function (index, value) {
                $('.column-toggle').each(function () {
                    if ($(this).val() == value) {
                        // alert(value)
                        toggleColumnVisibility(value, $(this));
                    }
                });
            });

            $(document).on('click', '.column-toggle', function () {
                var column_index = parseInt($(this).val());
                toggleColumnVisibility(column_index, $(this));

                if (hiddenColumnArray.includes(column_index)) {
                    hiddenColumnArray.splice(hiddenColumnArray.indexOf(column_index), 1);
                } else {
                    hiddenColumnArray.push(column_index);
                }

                hiddenColumnArray = [...new Set(hiddenColumnArray)]; // Remove duplicates

                // Update the columnVisibility cache data
                $.ajax({
                    url: '/update-column-visibility', // Replace with your route or endpoint for updating cache data
                    method: 'POST',
                    data: {
                        columnVisibility: hiddenColumnArray
                    },
                    success: function () {
                        console.log('Column visibility updated successfully.');
                    }
                });
            });

            function toggleColumnVisibility(column_index, this_btn) {
                var column = product_table.column(column_index);
                column.visible(!column.visible());

                if (column.visible()) {
                    $(this_btn).addClass('badge-primary').removeClass('badge-warning');
                } else {
                    $(this_btn).removeClass('badge-primary').addClass('badge-warning');
                }
            }
        });

        $(document).on('change', '.filter_product', function () {
            console.log($('#category_id').val());
            product_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function () {
            $('.filter_product').val('');
            $('.filter_product').selectpicker('refresh');
            $('#product_id').val('');
            $('.show_zero_stocks').val(1);
            product_table.ajax.reload();
        });
        $(document).on('change', '.show_zero_stocks', function () {
            if (this.checked) {
                $('.show_zero_stocks').val(0);
            } else {
                $('.show_zero_stocks').val(1);
            }
            product_table.ajax.reload();
        });

        @if (!empty(request()->product_id))
        $(document).ready(function () {
            $('#product_id').val({{ request()->product_id }});
            product_table.ajax.reload();

            var container = '.view_modal';
            $.ajax({
                method: 'get',
                url: "{{  route('admin.products.show',request()->product_id) }}",
                dataType: 'html',
                success: function (result) {
                    $(container).html(result).modal('show');
                },
            });
        });
        @endif

        $(document).on('click', '.delete_product', function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_transactions_related_to_this_product_will_be_deleted')",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    swal({
                        title: 'Please Enter Your Password',
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your password",
                                type: "password",
                                autocomplete: "off",
                                autofocus: true,
                            },
                        },
                        inputAttributes: {
                            autocapitalize: 'off',
                            autoComplete: 'off',
                        },
                        focusConfirm: true
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        swal(
                                            'Success',
                                            'Correct Password!',
                                            'success'
                                        );

                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: data,
                                            success: function (result) {
                                                if (result.success ==
                                                    true) {
                                                    swal(
                                                        'Success',
                                                        result.msg,
                                                        'success'
                                                    );
                                                    setTimeout(() => {
                                                        location
                                                            .reload();
                                                    }, 1500);
                                                    location.reload();
                                                } else {
                                                    swal(
                                                        'Error',
                                                        result.msg,
                                                        'error'
                                                    );
                                                }
                                            },
                                        });

                                    } else {
                                        swal(
                                            'Failed!',
                                            'Wrong Password!',
                                            'error'
                                        )

                                    }
                                }
                            });
                        }
                    });
                }
            });
        });
        $(document).on('change', '.show_at_the_main_pos_page', function (e) {
            $.ajax({
                type: "GET",
                url: "/dashboard/products/toggle-appearance-pos/" + $(this).data('id'),
                data: {
                    check: $(this).is(":checked") ? 'yes' : 'no'
                },
                // dataType: "dataType",
                success: function (response) {
                    if (response) {
                        $(this).removeAttr('checked');
                        $(this).attr('checked', false);
                        swal(response.success, response.msg, response.status);
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#productsFilterCollapse').on('show.bs.collapse', function () {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#productsFilterCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#productsFilterCollapse').on('hide.bs.collapse', function () {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#productsFilterCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#productsOtherFilterCollapse').on('show.bs.collapse', function () {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#productsOtherFilterCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#productsOtherFilterCollapse').on('hide.bs.collapse', function () {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#productsOtherFilterCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
@endsection
