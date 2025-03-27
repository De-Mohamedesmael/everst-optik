<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => route('admin.tax.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_tax_form' : 'tax_add_form',
        ]) !!}

        <div
            class="modal-header  position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">


            <h4 class="modal-title px-2 position-relative">@lang('lang.add_tax')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif align-items-center">
            <div class="form-group col-md-6 px-5">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('name', null, [
                    'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.name'),
                    'required',
                ]) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="form-group col-md-6 px-5">
                {!! Form::label('rate', __('lang.rate_percentage') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('rate', null, [
                    'class' => 'form-control  modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.rate'),
                    'required',
                ]) !!}
            </div>
            @if ($type == 'general_tax')
                <div class="form-group col-md-6 px-5">
                    {!! Form::label('tax_method', __('lang.tax_method') . '*', [
                        'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                    ]) !!}
                    {!! Form::select(
                        'tax_method',
                        ['inclusive' => __('lang.inclusive'), 'exclusive' => __('lang.exclusive')],
                        false,
                        ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select')],
                    ) !!}
                </div>

                <div class="form-group col-md-6 px-5">
                    <div class="d-flex justify-content-end align-items-center" style="gap: 5px">
                        {!! Form::label('store_ids', __('lang.stores'), [
                            'class' => 'form-label d-block mb-0 app()->isLocale("ar") ? text-end : text-start',
                        ]) !!} <i class="dripicons-question" data-toggle="tooltip"
                            title="@lang('lang.tax_status_info')"></i>
                    </div>
                    {!! Form::select(
                        'store_ids[]',
                        $stores,
                        [],
                        ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple'],
                    ) !!}
                </div>
                <div class="col-md-4">
                    <div class="i-checks toggle-pill-color flex-col-centered">
                        <input id="status" name="status" type="checkbox" checked value="1"
                            class="form-control-custom">
                        <label for="status">
                        </label>
                        <span>

                            <strong>
                                @lang('lang.active')
                            </strong>
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">

            <button type="submit" class="btn btn-main col-md-3 py-1">@lang('lang.save')</button>
            <button type="button" class="btn btn-danger  col-md-3 py-1" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('.selectpicker').selectpicker('refresh');
</script>
