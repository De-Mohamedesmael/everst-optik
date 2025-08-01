@extends('back-end.layouts.app')
@section('title', __('lang.lenses'))
@section('styles')
    <style>
        .text-start {
            text-align: left !important;
        }
    </style>
@endsection
@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
                style="text-decoration: none;color: #476762" href="{{ route('admin.lenses.index') }}">
            @lang('lang.lenses')</a>
    </li>
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.add_new_lens')</li>
@endsection

@section('content')
    <section class="forms px-3 py-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 px-1">
                    {!! Form::open([
                        'url' => route('admin.lenses.store'),
                        'id' => 'products-form',
                        'method' => 'POST',
                        'class' => '',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    @include('lens::back-end.lenses.partial.create_lens_form')
                    <div class="row my-2 justify-content-center align-items-center">
                        <div class="col-md-4">
                            <input type="button" value="{{ trans('lang.save') }}" id="submit-btn"
                                   class="btn btn-primary py-1">
                        </div>
                    </div>
                    <div id="cropped_images"></div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="lens_cropper_modal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('lang.crop_image_before_upload')</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img src="" id="lens_sample_image"/>
                            </div>
                            <div class="col-md-4">
                                <div class="lens_preview_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="lens_crop" class="btn btn-primary">@lang('lang.crop')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-modal" style="display:none">
                        <div id="croppie-container"></div>
                        <button data-dismiss="modal" id="croppie-cancel-btn" type="button" class="btn btn-secondary"><i
                                    class="fas fa-times"></i></button>
                        <button id="croppie-submit-btn" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{asset('js/product.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        const fileInput = document.querySelector('#file-input');
        const previewContainer = document.querySelector('.preview-container');
        const croppieModal = document.querySelector('#croppie-modal');
        const croppieContainer = document.querySelector('#croppie-container');
        const croppieCancelBtn = document.querySelector('#croppie-cancel-btn');
        const croppieSubmitBtn = document.querySelector('#croppie-submit-btn');

        // let currentFiles = [];
        fileInput.addEventListener('change', () => {
            // let files = fileInput.files;
            previewContainer.innerHTML = '';
            let files = Array.from(fileInput.files)
            // files.concat(currentFiles)
            // currentFiles.push(...files)
            // currentFiles && (files = currentFiles)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.addEventListener('load', (e) => {
                        e.preventDefault();
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        const actions = document.createElement('div');
                        actions.classList.add('action_div');
                        img.src = reader.result;
                        preview.appendChild(img);
                        preview.appendChild(actions);

                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('button');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            if (window.confirm('Are you sure you want to delete this image?')) {
                                files.splice(file, 1)
                                preview.remove();
                                getImages()
                            }
                        });

                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('button');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#exampleModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            setTimeout(() => {
                                launchCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewContainer.appendChild(preview);
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

        function launchCropTool(img) {
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
            const croppie = new Croppie(croppieContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieCancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                croppieModal.style.display = 'none';
                $('#exampleModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieSubmitBtn.addEventListener('click', (e) => {
                e.preventDefault();
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
                    $('#exampleModal').modal('hide');
                    croppie.destroy();
                    getImages()

                    // blob = new Blob(croppedImg, { type: 'image/*' });
                    let blob = new Blob([croppedImg], {
                        type: "image/png"
                    });
                    // console.log(blob);
                    // console.log(fileInput.files);
                    // let files = Array.from(fileInput.files)
                    // files.concat(currentFiles)
                    // currentFiles.push(...files)
                    // currentFiles && (files = currentFiles)
                    // currentFiles = files
                });
            });
        }

        function getImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-container');
                let images = [];
                $("#cropped_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                        .children[i].children[0].src);
                    $("#cropped_images").append(newInput);
                }
                console.log(images);
                return images
            }, 500);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#discount_customer_types').selectpicker('selectAll');
            $('#store_ids').selectpicker('selectAll');
        });
        $(document).on("click", ".remove_row", function () {
            row_id = $(this).closest("tr").data("row_id");
            $(this).closest("tr").remove();
        });
    </script>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>


    <script>


        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#discountInfoCollapse').on('show.bs.collapse', function () {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#discountInfoCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#discountInfoCollapse').on('hide.bs.collapse', function () {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#discountInfoCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });

        // Add an event listener for the 'show.bs.collapse' and 'hide.bs.collapse' events
        $('#moreInfoCollapse').on('show.bs.collapse', function () {
            // Change the arrow icon to 'chevron-up' when the content is expanded
            $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-down').addClass(
                'fa-arrow-up');
        });

        $('#moreInfoCollapse').on('hide.bs.collapse', function () {
            // Change the arrow icon to 'chevron-down' when the content is collapsed
            $('button[data-bs-target="#moreInfoCollapse"] i').removeClass('fa-arrow-up').addClass(
                'fa-arrow-down');
        });


    </script>

@endsection
