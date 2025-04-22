@extends('back-end.layouts.app')
@section('title', __('lang.import_add_stock'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/main.css') }}">
@endsection
@section('content')
    <section class="forms px-3 py-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div
                        class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <h5 class="mb-0 position-relative" style="margin-right: 30px">
                            @lang('lang.import_add_stock')
                            <span class="header-pill"></span>
                        </h5>
                    </div>
                    {!! Form::open([
                        'url' => route('admin.add-stock.saveImport'),
                        'method' => 'post',
                        'id' => 'import_add_stock_form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('store_id', __('lang.store') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('store_id', $stores, 'Please Select', [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'required',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                    </div>
                                </div>


                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('status', __('lang.status') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select(
                                            'status',
                                            ['received' => 'Received', 'partially_received' => 'Partially Received', 'pending' => 'Pending'],
                                            'Please Select',
                                            [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'required',
                                                'style' => 'width: 80%',
                                                'placeholder' => __('lang.please_select'),
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    {!! Form::label('transaction_date', __('lang.date_and_time'), [
                                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                    ]) !!}
                                    <input type="datetime-local" id="transaction_date" name="transaction_date"
                                        value="{{ date('Y-m-d\TH:i') }}"
                                        class="form-control  modal-input @if (app()->isLocale('ar')) text-end @else text-start @endif">
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        <input type="hidden" name="exchange_rate" id="exchange_rate" value="1">
                                        <input type="hidden" name="default_currency_id" id="default_currency_id"
                                            value="{{ !empty(Modules\Setting\Entities\System::getProperty('currency')) ? Modules\Setting\Entities\System::getProperty('currency') : '' }}">
                                        {!! Form::label('paying_currency_id', __('lang.paying_currency'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select(
                                            'paying_currency_id',
                                            $exchange_rate_currencies,
                                            !empty(Modules\Setting\Entities\System::getProperty('currency')) ? Modules\Setting\Entities\System::getProperty('currency') : null,
                                            ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'required'],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="col-md-12">
                                <div
                                    class="row align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif justify-content-between">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {!! Form::label('file', __('lang.file'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            {!! Form::file('file', []) !!}
                                            <p>@lang('lang.download_info_add_stock')</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-block btn-primary"
                                            href="{{ asset('sample_files/add_stock_import.xlsx') }}"><i
                                                class="fa fa-download"></i>@lang('lang.download_sample_file')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <input type="hidden" name="final_total" id="final_total" value="0">

                            <div class="row">
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('files', __('lang.files'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        <input type="file" name="files[]" id="files" multiple>
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('invoice_no', __('lang.invoice_no'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('invoice_no', null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.invoice_no'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('other_expenses', __('lang.other_expenses'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('other_expenses', null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.other_expenses'),
                                            'id' => 'other_expenses',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('discount_amount', __('lang.discount'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('discount_amount', null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.discount'),
                                            'id' => 'discount_amount',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('other_payments', __('lang.other_payments'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('other_payments', null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.other_payments'),
                                            'id' => 'other_payments',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('source_type', __('lang.source_type'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select(
                                            'source_type',
                                            ['user' => __('lang.user'), 'pos' => __('lang.pos'), 'store' => __('lang.store'), 'safe' => __('lang.safe')],
                                            'user',
                                            [
                                                'class' => 'selectpicker form-control',
                                                'data-live-search' => 'true',
                                                'style' => 'width: 80%',
                                                'placeholder' => __('lang.please_select'),
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('source_of_payment', __('lang.source_of_payment'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('source_id', $admins, null, [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                            'id' => 'source_id',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-3 px-5">
                                    <div class="form-group">
                                        {!! Form::label('payment_status', __('lang.payment_status') . '*', [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::select('payment_status', $payment_status_array, 'paid', [
                                            'class' => 'selectpicker form-control',
                                            'data-live-search' => 'true',
                                            'required',
                                            'style' => 'width: 80%',
                                            'placeholder' => __('lang.please_select'),
                                        ]) !!}
                                    </div>
                                </div>

                                @include('addstock::back-end.add_stock.partials.payment_form')

                                <div class="col-md-3 px-5 due_amount_div hide">
                                    <label for="due_amount"
                                        class="form-label d-block mb-0  @if (app()->isLocale('ar')) text-end @else text-start @endif">@lang('lang.due')
                                        <span class="due_amount_span">{{ @num_format(0) }}</span></label>
                                </div>

                                <div class="col-md-3 px-5 due_fields hide">
                                    <div class="form-group">
                                        {!! Form::label('due_date', __('lang.due_date'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}
                                        {!! Form::text('due_date', !empty($payment) ? $payment->due_date : null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start datepicker',
                                            'placeholder' => __('lang.due_date'),
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-3 px-5 due_fields hide">
                                    <div class="form-group">
                                        {!! Form::label('notify_before_days', __('lang.notify_before_days'), [
                                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                        ]) !!}

                                        {!! Form::text('notify_before_days', !empty($payment) ? $payment->notify_before_days : null, [
                                            'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                            'placeholder' => __('lang.notify_before_days'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex my-2  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                        <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#detailsCollapse" aria-expanded="false" aria-controls="detailsCollapse">
                            <i class="fas fa-arrow-down"></i>
                            @lang('lang.product_details')
                            <span class="toggle-pill"></span>
                        </button>
                    </div>

                    <div class="collapse" id="detailsCollapse">
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('notes', __('lang.notes'), [
                                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                            ]) !!}
                                            {!! Form::textarea('notes', null, [
                                                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                                                'rows' => 3,
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-2">
                            <button type="submit" name="submit" id="print" value="save"
                                class="btn py-1  submit-btn submit btn btn-block btn-primary">@lang('lang.save')</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('js/add_stock.js') }}"></script>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script>
        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#detailsCollapse').on('show.bs.collapse', function() {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#detailsCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#detailsCollapse').on('hide.bs.collapse', function() {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#detailsCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });
    </script>
    <script>
        $('#po_no').change(function() {
            let po_no = $(this).val();

            if (po_no) {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-purchase-order-details/' + po_no,
                    data: {},
                    contentType: 'html',
                    success: function(result) {
                        $("table#product_table tbody").empty().append(result);
                        calculate_sub_totals()
                    },
                });
            }
        });
        $(document).on("click", '#submit-btn-add-products', function(e) {
            e.preventDefault();
            console.log('click');
            var sku = $('#sku').val();
            if ($("#products-form-quick-add").valid()) {
                tinyMCE.triggerSave();
                $.ajax({
                    type: "POST",
                    url: "/products",
                    data: $("#products-form-quick-add").serialize(),
                    success: function(response) {
                        if (response.success) {
                            swal("Success", response.msg, "success");;
                            $("#search_product").val(sku);
                            $('input#search_product').autocomplete("search");
                            $('.view_modal').modal('hide');
                        }
                    },
                    error: function(response) {
                        if (!response.success) {
                            swal("Error", response.msg, "error");
                        }
                    },
                });
            }
        });
        $(document).on("change", "#category_id", function() {
            $.ajax({
                method: "get",
                url: "/category/get-sub-category-dropdown?category_id=" +
                    $("#category_id").val(),
                data: {},
                contentType: "html",
                success: function(result) {
                    $("#sub_category_id").empty().append(result).change();
                    $("#sub_category_id").selectpicker("refresh");

                    if (sub_category_id) {
                        $("#sub_category_id").selectpicker("val", sub_category_id);
                    }
                },
            });
        });

        //payment related script
        $('#payment_status').change(function() {
            var payment_status = $(this).val();

            if (payment_status === 'paid' || payment_status === 'partial') {
                $('.not_cash_fields').addClass('hide');
                $('#method').change();
                $('.payment_fields').removeClass('hide');
            } else {
                $('.payment_fields').addClass('hide');
            }
            if (payment_status === 'pending' || payment_status === 'partial') {
                $('.due_fields').removeClass('hide');
            } else {
                $('.due_fields').addClass('hide');
            }
            if (payment_status === 'pending') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
            }
            if (payment_status === 'paid') {
                $('.due_fields').addClass('hide');
            }
        })
        $(document).ready(function() {
            $('#payment_status').change();
            $('#source_type').change();
        })
        $('#source_type').change(function() {
            if ($(this).val() !== '') {
                $.ajax({
                    method: 'get',
                    url: '/add-stock/get-source-by-type-dropdown/' + $(this).val(),
                    data: {},
                    success: function(result) {
                        $("#source_id").empty().append(result);
                        $("#source_id").selectpicker("refresh");
                    },
                });
            }
        });

        $('#method').change(function() {
            var method = $(this).val();

            if (method === 'cash') {
                $('.not_cash_fields').addClass('hide');
                $('.not_cash').attr('required', false);
            } else {
                $('.not_cash_fields').removeClass('hide');
                $('.not_cash').attr('required', true);
            }
        })
    </script>
@endsection
