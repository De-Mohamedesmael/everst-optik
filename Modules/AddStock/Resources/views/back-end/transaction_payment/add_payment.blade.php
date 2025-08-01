<div class="modal-dialog" id="payment_modal" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.transaction-payment.store'), 'method' => 'post', 'id' =>
        'add_payment_form', 'enctype' => 'multipart/form-data']) !!}

        {{-- <div class="modal-content">--}}
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">
                    @lang('lang.add_payment')

                </h5>

                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true" style="border-radius: 10px !important;"><i
                            class="dripicons-cross"></i></span></button>

            </div>
            {{-- <div class="modal-body">--}}
                <div class="modal-body">
                    <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('amount', __('lang.amount') . ':*', []) !!} <br>
                                @if($balance >0 && $balance<$transaction->final_total -
                                    $transaction->transaction_payments->sum('amount'))
                                    {!! Form::text('amount', @num_format($transaction->final_total -
                                    $transaction->transaction_payments->sum('amount')-$balance), ['class' =>
                                    'form-control',
                                    'placeholder' => __('lang.amount')]) !!}
                                    @else
                                    {!! Form::text('amount', @num_format($transaction->final_total -
                                    $transaction->transaction_payments->sum('amount')), ['class' => 'form-control',
                                    'placeholder' => __('lang.amount')]) !!}
                                    @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('method', __('lang.payment_type') . ':*', []) !!}
                                {!! Form::select('method', $payment_type_array, 'cash', ['class' => 'selectpicker
                                form-control',
                                'data-live-search' => 'true', 'required', 'style' => 'width: 80%', 'placeholder' =>
                                __('lang.please_select')]) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('paid_on', __('lang.payment_date') . ':', []) !!} <br>
                                {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control
                                datepicker',
                                'readonly', 'required', 'placeholder' => __('lang.payment_date')]) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('upload_documents', __('lang.upload_documents') . ':', []) !!} <br>
                                <input type="file" name="upload_documents[]" id="upload_documents" multiple>
                            </div>
                        </div>
                        <div class="col-md-4 not_cash_fields card_field hide">
                            <div class="form-group">
                                {!! Form::label('ref_number', __('lang.ref_number') . ':', []) !!} <br>
                                {!! Form::text('ref_number', null, ['class' => 'form-control not_cash', 'placeholder' =>
                                __('lang.ref_number')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4 not_cash_fields hide">
                            <div class="form-group">
                                {!! Form::label('bank_deposit_date', __('lang.bank_deposit_date') . ':', []) !!} <br>
                                {!! Form::text('bank_deposit_date', @format_date(date('Y-m-d')), ['class' =>
                                'form-control
                                not_cash datepicker', 'readonly', 'placeholder' => __('lang.bank_deposit_date')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4 not_cash_fields hide">
                            <div class="form-group">
                                {!! Form::label('bank_name', __('lang.bank_name') . ':', []) !!} <br>
                                {!! Form::text('bank_name', null, ['class' => 'form-control not_cash', 'placeholder' =>
                                __('lang.bank_name')]) !!}
                            </div>
                        </div>

                        <div class="col-md-4 card_field hide">
                            <label>@lang('lang.card_number') *</label>
                            <input type="text" name="card_number" class="form-control">
                        </div>
                        <div class="col-md-2 card_field hide">
                            <label>@lang('lang.month')</label>
                            <input type="text" name="card_month" class="form-control">
                        </div>
                        <div class="col-md-2 card_field hide">
                            <label>@lang('lang.year')</label>
                            <input type="text" name="card_year" class="form-control">
                        </div>
                        @if ($transaction->type == 'add_stock')
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('source_type', __('lang.source_type'), []) !!} <br>
                                {!! Form::select('source_type', ['user' => __('lang.user'), 'pos' => __('lang.pos'),
                                'store' =>
                                __('lang.store'), 'safe' => __('lang.safe')], 'user', ['class' => 'selectpicker
                                form-control',
                                'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' =>
                                __('lang.please_select')]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('source_of_payment', __('lang.source_of_payment'), []) !!} <br>
                                {!! Form::select('source_id', $users, null, ['class' => 'selectpicker form-control',
                                'data-live-search' => 'true', 'style' => 'width: 80%', 'placeholder' =>
                                __('lang.please_select'), 'id' => 'source_id']) !!}
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

                <div class="modal-footer row">
                    <button type="button" id="submit_form_button"
                        class="btn btn-primary col-6">@lang('lang.save')</button>
                    <button type="button" id="close_modal_button" class="btn btn-outline-danger col-6"
                        data-dismiss="modal">@lang('lang.close')</button>
                </div>

                {!! Form::close() !!}

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

        <script>
            $(document).ready(function() {

        var pageTitle = window.location.pathname;
        console.log(pageTitle);
        $('#submit_form_button').click(function() {
            $('#add_payment_form').submit();
        });

        if(pageTitle!=="/pos/create"){
            $('#add_payment_form').submit(function(e) {
                e.preventDefault()
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response here
                        console.log(response);

                        $('#add_payment_form')[0].reset();
                        $('#close_modal_button').click();
                        $('#sales_table').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        // Handle error response here
                        console.log(error);
                    }
                });
            });
        }
    });
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
    $('.selectpicker').selectpicker('refresh');
    $('.datepicker').datepicker({
        language: '{{ session('language') }}',
        todayHighlight: true,

    });
    $('#add_payment_form #method').change(function() {
        var method = $(this).val();

        if (method === 'card') {
            $('.not_cash_fields').addClass('hide');
            $('.card_field').removeClass('hide');
            $('.not_cash').attr('required', false);
        } else if (method === 'cash') {
            $('.not_cash_fields').addClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', false);
        } else {
            $('.not_cash_fields').removeClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', false);
        }
    })
        </script>