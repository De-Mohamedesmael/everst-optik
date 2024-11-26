@extends('back-end.layouts.app')
@section('title', __('lang.customer'))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.customers.index') }}">/
            @lang('lang.customers')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        {{translate('add_customer')}}</li>
@endsection
@section('content')
    <section class="forms py-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    <div class="card mb-2 d-flex flex-row justify-content-center align-items-center">
                        <p class="italic mb-0 py-1">
                            <small>@lang('lang.required_fields_info')</small>
                        <div style="width: 30px;height: 30px;">
                            <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
                        </div>
                        </p>
                    </div>
                    {!! Form::open([
                        'url' => route('admin.customers.store'),
                        'id' => 'customer-form',
                        'method' => 'POST',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    @include('customer::back-end.customers.partial.create_customer_form')
                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-4">
                            <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn" class="btn py-1">
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal gift_card_modal no-print" role="dialog" aria-hidden="true"></div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>


    <script src="{{ asset('js/dashboard/customers/_pst.js') }}"></script>
    <script type="text/javascript">
        $('#customer-type-form').submit(function() {
            $(this).validate();
            if ($(this).valid()) {
                $(this).submit();
            }
        });


        $('.datepicker').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });
    </script>

@endsection
