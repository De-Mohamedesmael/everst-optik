<div class="card mb-2">
    <div class="card-body p-2">
        <div class="row  @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <div class="col-md-2 mb-2">
                {!! Form::label('customer_type_id', __('lang.customer_type') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select('customer_type_id', $customer_types, false, [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'id' => 'customer_type_id',
                'required',
                'placeholder' => __('lang.please_select'),
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('name', __('lang.name'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('name', null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.name'),
                ]) !!}
            </div>
            <div class="col-md-2 mb-2">
                {!! Form::label('id_type',translate('id_type') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select('id_type', [
                "TC_KIMLIK_NUMARASI_VAR" => translate('TC_KIMLIK_NUMARASI_VAR'),
                "PASAPORT_NUMARASI_VAR" => translate('PASAPORT_NUMARASI_VAR'),
                "YUPASS_NUMARASI_VAR" => translate('YUPASS_NUMARASI_VAR'),
                "KIMLIGI_BELIRSIZ" => translate('KIMLIGI_BELIRSIZ'),
                "DIGER" => translate('DIGER'),
]               , false, [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'id' => 'id_type',
                'required',
                'placeholder' => translate('please_select'),
                ]) !!}
            </div>
            <div class="col-md-4 mb-2">
                {!! Form::label('id_number', __('lang.id_number') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('id_number', null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.id_number'),
                ]) !!}
            </div>
            <div class="col-md-4 mb-2">
                {!! Form::label('BNO',translate('BNO') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('BNO', null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' =>translate('BNO'),
                ]) !!}
            </div>
            <div class="col-md-2 mb-2 div-company d-none">
                {!! Form::label('id_number', __('lang.tax_number') , [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('tax_number', null, [
                'style'=> "border:1px solid #e6e6e6 !important",
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.tax_number'),
                ]) !!}
            </div>
            <div class="col-md-2 mb-2 div-company d-none">
                {!! Form::label('tax_location_id', translate('tax_locations') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select('tax_location_id', $tax_locations, null , [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'placeholder' => __('lang.tax_locations'),
                ]) !!}
            </div>


            <div class="col-md-3 mb-2">
                {!! Form::label('mobile_number', __('lang.mobile_number') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('mobile_number', null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.mobile_number'),
                'required',
                ]) !!}
            </div>




            <div class="col-md-2 mb-2">
                {!! Form::label('gender', translate('gender') . '*', [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::select('gender', $genders, 1 , [
                'class' => 'selectpicker form-control',
                'data-live-search' => 'true',
                'required',
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('email', __('lang.email'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::email('email', null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'placeholder' => __('lang.email'),
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('address', __('lang.address'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::textarea('address', null, [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'rows' => 3,
                'style' => 'height:30px',
                'placeholder' => __('lang.address'),
                ]) !!}
            </div>
            <div class="col-md-3 mb-2">
                {!! Form::label('photo', __('lang.photo'), [
                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::file('image', [
                'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                'style' => 'height:30px',
                ]) !!}
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="quick_add" value="{{ $quick_add }}">
