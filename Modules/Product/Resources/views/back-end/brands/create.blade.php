<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row ">
            <h5 class="modal-title" id="edit">@lang('lang.add_brand')</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {!! Form::open([
            'url' => route('admin.brands.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_brand_form' : 'brand_add_form',
            'files' => true,
        ]) !!}

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif align-items-center">
            <div class="form-group col-md-12 px-5">
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
            <div class="form-group px-5 col-md-12 d-flex flex-column mb-2">
                <label
                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                    for="projectinput2"> {{ __('lang.image') }}</label>

                <div
                    class="d-flex justify-content-center align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-brand' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                            <input type="file" id="file-input-brand">
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-brand-container"></div>
                    </div>
                </div>

            </div>
        </div>
        <div id="cropped_brand_images"></div>


        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button id=""class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}
        <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="brandModalLabel">Modal title</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-brand-modal" style="display:none">
                            <div id="croppie-brand-container"></div>
                            <button data-dismiss="modal" id="croppie-brand-cancel-btn" type="button"
                                class="btn btn-secondary"><i class="fas fa-times"></i></button>
                            <button id="croppie-brand-submit-btn" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

