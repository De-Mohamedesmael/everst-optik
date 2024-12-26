<div class="card my-3">
    <div class="card-body p-2">
        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('store_id', __('lang.store'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select('store_id', $stores, request()->store_id, [
                    'class' => 'form-control filter_lens',
                    'placeholder' => __('lang.all'),
                    'data-live-search' => 'true',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('created_by', __('lang.created_by'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select('created_by', $admins, request()->created_by, [
                    'class' => 'form-control filter_lens
                    selectpicker',
                    'data-live-search' => 'true',
                    'placeholder' => __('lang.all'),
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('active', __('lang.active'), [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select('active', [0 => __('lang.no'), 1 => __('lang.yes')], request()->active, [
                    'class' => 'form-control filter_lens
                    selectpicker',
                    'data-live-search' => 'true',
                    'placeholder' => __('lang.all'),
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <div
                class="d-flex my-2  col-md-3 @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif">
                <button class="text-decoration-none toggle-button mb-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#lensesFilterCollapse" aria-expanded="false"
                        aria-controls="lensesFilterCollapse">
                    <i class="fas fa-arrow-down"></i>
                    {{translate('lenses_filter')}}
                    <span class="section-header-pill"></span>
                </button>
            </div>
            <div class="collapse col-md-9" id="lensesFilterCollapse">

                <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">




                        <div class="col-md-2 px-2">
                            <div class="form-group">
                                {!! Form::label('brand_id', __('lang.brand'), [
                                'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                                ]) !!}
                                {!! Form::select('brand_id', $brands, request()->brand_id, [
                                'class' => 'form-control filter_lens selectpicker',
                                'data-live-search' => 'true',
                                'placeholder' => __('lang.all'),
                                ]) !!}
                            </div>
                        </div>

                    <div class="col-md-2 px-2">
                        <div class="form-group">
                            {!! Form::label('color_id', __('lang.color'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                            ]) !!}
                            {!! Form::select('color_id', $colors, request()->color_id, [
                            'class' => 'form-control
                            filter_lens
                            selectpicker',
                            'data-live-search' => 'true',
                            'placeholder' => __('lang.all'),
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-2 px-2">
                        <div class="form-group">
                            {!! Form::label('tax_id', __('lang.tax'), [
                            'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                            ]) !!}
                            {!! Form::select('tax_id', $taxes, request()->tax_id, [
                            'class' => 'form-control
                            filter_lens
                            selectpicker',
                            'data-live-search' => 'true',
                            'placeholder' => __('lang.all'),
                            ]) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">


            <input type="hidden" name="lens_id" id="lens_id" value="">
            <div class="col-md-3 px-2 d-flex justify-content-around align-items-center">
                <button class="btn py-1 btn-outline-danger clear_filters">@lang('lang.clear_filters')</button>

                <a data-href="{{  route('admin.lenses.multiDeleteRow') }}" id="delete_all"
                   data-check_password="{{ route('admin.check-password', Auth::user()->id) }}"
                   class="btn btn-danger text-white delete_all"><i class="fa fa-trash"></i>
                    @lang('lang.delete_all')</a>
            </div>


            <div class="col-md-3 px-2 d-flex justify-content-center align-items-center">
                <div class="form-group d-flex justify-content-center align-items-center mb-0">
                    {{-- <label>
                        Don't show zero stocks
                    </label> --}}
                    {!! Form::label('show_zero_stocks', "Don't show zero stocks", [
                    'class' => 'form-label d-block mb-0 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::checkbox(
                    'show_zero_stocks',
                    1,
                    false,
                    ['class' => ' form-control show_zero_stocks mx-2',
                    'style' => 'width:fit-content',
                    'data-live-search' => 'true'],
                    request()->show_zero_stocks ? true : false,
                    ) !!}
                </div>
            </div>
        </div>
    </div>
</div>
