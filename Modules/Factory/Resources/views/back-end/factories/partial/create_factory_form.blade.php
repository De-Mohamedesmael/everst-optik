@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif




<div class="card mb-2">
    <div class="card-body p-2">
        <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <div class="col-md-2 mb-2">
                {!! Form::label('customer_type_id', __('lang.country') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select('country_id', $countries, isset($factory) ? $factory->country_id : false,  [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'id' => 'country_id',
                'required',
                'placeholder' => __('lang.please_select'),
                ]) !!}
            </div>


            <div class="col-md-3 mb-2">
                {!! Form::label('name', __('lang.name'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('name', isset($factory) ? $factory->name : null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.name'),
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('email', __('lang.email'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::email('email',  isset($factory) ? $factory->email : null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.email'),
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('address', __('lang.address'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::textarea('address',  isset($factory) ? $factory->address : null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'rows' => 3,
                'style' => 'height:30px',
                'placeholder' => __('lang.address'),
                ]) !!}
            </div>
            
            <div class="col-md-4 mb-2">
                {!! Form::label('code', __('lang.factory_code') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('code',  isset($factory) ? $factory->code : null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.factory_code'),
                ]) !!}
            </div>

            <div class="col-md-4 mb-2">
                {!! Form::label('postal_code', __('lang.postal_code') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('postal_code',  isset($factory) ? $factory->postal_code : null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.postal_code'),
                ]) !!}
            </div>

            

            <div class="col-md-3 mb-2">
                {!! Form::label('phone', __('lang.phone') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('phone',  isset($factory) ? $factory->phone : null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.phone'),
                'required',
                ]) !!}
            </div>

            <div class="col-md-4 mb-2">
                {!! Form::label('owner_name', __('lang.owner_name') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('owner_name',  isset($factory) ? $factory->owner_name : null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.owner_name'),
                ]) !!}
            </div>





        </div>
    </div>
</div>

{{-- <input type="hidden" name="quick_add" value="{{ $quick_add }}"> --}}
