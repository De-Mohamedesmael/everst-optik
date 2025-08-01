<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => route('admin.colors.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_color_form' : 'color_add_form',
        ]) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">@lang('lang.add_color')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div class="row">
                <div class="col-md-2 px-0 d-flex justify-content-center">
                    <div class="i-checks toggle-pill-color flex-col-centered">
                        <input id="is_lens" name="is_lens" type="checkbox" value="1" class="form-control-custom">
                        <label for="is_lens">
                        </label>
                        <span>
                    <strong>@lang('lang.is_lens')</strong>
                </span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-2">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('name', null, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',

                    'placeholder' => __('lang.name'),
                    'required',
                ]) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            <div class="col-sm-6 mb-2">
                {!! Form::label('color_hex', __('lang.color_hex') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                {!! Form::text('color_hex', null, [
                    'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                    'placeholder' => __('lang.color_hex'),
                ]) !!}
            </div>
        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
