@extends('back-end.layouts.app')
@section('title', __('lang.factory'))
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ url('front/css/stock.css') }}">
@endsection
@section('content')
<section class="forms px-3 py-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 px-1">
                <div
                    class="d-flex align-items-center my-2 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                    <h5 class="mb-0 position-relative" style="margin-right: 30px">
                        @lang('lang.edit_customer')
                        <span class="header-pill"></span>
                    </h5>
                </div>
                <div class="card mb-2 d-flex flex-row justify-content-center align-items-center">
                    <p class="italic mb-0 py-1">
                        <small>@lang('lang.required_fields_info')</small>
                    <div style="width: 30px;height: 30px;">
                        <img class="w-100 h-100" src="{{ asset('front/images/icons/warning.png') }}" alt="warning!">
                    </div>
                    </p>
                </div>
                {!! Form::open([
                'url' => route('admin.factories.update', $factory->id),
                'id' => 'customer-form',
                'method' => 'PUT',
                'class' => '',
                'enctype' => 'multipart/form-data',
                ]) !!}

                @include('factory::back-end.factories.partial.create_factory_form', ['factory' => $factory])

                
                <div class="row my-2 justify-content-center align-items-center">
                    <div class="col-md-4">
                        <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn" class="btn py-1">
                    </div>
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</section>
@endsection

@section('javascript')
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

<script type="text/javascript">

</script>
@endsection
