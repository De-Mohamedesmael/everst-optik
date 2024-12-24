<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row">
            <h5 class="modal-title" id="edit">@lang('lang.add_feature')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open([
            'url' => route('admin.features.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_feature_form' : 'feature_add_form',
            'files' => true,
            'enctype' => 'multipart/form-data',

        ]) !!}

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div class="col-md-3 mb-2">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::text('name', old('name'), [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.name'),
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-5 d-flex flex-column mb-2" id="feature-container">
                <label class="form-label" for="file-input-feature">{{ __('lang.image') }}</label>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-feature' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                            <input type="file" id="file-input-feature">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-feature-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column mb-2" id="icon-active-container">
                <label class="form-label" for="file-input-icon-active">{{ __('lang.icon_active') }}</label>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-icon-active' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                            <input type="file" id="file-input-icon-active">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-icon-active-container"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('brand_lens_id', __('lang.brand_lenses'), [
                        'class' => 'form-label d-block mb-1 ',
                    ]) !!}
                    {!! Form::select('brand_lens_id[]', $brand_lenses,null, [
                        'class' => ' selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'multiple',
                       'data-actions-box' => 'true',
                        'id' => 'brand_lens_id',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-5 d-flex flex-column mb-2" id="before-effect-container">
                <label class="form-label" for="file-input-before-effect">{{ __('lang.before_effect') }}</label>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-before-effect' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                            <input type="file" id="file-input-before-effect">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-before-effect-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column mb-2" id="after-effect-container">
                <label class="form-label" for="file-input-after-effect">{{ __('lang.after_effect') }}</label>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-after-effect' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                            <input type="file" id="file-input-after-effect">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-after-effect-container"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer d-flex justify-content-center gap-3">
            <button id="add-feature-btn" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>
        <div id="cropped_add_feature_images"></div>
        {!! Form::close() !!}

    </div>
</div>


<script type="text/javascript">
    $('#brand_lens_id').selectpicker('refresh');

    $(document).ready(function() {
        const fields = ["feature", "icon-active", "before-effect", "after-effect"];

        fields.forEach(field => {
            const fileInput = document.querySelector(`#file-input-${field}`);
            const previewContainer = document.querySelector(`.preview-${field}-container`);
            const croppedImagesContainer = document.querySelector(`#cropped_add_feature_images`);

            if (fileInput) {
                fileInput.addEventListener('change', () => {
                    previewContainer.innerHTML = '';
                    const files = Array.from(fileInput.files);

                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            const img = document.createElement('img');
                            img.src = reader.result;
                            img.style.maxWidth = "100%";
                            img.style.maxHeight = "100px";
                            previewContainer.appendChild(img);

                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = `${field}_images[]`;
                            hiddenInput.value = reader.result;
                            croppedImagesContainer.appendChild(hiddenInput);
                        });
                        reader.readAsDataURL(file);
                    });
                });
            }
        });

        $("#add-feature-btn").on("click", function(e) {
            e.preventDefault();
            if ($("#feature_add_form").length) {
                $("#feature_add_form").submit();
            }
            if ($("#quick_add_feature_form").length) {
                $("#quick_add_feature_form").submit();
            }
        });
    });
</script>


