@extends('back-end.layouts.app')
@section('title', __('lang.factories'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
    <style>
        .dropdown-menu.edit-options li a, .dropdown-menu.edit-options li .btn-link {
            color: var(--tertiary-color);
            display: block;
            text-align: left;
            text-decoration: none;
            width: 100%;
            padding: 6px 10px !important;
        }
        table .dropdown-menu a {
            font-weight: 700 !important;
            display: flex !important;
            justify-content: space-between !important;
            font-size: 13px !important;
            align-items: center !important;
        }
        .dt-buttons .table-btns {

            background-color: var(--complementary-color-1) !important;
            color: white !important;
            border-radius: 6px !important;
            margin: 5px 0 !important;
            transition: 0.6s;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .btn.btn-default, .btn.btn-default:focus {
            border: 1px solid;
            background-color: #fff;
            color: var(--secondary-color);
        }
        .text-red {
            color: maroon !important;
        }
        .dropdown-menu.edit-options li {
            border-bottom: 1px solid #eee;
            padding: 0px !important;
        }
        sr-only {
            border: 0;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }
        .dropdown-toggle::after {
            color: var(--complementary-color-1) !important;
        }

    </style>
@endsection
@section('breadcrumbs')
    @parent

    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.factories')</li>
@endsection
@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <a class="btn btn-primary" href="{{ route('admin.factories.create') }}">{{translate('add_factory')}}</a>
    </div>
@endsection
@section('content')
    <section class="forms px-3 py-1">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    {{-- <div class="card mb-2 mt-2">
                        <div class="card-body p-2">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-2 px-5">
                                    <div class="form-group">
                                        {!! Form::label('start_date', __('lang.start_date'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::date('startdate', request()->start_date, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'id' => 'startdate',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-2 px-5">
                                    <div class="form-group">
                                        {!! Form::label('end_date', __('lang.end_date'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::date('enddate', request()->end_date, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'id' => 'enddate',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-2 px-5">
                                    <div class="form-group">
                                        {!! Form::label('customer_type_id', __('lang.customer_type'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('customer_type_id', $customer_types, request()->customer_type_id, [
                                            'class' => 'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker',
                                            'data-live-search' => 'true',
                                            'placeholder' => __('lang.all'),
                                            'id' => 'customer_type_id',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    {!! Form::label('gender', translate('gender') . '*', [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    {!! Form::select('gender', \Modules\Customer\Entities\Customer::getDropdownGender(), null , [
                                        'class' => 'selectpicker form-control',
                                        'data-live-search' => 'true',
                                        'required',
                                        'placeholder' => __('lang.all'),
                                    ]) !!}
                                </div>
                                <div class="col-md-2 px-5 d-flex justify-content-center align-items-center">
                                    <button type="button"
                                        class="btn btn-main col-md-12 filter_product">@lang('lang.filter')</button>
                                </div>
                                <div class="col-md-2 px-5 d-flex justify-content-center align-items-center">
                                    <button class="btn btn-danger col-md-12 clear_filters">@lang('lang.clear_filters')</button>

                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table id="store_table" class="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.factory_name')</th>
                                            <th>@lang('lang.email')</th>
                                            <th>@lang('lang.address')</th>
                                            <th>@lang('lang.country')</th>
                                            <th>@lang('lang.code')</th>
                                            <th>@lang('lang.postal_code')</th>
                                            <th>@lang('lang.phone')</th>
                                            <th>@lang('lang.owner_name')</th>
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
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            store_table = $('#store_table').DataTable({
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
                // searching: true,
                serverSide: true,
                aaSorting: [
                    [2, 'asc']
                ],
                // bPaginate: false,
                // bFilter: false,
                // bInfo: false,
                bSortable: true,
                bRetrieve: true,
                "ajax": {
                    "url": "/dashboard/factories/",
                    "data": function(d) {
                        // d.startdate = $('#startdate').val();
                        // d.enddate = $('#enddate').val();
                        // d.customer_type_id = $('#customer_type_id').val()
                        // d.gender = $('#gender').val()
                    }
                },
                columnDefs: [{
                        // "targets": [0,2, 3],
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "targets": [6], // Column 6 (purchases)
                        "orderable": true, // Enable sorting for the "purchases" column
                        "searchable": true
                    }
                ],
                columns: [{
                        data: 'factory_name',
                        name: 'factory_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'country',
                        name: 'country.arabic'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },{
                        data: 'postal_code',
                        name: 'postal_code'
                    },{
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'owner_name',
                        name: 'owner_name'
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



                },
            });

        });


        $(document).on('click', '.delete_customer', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_customer_transactions_will_be_deleted')",
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
                                            success: function(result) {
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
        $(document).on('click', '.filter_product', function() {
            store_table.ajax.reload();
        })
        $(document).on('click', '.clear_filters', function(e) {
            // e.preventDefault();
            $('#startdate').val('');
            $('#enddate').val('');
            $('#customer_type_id').val('')
            store_table.ajax.reload();
        });
    </script>
@endsection
