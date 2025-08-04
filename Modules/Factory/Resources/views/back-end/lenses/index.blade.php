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
        <a class="btn btn-primary" href="{{ route('admin.factories.lenses.create') }}">{{translate('add_request_lenses')}}</a>
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
                                        <th>{{translate('factory_name')}}</th>
                                        <th>{{translate('lens_name')}}</th>
                                        <th>{{translate('amount_product')}}</th>
                                        <th>{{translate('total_extra')}}</th>
                                        <th>{{translate('amount_total')}}</th>
                                        <th>{{translate('date')}}</th>
                                        <th>{{translate('qrcode')}}</th>
                                        <th>{{translate('qr_code_image')}}</th>
                                        <th>{{translate('actions')}}</th>
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
                    [5, 'asc']
                ],
                // bPaginate: false,
                // bFilter: false,
                // bInfo: false,
                bSortable: true,
                bRetrieve: true,
                "ajax": {
                    "url": "{{route('admin.factories.lenses.index')}}",
                    "data": function(d) {
                        // d.startdate = $('#startdate').val();
                        // d.enddate = $('#enddate').val();
                        // d.customer_type_id = $('#customer_type_id').val()
                        // d.gender = $('#gender').val()
                    }
                },
                columnDefs: [{
                    "orderable": true,
                    "searchable": true
                },
                    {

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
                        data: 'scan_input',
                        name: 'scan_input',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'qr_code_image',
                        name: 'qr_code_image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });

        $(document).on('click', '.delete_customer', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "@lang('lang.all_customer_transactions_will_be_deleted')",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    Swal.fire({
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
                                        Swal.fire(
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
                                                    Swal.fire(
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
                                                    Swal.fire(
                                                        'Error',
                                                        result.msg,
                                                        'error'
                                                    );
                                                }
                                            },
                                        });

                                    } else {
                                        Swal.fire(
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
            $('#store_table_filter input').attr('autocomplete', 'off');

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

        // save qrcode
        $(document).on('change', '.scan-input', function () {
            let qr_value = $(this).val();
            let id = $(this).data('id');

            if (!qr_value) return;


            $.ajax({
                url: '/dashboard/factories/lenses/save-qr',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    qr_code: qr_value
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ بنجاح',
                            text: response.message || '',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    // refresh the table
                    // store_table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map(e => e[0]).join('<br>');

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في التحقق',
                            html: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ غير متوقع',
                            text: 'حدث خطأ أثناء الحفظ، يرجى المحاولة مرة أخرى'
                        });
                    }
                }
            });
        });

        // sell via uts
        $(document).on('click', '.send-btn', function () {
            const id = $(this).data('id');
            const input = $('.scan-input[data-id="' + id + '"]').val();

            if (!input) {
                alert('يرجى إدخال القيمة أولاً');
                return;
            }

            $.ajax({
                url: '/dashboard/factories/lenses/sell_to_uts',
                type: 'POST',
                data: {
                    uno: input,
                    lot: 'LOT20250701',
                    adt: 1,
                    vrn: '2025-07-12',
                    vrnT: 'SATIS',
                    aciklama: 'إرسال يدوي',
                    aliciKurum: {
                        krn: '09876543210987',
                        tip: 'FIRMA'
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    alert('تم الإرسال بنجاح');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('حدث خطأ أثناء الإرسال');
                }
            });
        });



    </script>
@endsection
