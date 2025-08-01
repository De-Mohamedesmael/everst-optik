<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.transactionPayment.payCustomerDue', $customer->id), 'method' =>
        'post', 'id' => 'pay_customer_due_form' ])
        !!}


        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">
                @lang('lang.pay_customer_due')

            </h5>

            <button type="button" data-dismiss="modal" aria-label="Close"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                    aria-hidden="true" style="border-radius: 10px !important;"><i
                        class="dripicons-cross"></i></span></button>

        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" value="{{$extract_due}}" name="extract_due" />
                            <label for="">@lang('lang.customer_name'): {{$customer->name}}</label> <br>
                            <label for="">@lang('lang.mobile'): {{$customer->mobile}}</label><br>
                            <label for="">@lang('lang.address'): {{$customer->address}}</label><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('amount', __('lang.amount'). ':*', []) !!} <br>
                            {!! Form::text('amount', @num_format($due), ['class' => 'form-control', 'placeholder'
                            => __('lang.amount')]) !!}
                            <input type="hidden" value="{{@num_format($due)}}" name="balance" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('method', __('lang.payment_type'). ':*', []) !!}
                            {!! Form::select('method', $payment_type_array,
                            null, ['class' => 'selectpicker form-control',
                            'data-live-search'=>"true", 'required',
                            'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('paid_on', __('lang.payment_date'). ':', []) !!} <br>
                            {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control
                            datepicker', 'readonly','required',
                            'placeholder' => __('lang.payment_date')]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('upload_documents', __('lang.upload_documents'). ':', []) !!} <br>
                            {!! Form::file('upload_documents[]', null, ['class' => '']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('ref_number', __('lang.ref_number'). ':', []) !!} <br>
                            {!! Form::text('ref_number', null, ['class' => 'form-control not_cash',
                            'placeholder' => __('lang.ref_number')]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('bank_deposit_date', __('lang.bank_deposit_date'). ':', []) !!} <br>
                            {!! Form::text('bank_deposit_date', null, ['class' => 'form-control not_cash datepicker',
                            'readonly',
                            'placeholder' => __('lang.bank_deposit_date')]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 not_cash_fields hide">
                        <div class="form-group">
                            {!! Form::label('bank_name', __('lang.bank_name'). ':', []) !!} <br>
                            {!! Form::text('bank_name', null, ['class' => 'form-control not_cash',
                            'placeholder' => __('lang.bank_name')]) !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary col-6">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-outline-danger col-6" data-dismiss="modal">@lang( 'lang.close'
                )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $('.selectpicker').selectpicker('refresh');
    $('.datepicker').datepicker({
        language: '{{session('language')}}',
        todayHighlight: true,

    });
    $('#method').change(function(){
        var method = $(this).val();

        if(method === 'cash'){
            $('.not_cash_fields').addClass('hide');
            $('.not_cash').attr('required', false);
        }else{
            $('.not_cash_fields').removeClass('hide');
            $('.not_cash').attr('required', true);
        }
    })
</script>