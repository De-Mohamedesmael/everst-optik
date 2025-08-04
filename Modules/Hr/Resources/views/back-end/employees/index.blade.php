@extends('back-end.layouts.app')
@section('title', __('lang.employee'))
@section('styles')
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
        @lang('lang.employees')</li>
@endsection

@section('button')
    <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
        <a class="btn btn-primary" href="{{ route('admin.hr.employees.create') }}">@lang('lang.add_employee')</a>
    </div>
@endsection
@section('content')
    <div class="animate-in-page">

        <div class="container-fluid">
            <div class="col-md-12  no-print">
                <div class="card mt-1">
                    <div
                            class="card-header d-flex align-items-center @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <h6 class="print-title">
                            @lang('lang.employees')</h6>
                    </div>
                    <div class="card-body">
                        <div class="wrapper1 @if (app()->isLocale('ar')) dir-rtl @endif">
                            <div class="div1"></div>
                        </div>
                        <div class="wrapper2 @if (app()->isLocale('ar')) dir-rtl @endif">
                            <div class="div2 table-scroll-wrapper">
                                <!-- content goes here -->
                                <div style="min-width: 1300px;max-height: 90vh;overflow: auto">
                                    <table id="employee_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.profile_photo')</th>
                                            <th>@lang('lang.employee_name')</th>
                                            <th>@lang('lang.email')</th>
                                            <th>@lang('lang.mobile')</th>
                                            <th>@lang('lang.job_title')</th>
                                            <th class="sum">@lang('lang.wage')</th>
                                            <th>@lang('lang.annual_leave_balance')</th>
                                            <th>@lang('lang.age')</th>
                                            <th>@lang('lang.start_working_date')</th>
                                            <th>@lang('lang.current_status')</th>
                                            <th>@lang('lang.store')</th>
                                            <th>@lang('lang.pos')</th>
                                            <th>@lang('lang.commission')</th>
                                            <th>@lang('lang.total_paid')</th>
                                            <th>@lang('lang.pending')</th>
                                            <th class="notexport">@lang('lang.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td></td>
                                            <td>{{ $currency_symbol }}</td>
                                            <td></td>
                                            <th style="text-align: center">@lang('lang.total')</th>
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
@endsection

@section('javascript')
    <script>
        $(document).on('click', 'a.toggle-active', function(e) {
            e.preventDefault();

            $.ajax({
                method: 'get',
                url: $(this).data('href'),
                data: {},
                success: function(result) {
                    if (result.success == true) {
                        new PNotify({
                            title: 'Success',
                            text: result.msg,
                            type: 'success'
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        new PNotify({
                            title: 'Error',
                            text:result.msg,
                            type: 'Error'
                        });

                    }
                },
            });
        });
        $(document).ready(function() {
            employee_table = $("#employee_table").DataTable({
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
                    url: "{{route('admin.hr.employees.index')}}",
                    data: function(d) {
                        d.employee_id = $("#employee_id").val();
                        d.method = $("#method").val();
                        d.start_date = $("#start_date").val();
                        d.start_time = $("#start_time").val();
                        d.end_date = $("#end_date").val();
                        d.end_time = $("#end_time").val();
                        d.created_by = $("#created_by").val();
                        d.payment_status = $("#payment_status").val();
                    },
                },
                columnDefs: [{
                        targets: "date",
                        type: "date-eu",
                    },
                    {
                        targets: [7],
                        orderable: false,
                        searchable: false,
                    },
                ],
                columns: [{
                        data: "profile_photo",
                        name: "profile_photo"
                    },
                    {
                        data: "employee_name",
                        name: "employee_name"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "mobile",
                        name: "mobile"
                    },
                    {
                        data: "job_title",
                        name: "job_types.job_title"
                    },
                    {
                        data: "fixed_wage_value",
                        name: "employees.fixed_wage_value"
                    },
                    {
                        data: "annual_leave_balance",
                        name: "annual_leave_balance",
                        searchable: false
                    },
                    {
                        data: "age",
                        name: "age",

                    },
                    {
                        data: "date_of_start_working",
                        name: "date_of_start_working"
                    },
                    {
                        data: "current_status",
                        name: "current_status"
                    },
                    {
                        data: "store",
                        name: "store",
                    },
                    {
                        data: "store_pos",
                        name: "store_pos",
                    },
                    {
                        data: "commission",
                        name: "commission",
                    },
                    {
                        data: "total_paid",
                        name: "total_paid",
                    },
                    {
                        data: "due",
                        name: "due",
                    },
                    {
                        data: "action",
                        name: "action"
                    },
                ],
                createdRow: function(row, data, dataIndex) {},
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[, Rs]|(\.\d{2})/g, "") * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        },
                        total2 = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    totalRows = api.page.info().recordsDisplay;

                    $(api.column(5).footer()).html(
                        total2
                    );
                    $(api.column(0).footer()).html(
                        "{{ __('lang.total_rows') }}: " + totalRows
                    );
                },
            });
            $('#store_table_filter input').attr('autocomplete', 'off');
        })
    </script>
@endsection
