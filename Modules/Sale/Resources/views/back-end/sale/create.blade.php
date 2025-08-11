@extends('layouts.app')
@section('title', __('lang.add_sale'))

@section('content')
<section class="forms py-2">

    <div class="container-fluid px-2 no-print">
        <div class="row">
            <div class="col-md-12">

                <x-page-title>
                    <h4>@lang('lang.add_sale')</h4>
                </x-page-title>



                {!! Form::open(['url' => route('admin.pos.store'), 'method' => 'post', 'files' =>
                true, 'class' => 'pos-form', 'id' => 'add_sale_form']) !!}
                {{-- <input type="hidden" name="is_add_stock" id="is_add_stock" value="1"> --}}
                <input type="hidden" name="default_customer_id" id="default_customer_id"
                    value="@if(!empty($walk_in_customer)){{$walk_in_customer->id}}@endif">
                <input type="hidden" name="row_count" id="row_count" value="0">

                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-3">
                                {!! Form::label('customer_id', __('lang.customer'), [
                                'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                <div class="input-group my-group">
                                    {!! Form::select('customer_id', $customers,
                                    !empty($walk_in_customer) ? $walk_in_customer->id : null, ['class' =>
                                    'selectpicker form-control', 'data-live-search'=>"true",
                                    'style' =>'width: 80%' , 'id' => 'customer_id']) !!}
                                    <span class="input-group-btn">
                                        @can('customer_module.customer.create_and_edit')
                                        <button class="btn-modal btn btn-primary  btn-partial btn-flat"
                                            data-href="{{action('CustomerController@create')}}?quick_add=1"
                                            data-container=".view_modal"><i
                                                class="fa fa-plus-circle text-white fa-lg"></i></button>
                                        @endcan
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                {!! Form::label('transaction_date', __('lang.date_and_time'), [
                                'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                <input type="datetime-local" id="transaction_date" name="transaction_date"
                                    value="{{date('Y-m-d\TH:i')}}" class="form-control">

                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('store_id', __('lang.store'). '*', [
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::select('store_id', $stores,
                                    $store_pos->store_id ?? null, ['class' => 'selectpicker form-control',
                                    'data-live-search'=>"true",
                                    'required',
                                    'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('store_pos_id', __('lang.pos'). '*', [
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::select('store_pos_id', $store_poses,
                                    $store_pos->id ?? null, ['class' => 'selectpicker form-control',
                                    'data-live-search'=>"true",
                                    'required',
                                    'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div
                            class="row justify-content-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                            <div class="col-md-2 mb-2">
                                @include('quotation.partial.product_selection')
                            </div>

                            <div class="col-md-12">
                                <div class="search-box input-group">
                                    <button type="button" class="btn btn-secondary btn-lg" id="search_button"><i
                                            class="fa fa-search"></i></button>
                                    <input type="text" name="search_product" id="search_product"
                                        placeholder="@lang('lang.enter_product_name_to_print_labels')"
                                        class="form-control ui-autocomplete-input" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-12">
                                <div class="table-responsive transaction-list">
                                    <table id="product_table" style="width: 100% " class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width: 20%">{{__('lang.product')}}</th>
                                                <th style="width: 15%">{{__('lang.quantity')}}</th>
                                                <th style="width: 10%">{{__('lang.price')}}</th>
                                                <th style="width: 10%">{{__('lang.discount')}}</th>
                                                <th style="width: 15%">{{__('lang.category_discount')}}</th>
                                                <th style="width: 10%">{{__('lang.sub_total')}}</th>
                                                @if(session('system_mode') != 'restaurant')
                                                <th style="width: 10%">{{__('lang.current_stock')}}</th>
                                                @endif
                                                {{-- <th style="width: 20%"></th> --}}
                                                <th style="width: 10%;">
                                                    @lang('lang.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <th style="text-align: right">@lang('lang.total')</th>
                                                <th><span class="grand_total_span"></span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif"
                    style="display: none;">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="hidden" id="final_total" name="final_total" />
                            <input type="hidden" id="grand_total" name="grand_total" />
                            <input type="hidden" id="gift_card_id" name="gift_card_id" />
                            <input type="hidden" id="coupon_id" name="coupon_id">
                            <input type="hidden" id="total_tax" name="total_tax" value="0.00">
                            <input type="hidden" id="is_direct_sale" name="is_direct_sale" value="1">
                            <input type="hidden" name="discount_amount" id="discount_amount">
                        </div>
                    </div>
                </div>


                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"
                                        for="tax_id">@lang('lang.tax')</label>
                                    <select class="form-control" name="tax_id" id="tax_id">
                                        <option value="" selected>No Tax</option>
                                        @foreach ($taxes as $tax)
                                        <option data-rate="{{$tax->rate}}" value="{{$tax->id}}">{{$tax->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('discount_type', __( 'lang.discount_type' ) . '*' ,[
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::select('discount_type', ['fixed' => 'Fixed', 'percentage' =>
                                    'Percentage'],
                                    'fixed', ['class' =>
                                    'form-control', 'data-live-search' => 'true']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('discount_value', __( 'lang.discount_value' ) . '*',[
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::text('discount_value', null, ['class' => 'form-control', 'placeholder'
                                    =>
                                    __(
                                    'lang.discount_value' )])
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('status', __( 'lang.status' ) . '*',[
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::select('status', ['final' => 'Completed', 'pending' => 'Pending'],
                                    'final', ['class' =>
                                    'form-control', 'data-live-search' => 'true']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="exchange_rate" id="exchange_rate" value="1">
                                    <input type="hidden" name="default_currency_id" id="default_currency_id"
                                        value="{{ !empty(App\Models\System::getProperty('currency')) ? App\Models\System::getProperty('currency') : '' }}">
                                    {!! Form::label('received_currency_id', __('lang.received_currency') , [
                                    'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                    ]) !!}
                                    {!! Form::select('received_currency_id', $exchange_rate_currencies,
                                    !empty(App\Models\System::getProperty('currency')) ?
                                    App\Models\System::getProperty('currency') : null, ['class' => 'form-control
                                    selectpicker', 'data-live-search' => 'true']) !!}
                                </div>
                            </div>
                        </div>


                        <div id="payment_rows" class="col-md-12">
                            <div class="row payment_row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('payment_status', __('lang.payment_status'). '*', [
                                        'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                        ])
                                        !!}
                                        {!! Form::select('payment_status', $payment_status_array,
                                        'paid', ['class' => 'selectpicker form-control',
                                        'data-live-search'=>"true", 'required',
                                        'style' =>'width: 80%' , 'placeholder' => __('lang.please_select')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-3 mt-1 payment_fields ">
                                    <label
                                        class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.payment_method')</label>
                                    {!! Form::select('payments[0][method]', $payment_types, null, ['class' =>
                                    'form-control',
                                    'id' =>
                                    'method', 'required', 'data-live-search' => 'true']) !!}
                                </div>
                                <div class="col-md-3 mt-1 payment_fields ">
                                    <label
                                        class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.received_amount')
                                        *</label>
                                    <input type="text" name="payments[0][amount]"
                                        class="form-control numkey received_amount" required id="amount" step="any">
                                </div>
                                <div class="col-md-3 mt-1 payment_fields ">
                                    <label
                                        class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.paying_amount')
                                        *</label>
                                    <input type="text" name="paying_amount" class="form-control numkey"
                                        id="paying_amount" step="any">
                                </div>
                                <div class="col-md-3 payment_fields hide mt-1">
                                    <label class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"
                                        class="change_text">@lang('lang.change') : </label>
                                    <span class="change" class="ml-2">0.00</span>
                                    <input type="hidden" name="payments[0][change_amount]" class="change_amount"
                                        value="0">
                                    <input type="hidden" name="payments[0][pending_amount]" class="pending_amount">
                                </div>

                                <div class="col-md-12 hide">
                                    <div class="i-checks toggle-pill-color flex-col-centered">
                                        <input id="print_the_transaction" name="print_the_transaction" type="checkbox"
                                            checked value="1" class="form-control-custom">
                                        <label
                                            class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"
                                            for="print_the_transaction">
                                        </label>
                                        <span>
                                            <strong>@lang('lang.print_the_transaction')</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt-3 card_field payment_fields hide">
                                    <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                                        <div class="col-md-4">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.card_number')
                                                *</label>
                                            <input type="text" name="payments[0][card_number]" class="form-control">
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.card_security')</label>
                                            <input type="text" name="payments[0][card_security]" class="form-control">
                                        </div> --}}
                                        <div class="col-md-2">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.month')</label>
                                            <input type="text" name="payments[0][card_month]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.year')</label>
                                            <input type="text" name="payments[0][card_year]" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 cheque_field payment_fields hide">
                                    <label
                                        class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.cheque_number')
                                        *</label>
                                    <input type="text" name="payments[0][cheque_number]" class="form-control">
                                </div>
                                <div class="form-group col-md-12 gift_card_field hide">
                                    <div class="col-md-12">
                                        <label
                                            class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.gift_card_number')
                                            *</label>
                                        <input type="text" name="payments[0][gift_card_number]" id="gift_card_number"
                                            class="form-control" placeholder="@lang('lang.enter_gift_card_number')">
                                        <span class="gift_card_error" style="color: red;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"><b>@lang('lang.current_balance'):</b>
                                            </label><br>
                                            <span class="gift_card_current_balance"></span>
                                            <input type="hidden" name="payments[0][gift_card_current_balance]"
                                                id="gift_card_current_balance">
                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.enter_amount_to_be_used')
                                            </label>
                                            <input type="text" name="payments[0][amount_to_be_used]"
                                                id="amount_to_be_used" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.remaining_balance')
                                            </label>
                                            <input type="text" name="payments[0][remaining_balance]"
                                                id="remaining_balance" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"><b>@lang('lang.final_total'):</b>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="payments[0][gift_card_final_total]"
                                                id="gift_card_final_total" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label
                                        class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.payment_note')</label>
                                    <textarea id="payment_note" rows="2" class="form-control"
                                        name="payment_note"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="col-md-3  btn-add-payment">
                                <button type="button" id="add_payment_row" class="btn btn-primary btn-block">
                                    @lang('lang.add_payment_row')</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-6 form-group">
                                <label
                                    class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.sale_note')</label>
                                <textarea rows="3" class="form-control" name="sale_note"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label
                                    class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif">@lang('lang.staff_note')</label>
                                <textarea rows="3" class="form-control" name="staff_note"></textarea>
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('terms_and_condition_id', __('lang.terms_and_conditions'), [
                                'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                ]) !!}
                                <div class="input-group my-group">
                                    {!! Form::select('terms_and_condition_id', $tac,
                                    null, ['class' =>
                                    'selectpicker form-control', 'data-live-search'=>"true",
                                    'style' =>'width: 80%' , 'id' => 'terms_and_condition_id']) !!}
                                </div>
                                <div class="tac_description_div"><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-12">
                                <button type="submit" name="action" value="submit"
                                    class="btn btn-primary">@lang('lang.save')</button>
                                <button type="button" class="btn btn-success" name="action" value="print"
                                    id="print-btn">
                                    @lang('lang.print')
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#sendModal">
                                    @lang('lang.send')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <x-modal-header>
                                <h5 class="modal-title" id="sendModalLabel">@lang('lang.emails')</h5>
                            </x-modal-header>

                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <small class="text-muted"
                                            style="font-size: 12px">@lang('lang.separated_by_comma')</small>
                                        {!! Form::text('emails', null, ['class' => 'form-control', 'id' =>
                                        'emails']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default col-6"
                                    data-dismiss="modal">@lang('lang.close')</button>
                                <button type="submit" name="action" value="send" id="send-btn"
                                    class="btn btn-primary col-6">@lang('lang.send')</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<!-- This will be printed -->
<section class="invoice print_section print-only" id="receipt_section"> </section>
@endsection

@section('javascript')
<script src="{{asset('js/pos.js')}}"></script>
<script src="{{asset('js/product_selection.js')}}"></script>
<script>
    $(document).on('click', '#add-selected-btn', function(){
        $('#select_products_modal').modal('hide');
        $.each(product_selected, function(index, value){
            get_label_product_row(value.product_id, value.variation_id);
        });
        product_selected = [];
        product_table.ajax.reload();
    })
    $(document).on("change", "#method", function (e) {
    let method = $(this).val();


    if (method === "cheque") {
        $(".cheque_field").removeClass("hide");
    } else {
        $(".cheque_field").addClass("hide");
    }
    if (method === "card") {
        $(".card_field").removeClass("hide");
    } else {
        $(".card_field").addClass("hide");
    }
    if (method === "gift_card") {
        $(".gift_card_field").removeClass("hide");
    } else {
        $(".gift_card_field").addClass("hide");
    }
    if (method === "cash") {
        $(".qc").removeClass("hide");
    } else {
        $(".qc").addClass("hide");
    }
});

 //payment related script

 $('#payment_status').change(function(){
        var payment_status = $(this).val();

        if(payment_status === 'paid' || payment_status === 'partial'){
            $('.not_cash_fields').addClass('hide');
            $('#method').change();
            $('.payment_fields').removeClass('hide');
        }else{
            $('.payment_fields').addClass('hide');
        }
        if(payment_status === 'pending' || payment_status === 'partial'){
            $('.due_fields').removeClass('hide');
        }else{
            $('.due_fields').addClass('hide');
        }
        if(payment_status === 'pending'){
            $('.not_cash_fields').addClass('hide');
            $('.not_cash').attr('required', false);
            $('#method').val();
            $('#amount').val(0);
            $('#method').selectpicker('refresh');
        }
        if(payment_status === 'paid'){
            $('.due_fields').addClass('hide');
        }

        $('#method').change();
    })

    //on click event jquery
    $(document).on('click', '#send-btn', function () {
        if($('#add_sale_form').valid()){
            $('#add_sale_form').submit()
        }
    });

    $(document).on('click', '#print-btn', function () {
        var form = $('#add_sale_form');
        if(form.valid()){
            var data = $(form).serialize();
            data =
                data +"&action=print"
            var url = $(form).attr("action");
            $.ajax({
                method: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (result) {
                    if (result.success == 1) {
                        pos_print(result.html_content);
                        reset_pos_form();
                    } else {
                        toastr.error(result.msg);
                    }

                },
            });
        }
    });

    $(document).on('change', '#emails', function () {
        //remove white space from string javascript
        var email_list = $(this).val().replace(/\s/g, '');
        $(this).val(email_list);
    });
</script>
@endsection
