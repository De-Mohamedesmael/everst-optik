<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row ">
            <h5 class="modal-title" id="edit">@lang('lang.edit_brand_lens')</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {!! Form::open([
            'url' => route('admin.brand_lenses.update', $brand_lens->id),
            'method' => 'put',
            'id' => 'brand_lens_add_form',
            'files' => true,
        ]) !!}
        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div class="col-sm-3 mb-2">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::text('name', $brand_lens->name, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.name'),
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="col-sm-3 mb-2">
                {!! Form::label('price', __('lang.price') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::number('price', $brand_lens->price, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.price'),
                        'required',
                    ]) !!}

                </div>
            </div>
            <div class="col-md-6 px-5">
                <div class="form-group">
                    {!! Form::label('feature_id', __('lang.features'), [
                        'class' => 'form-label d-block mb-1 ',
                    ]) !!}
                    {!! Form::select('feature_id[]', $features,$brand_lens->features()->pluck('features.id'), [
                        'class' => ' selectpicker form-control',
                        'data-live-search' => 'true',
                        'style' => 'width: 80%',
                        'multiple',
                       'data-actions-box' => 'true',
                        'id' => 'feature_id',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3 px-5">
                <div class="form-group">
                    {!! Form::label('color', __('lang.color'), [
                        'class' => 'form-label d-block mb-1 ',
                    ]) !!}
                    {!! Form::color('color',$brand_lens->color, [
                        'class' => '  form-control',
                        'id' => 'color',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column mb-2">


                <label
                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                    for="projectinput2">{{ __('lang.image') }}</label>
                <div
                    class="d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                     <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-edit' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>Upload
                            </label>
                            <!-- <input  id="file-input" multiple type='file' /> -->
                             <input type="file" id="file-input-edit">
                        </div>
                    </div>


                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-edit-container">
                            @if ($brand_lens)
                                <div id="preview{{ $brand_lens->id }}" class="preview">
                                    @if (!empty($brand_lens->getFirstMediaUrl('icon')))
                                        <img src="{{ $brand_lens->getFirstMediaUrl('icon') }}"
                                            id="img{{ $brand_lens->id }}" alt="">
                                    @else
                                        <img src="{{ $brand_lens->icon }}" alt=""
                                            id="img{{ $brand_lens->id }}">
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


         </div>

        <div id="cropped_images"></div>
        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">

            <button type="submit" class="btn btn-main col-3 py-1">@lang('lang.update')</button>
            <button type="button" class="btn btn-danger col-3 py-1" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

        <div class="modal fade" id="imagesModal" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imagesModalLabel">Modal title</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-modal-edit" style="display:none">
                            <div id="croppie-container-edit"></div>
                            <button data-dismiss="modal" id="croppie-cancel-btn-edit" type="button"
                                class="btn btn-secondary"><i class="fas fa-times"></i></button>
                            <button id="croppie-submit-btn-edit" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
         </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    $('#feature_id').selectpicker('refresh');

    $("#sub-button-form").click(function(e) {
        e.preventDefault();
        getImages()
        setTimeout(() => {
            $("#brand_lens_add_form").submit();
        }, 500)
    });
    const fileInputImages = document.querySelector('#file-input-edit');
    const previewImagesContainer = document.querySelector('.preview-edit-container');
    const croppieModal = document.querySelector('#croppie-modal-edit');
    const croppieContainer = document.querySelector('#croppie-container-edit');
    const croppieCancelBtn = document.querySelector('#croppie-cancel-btn-edit');
    const croppieSubmitBtn = document.querySelector('#croppie-submit-btn-edit');

    fileInputImages.addEventListener('change', () => {
        previewImagesContainer.innerHTML = '';
        let files = Array.from(fileInputImages.files)
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            let fileType = file.type.slice(file.type.indexOf('/') + 1);
            let FileAccept = ["jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "BMP", "bmp"];
            // if (file.type.match('image.*')) {
            if (FileAccept.includes(fileType)) {
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const preview = document.createElement('div');
                    preview.classList.add('preview');
                    const img = document.createElement('img');
                    img.src = reader.result;
                    preview.appendChild(img);
                    const container = document.createElement('div');
                    const deleteBtn = document.createElement('span');
                    deleteBtn.classList.add('delete-btn');
                    deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                    deleteBtn.addEventListener('click', () => {
                        Swal.fire({
                            title: '{{translate('Are you sure?') }}',
                            text: "{{translate("You won't be able to delete!") }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '{{translate('Yes, delete it!') }}'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire(
                                    'Deleted!',
                                    '{{translate("Your Image has been deleted.") }}',
                                    'success'
                                )
                                files.splice(file, 1)
                                preview.remove();
                                getImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#imagesModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchImagesCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewImagesContainer.appendChild(preview);
                });
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('site.Oops...') }}',
                    text: '{{ __('site.Sorry , You Should Upload Valid Image') }}',
                })
            }
        }
        getImages()
    });

    function launchImagesCropTool(img) {
        // Set up Croppie options
        const croppieOptions = {
            viewport: {
                width: 120,
                height: 60,
                type: 'square' // or 'square'
            },
            boundary: {
                width: 120,
                height: 60,
            },
            enableOrientation: true
        };

        // Create a new Croppie instance with the selected image and options
        const croppie = new Croppie(croppieContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieCancelBtn.addEventListener('click', () => {
            croppieModal.style.display = 'none';
            $('#imagesModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieModal.style.display = 'none';
                $('#imagesModal').modal('hide');
                croppie.destroy();
            });
        });
    }

    function getImages() {
        $("#cropped_images").empty();
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-edit-container');
            let images = [];
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                    .children[i].children[0].src);
                $("#cropped_images").append(newInput);
            }
            console.log(images)
            return images
        }, 300);
    }
</script>
