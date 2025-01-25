@extends('back-end.layouts.app')
@section('title', __('lang.brands'))

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active"><a
            style="text-decoration: none;color: #476762" href="{{ route('admin.products.index') }}">/
            @lang('lang.products')</a>
    </li>
    <li class="breadcrumb-item  @if (app()->isLocale('ar')) mr-2 @else ml-2 @endif active" aria-current="page">
        @lang('lang.brands')</li>
@endsection

@section('button')

    @can('product_module.brand.create_and_edit')
        <div class="widgetbar d-flex @if (app()->isLocale('ar')) justify-content-start @else justify-content-end @endif">
            <a style="color: white"
               data-href="{{ route('admin.brands.create') }}"
               data-container=".view_modal" class="btn btn-modal btn-main"><i
                    class="dripicons-plus"></i>
                {{translate('add_brand')}}
            </a>
        </div>
    @endcan
@endsection
@section('content')
    <section class="forms py-0">

        <div class="container-fluid">

            <div class="col-md-12 px-1 no-print">
                <div class="card mb-2 mt-2">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table id="store_table" class="table dataTable">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.image')</th>
                                        <th>@lang('lang.name')</th>
                                        <th class="notexport">@lang('lang.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $brand)
                                        <tr>
                                            <td><img src="@if (!empty($brand->getFirstMediaUrl('brand'))) {{ $brand->getFirstMediaUrl('brand') }}@else{{ asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) }} @endif"
                                                    alt="photo" width="50" height="50">
                                            </td>
                                            <td>{{ $brand->name }}</td>

                                            <td>
                                                @can('product_module.brand.create_and_edit')

                                                    <a data-href="{{ route('admin.brands.edit', $brand->id) }}"
                                                       data-container=".view_modal"
                                                       class="btn btn-primary btn-modal text-white edit_job">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                @endcan
                                                @can('product_module.brand.delete')
                                                    <a
                                                        data-href="{{ route('admin.brands.destroy', $brand->id) }}"
                                                        class="btn btn-danger text-white delete_item">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $('#brand_category_id').selectpicker('render');

        $('.view_modal').on('shown.bs.modal', function() {
            let brand_category_id = $('#sub_category_id').val();
            if (brand_category_id) {
                $("#brand_category_id").selectpicker("val", brand_category_id);
            } else {
                let brand_category_id = $('#category_id').val();
                $("#brand_category_id").selectpicker("val", brand_category_id);
            }
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        $("#submit-create-brand-btn").on("click", function(e) {
            e.preventDefault();
            setTimeout(() => {
                getBrandImages();
                $("#brand_add_form").submit();
                $("#quick_add_brand_form").submit();
            }, 500)
        });

        var fileBrandInput = document.querySelector('#file-input-brand');
        var previewBrandContainer = document.querySelector('.preview-brand-container');
        var croppieBrandModal = document.querySelector('#croppie-brand-modal');
        var croppieBrandContainer = document.querySelector('#croppie-brand-container');
        var croppieBrandCancelBtn = document.querySelector('#croppie-brand-cancel-btn');
        var croppieBrandSubmitBtn = document.querySelector('#croppie-brand-submit-btn');
        // let currentFiles = [];
        fileBrandInput.addEventListener('change', () => {
            previewBrandContainer.innerHTML = '';
            let files = Array.from(fileBrandInput.files)
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
                                    getBrandImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#brandModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchBrandCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewBrandContainer.appendChild(preview);
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

            getBrandImages()
        });

        function launchBrandCropTool(img) {
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
            const croppie = new Croppie(croppieBrandContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieBrandModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieBrandCancelBtn.addEventListener('click', () => {
                croppieBrandModal.style.display = 'none';
                $('#brandModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieBrandSubmitBtn.addEventListener('click', () => {
                croppie.result({
                    type: 'canvas',
                    size: {
                        width: 800,
                        height: 600
                    },
                    quality: 1 // Set quality to 1 for maximum quality
                }).then((croppedImg) => {
                    img.src = croppedImg;
                    croppieBrandModal.style.display = 'none';
                    $('#brandModal').modal('hide');
                    croppie.destroy();
                    getBrandImages()
                });
            });
        }

        function getBrandImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-brand-container');
                let images = [];
                $("#cropped_brand_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0]
                        .children[i].children[0].src);
                    $("#cropped_brand_images").append(newInput);
                }
                return images
            }, 300);
        }
    </script>
@endsection
