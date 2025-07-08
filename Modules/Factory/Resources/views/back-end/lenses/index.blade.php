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
        <a class="btn btn-primary" href="{{ route('admin.factories_lenses.create') }}">{{translate('add_request_lenses')}}</a>
    </div>
@endsection
@section('content')
    <section class="forms px-3 py-1">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table id="store_table" class="table" style="width: 100%">
                                    <thead>
                                        <tr>

                                            <th>@lang('lang.factory_name')</th>
                                            <th>@lang('lang.customer_name')</th>
                                            <th>@lang('lang.customer_phone')</th>
                                            <th>@lang('lang.lens_name')</th>
                                            <th>@lang('lang.lenses_price')</th>
                                            <th>@lang('lang.total_extra')</th>
                                            <th>@lang('lang.amount_total')</th>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.created_at')</th>
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
                    "url": "{{route('admin.factories_lenses.index')}}",
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
                        "targets": [7], // Column 6 (purchases)
                        "orderable": true, // Enable sorting for the "purchases" column
                        "searchable": true
                    }
                ],
                columns: [
                    {
                        data: 'factory_name',
                        name: 'factories.name'
                    },
                    {
                        data:'customer_name',
                        name: 'customers.name',
                    },
                    {
                        data:'customer_phone',
                        name: 'customers.mobile_number'
                    },
                    {
                        data: 'lens_name',
                        name: 'products.name'
                    },
                    {
                        data: 'amount_product',
                        name: 'amount_product'
                    },
                    {
                        data: 'total_extra',
                        name: 'total_extra'
                    },
                    {
                        data: 'amount_total',
                        name: 'amount_total'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
    </script>
@endsection
