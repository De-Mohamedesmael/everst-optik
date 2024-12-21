<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row ">
            <h5 class="modal-title" id="edit">@lang('lang.add_brand_lens')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open([
            'url' => route('admin.brand_lenses.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_brand_lens_form' : 'brand_lens_add_form',
            'files' => true,
        ]) !!}


        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div class="col-sm-6 mb-2">
                {!! Form::label('name', __('lang.name') . '*', [
                    'class' => 'form-label d-block mb-1 app()->isLocale("ar") ? text-end : text-start',
                ]) !!}
                <div class="input-group my-group select-button-group">
                    {!! Form::text('name', null, [
                        'class' => 'form-control modal-input app()->isLocale("ar") ? text-end : text-start',
                        'placeholder' => __('lang.name'),
                        'required',
                    ]) !!}
                    <span class="input-group-btn">
                        <button class="select-button btn-flat  translation_btn" type="button" data-type="brand_lens"><i
                                class="dripicons-web"></i></button>
                    </span>
                </div>
            </div>
            @include('back-end.layouts.partials.translation_inputs', [
                'attribute' => 'name',
                'translations' => [],
                'type' => 'brand_lens',
            ])

            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            {{--            @include('back-end.layouts.partials.image_crop') --}}
            <div class="col-md-6 d-flex flex-column mb-2">

                <label
                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                    for="file-input-brand_lens"> {{ __('lang.image') }}</label>
                <div
                    class="d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-brand_lens' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>

                            </label>
                            <!-- <input  id="file-input" multiple type='file' /> -->
                            <input type="file" id="file-input-brand_lens">
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-brand_lens-container"></div>
                    </div>
                </div>



            </div>

        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button id="add-brand_lens-btn" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>
        <div id="cropped_add_brand_lens_images"></div>
        {!! Form::close() !!}
        <!-- Modal -->
        <div class="modal fade" id="addbrand_lensModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-brand_lens-modal" style="display:none">
                            <div id="croppie-brand_lens-container"></div>
                            <button data-dismiss="modal" id="croppie-brand_lens-cancel-btn" type="button"
                                class="btn btn-secondary"><i class="fas fa-times"></i></button>
                            <button id="croppie-brand_lens-submit-btn" type="button" class="btn btn-primary"><i
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
    $("#add-brand_lens-btn").on("click", function(e) {
        e.preventDefault();
        setTimeout(() => {
            getAddbrand_lensImages();
            $("#brand_lens_add_form").submit();
            $("#quick_add_brand_lens_form").submit();
        }, 500)
    });


    // crop image
    var fileAddbrand_lensInput = document.querySelector('#file-input-brand_lens');
    var previewAddbrand_lensContainer = document.querySelector('.preview-brand_lens-container');
    var croppieAddbrand_lensModal = document.querySelector('#croppie-brand_lens-modal');
    var croppieAddbrand_lensContainer = document.querySelector('#croppie-brand_lens-container');
    var croppieAddbrand_lensCancelBtn = document.querySelector('#croppie-brand_lens-cancel-btn');
    var croppieAddbrand_lensSubmitBtn = document.querySelector('#croppie-brand_lens-submit-btn');
    // let currentFiles = [];
    fileAddbrand_lensInput.addEventListener('change', () => {
        previewAddbrand_lensContainer.innerHTML = '';
        let files = Array.from(fileAddbrand_lensInput.files)
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
                    const actions = document.createElement('div');
                    actions.classList.add('action_div');
                    img.src = reader.result;
                    preview.appendChild(img);
                    preview.appendChild(actions);
                    const container = document.createElement('div');
                    const deleteBtn = document.createElement('span');
                    deleteBtn.classList.add('delete-btn');
                    deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                    deleteBtn.addEventListener('click', () => {
                        Swal.fire({
                            title: '{{ __('site.Are you sure?') }}',
                            text: "{{ __("site.You won't be able to delete!") }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire(
                                    'Deleted!',
                                    '{{ __('site.Your Image has been deleted.') }}',
                                    'success'
                                )
                                files.splice(file, 1)
                                preview.remove();
                                getAddbrand_lensImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#addbrand_lensModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchAddbrand_lensCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewAddbrand_lensContainer.appendChild(preview);
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

        getAddbrand_lensImages()
    });

    function launchAddbrand_lensCropTool(img) {
        // Set up Croppie options
        const croppieOptions = {
            viewport: {
                width: 200,
                height: 200,
                type: 'square' // or 'square'
            },
            boundary: {
                width: 300,
                height: 300,
            },
            enableOrientation: true
        };

        // Create a new Croppie instance with the selected image and options
        const croppie = new Croppie(croppieAddbrand_lensContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieAddbrand_lensModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieAddbrand_lensCancelBtn.addEventListener('click', () => {
            croppieAddbrand_lensModal.style.display = 'none';
            $('#addbrand_lensModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieAddbrand_lensSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieAddbrand_lensModal.style.display = 'none';
                $('#addbrand_lensModal').modal('hide');
                croppie.destroy();
                getAddbrand_lensImages()
            });
        });
    }

    function getAddbrand_lensImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-brand_lens-container');
            let images = [];
            $("#cropped_add_brand_lens_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                    .children[i].children[0].src);
                $("#cropped_add_brand_lens_images").append(newInput);
            }
            return images
        }, 300);
    }
</script>
