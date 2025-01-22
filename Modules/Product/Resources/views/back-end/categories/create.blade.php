<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex justify-content-between py-2 flex-row ">
            <h5 class="modal-title" id="edit">@lang('lang.add_category')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open([
            'url' => route('admin.categories.store'),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_category_form' : 'category_add_form',
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
                        <button class="select-button btn-flat  translation_btn" type="button" data-type="category"><i
                                class="dripicons-web"></i></button>
                    </span>
                </div>
            </div>
            @include('back-end.layouts.partials.translation_inputs', [
                'attribute' => 'name',
                'translations' => [],
                'type' => 'category',
            ])

            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            {{--            @include('back-end.layouts.partials.image_crop') --}}
            <div class="col-md-6 d-flex flex-column mb-2">

                <label
                    class="form-label d-block mb-1  @if (app()->isLocale('ar')) text-end @else text-start @endif"
                    for="file-input-category"> {{ __('lang.image') }}</label>
                <div
                    class="d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <div class="variants col-md-6">
                        <div class='file file--upload w-100'>
                            <label for='file-input-category' class="w-100 modal-input m-0">
                                <i class="fas fa-cloud-upload-alt"></i>

                            </label>
                            <!-- <input  id="file-input" multiple type='file' /> -->
                            <input type="file" id="file-input-category">
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="preview-category-container"></div>
                    </div>
                </div>



            </div>

        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button id="add-category-btn" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>
        <div id="cropped_add_category_images"></div>
        {!! Form::close() !!}
        <!-- Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <div id="croppie-category-modal" style="display:none">
                            <div id="croppie-category-container"></div>
                            <button data-dismiss="modal" id="croppie-category-cancel-btn" type="button"
                                class="btn btn-secondary"><i class="fas fa-times"></i></button>
                            <button id="croppie-category-submit-btn" type="button" class="btn btn-primary"><i
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
    $("#add-category-btn").on("click", function(e) {
        e.preventDefault();
        setTimeout(() => {
            getAddCategoryImages();
            $("#category_add_form").submit();
            $("#quick_add_category_form").submit();
        }, 500)
    });


    // crop image
    var fileAddCategoryInput = document.querySelector('#file-input-category');
    var previewAddCategoryContainer = document.querySelector('.preview-category-container');
    var croppieAddCategoryModal = document.querySelector('#croppie-category-modal');
    var croppieAddCategoryContainer = document.querySelector('#croppie-category-container');
    var croppieAddCategoryCancelBtn = document.querySelector('#croppie-category-cancel-btn');
    var croppieAddCategorySubmitBtn = document.querySelector('#croppie-category-submit-btn');
    // let currentFiles = [];
    fileAddCategoryInput.addEventListener('change', () => {
        previewAddCategoryContainer.innerHTML = '';
        let files = Array.from(fileAddCategoryInput.files)
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
                            title: '{{ translate('site.Are you sure?') }}',
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
                                getAddCategoryImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#addCategoryModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchAddCategoryCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewAddCategoryContainer.appendChild(preview);
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

        getAddCategoryImages()
    });

    function launchAddCategoryCropTool(img) {
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
        const croppie = new Croppie(croppieAddCategoryContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieAddCategoryModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieAddCategoryCancelBtn.addEventListener('click', () => {
            croppieAddCategoryModal.style.display = 'none';
            $('#addCategoryModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieAddCategorySubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieAddCategoryModal.style.display = 'none';
                $('#addCategoryModal').modal('hide');
                croppie.destroy();
                getAddCategoryImages()
            });
        });
    }

    function getAddCategoryImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-category-container');
            let images = [];
            $("#cropped_add_category_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                    .children[i].children[0].src);
                $("#cropped_add_category_images").append(newInput);
            }
            return images
        }, 300);
    }
</script>
