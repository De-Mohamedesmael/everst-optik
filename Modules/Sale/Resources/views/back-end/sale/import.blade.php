@extends('layouts.app')
@section('title', __('lang.import_sale'))

@section('content')

<section class="forms py-2">

    <div class="container-fluid px-2">
        <div class="row">
            <div class="col-md-12">

                <x-page-title>


                    <h4>@lang('lang.import_sale')</h4>

                </x-page-title>


                {!! Form::open(['url' => action('SellController@saveImport'), 'method' => 'post', 'files' =>
                true, 'class' => 'pos-form', 'id' => 'import_sale_form']) !!}
                <input type="hidden" name="store_id" id="store_id" value="{{$store_pos->store_id}}">
                <input type="hidden" name="default_customer_id" id="default_customer_id"
                    value="@if(!empty($walk_in_customer)){{$walk_in_customer->id}}@endif">

                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

                            <div class="col-md-4">
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
                                        <button class="btn-modal btn btn-primary btn-partial btn-flat"
                                            data-href="{{action('CustomerController@create')}}?quick_add=1"
                                            data-container=".view_modal"><i
                                                class="fa fa-plus-circle text-white fa-lg"></i></button>
                                        @endcan
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"
                                        for="file">
                                        <input type="file" name="file" id="file" class=" w-100 h-100"
                                            style="opacity: 0;position: absolute;top: 0;left: -18px;">
                                        <div class="w-100 h-100 mb-1 py-2 text-center"
                                            style="border: 2px dashed var(--primary-color);cursor: pointer;">
                                            {{__('lang.attachment')}}
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <a class="btn btn-block btn-primary"
                                    href="{{asset('sample_files/sales_import.xlsx')}}"><i
                                        class="fa fa-download"></i>@lang('lang.download_sample_file')</a>
                            </div>


                            <div class="row" style="display: none;">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" id="final_total" name="final_total" />
                                        <input type="hidden" id="grand_total" name="grand_total" />
                                        <input type="hidden" id="gift_card_id" name="gift_card_id" />
                                        <input type="hidden" id="coupon_id" name="coupon_id">
                                        <input type="hidden" id="total_tax" name="total_tax" value="0.00">
                                        <input type="hidden" id="is_direct_sale" name="is_direct_sale" value="1">
                                        <input type="hidden" name="discount_amount" id="discount_amount">
                                        <input type="hidden" id="store_pos_id" name="store_pos_id"
                                            value="{{$store_pos->id}}" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card mt-1 mb-0">
                    <div class="card-body py-2 px-4">
                        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">


                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label
                                                class="@if (app()->isLocale('ar')) mb-1 label-ar @else mb-1 label-en @endif"
                                                for="tax_id">@lang('lang.tax')</label>
                                            <select class="form-control" name="tax_id" id="tax_id">
                                                <option value="" selected>No Tax</option>
                                                @foreach ($taxes as $tax)
                                                <option data-rate="{{$tax->rate}}" value="{{$tax->id}}">
                                                    {{$tax->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('discount_type', __( 'lang.discount_type' ) . '*',[
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
                                            {!! Form::text('discount_value', null, ['class' => 'form-control',
                                            'placeholder'
                                            =>
                                            __(
                                            'lang.discount_value' ),
                                            'required' ])
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('status', __( 'lang.status' ) . '*',[
                                            'class' => app()->isLocale('ar') ? 'mb-1 label-ar' : 'mb-1 label-en'
                                            ]) !!}
                                            {!! Form::select('status', ['final' => 'Completed', 'pending' =>
                                            'Pending'],
                                            'final', ['class' =>
                                            'form-control', 'data-live-search' => 'true']) !!}
                                        </div>
                                    </div>
                                </div>
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
                                ])
                                !!}
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

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">@lang('lang.import')</button>
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
<script>

</script>
@endsection
