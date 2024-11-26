var key  = 0;
var fileImageInput = '';
var previewImageContainer = '';
var croppieImageModal = '';
var croppieImageContainer = '';
var croppieImageCancelBtn = '';
var croppieImageSubmitBtn = ''
$(".file-input__label").on("click", function() {
    key = $(this).data('key');
    console.log(key);
    fileImageInput = document.querySelector("#file-input-image" + key);
    previewImageContainer = document.querySelector(".preview-image-container"+ key);
    croppieImageModal = document.querySelector("#croppie-image-modal"+ key);
    croppieImageContainer = document.querySelector("#croppie-image-container"+ key);
    croppieImageCancelBtn = document.querySelector("#croppie-image-cancel-btn"+ key);
    croppieImageSubmitBtn = document.querySelector("#croppie-image-submit-btn"+ key);
    console.log(fileImageInput);

    fileImageInput.addEventListener("change", () => {
        console.log('test');
        previewImageContainer.innerHTML = "";
        let files = Array.from(fileImageInput.files);
        for (let i = 0; i < files.length; i++) {
            var file = files[i];
            let fileType = file.type.slice(file.type.indexOf("/") + 1);
            let FileAccept = [
                "jpg",
                "JPG",
                "jpeg",
                "JPEG",
                "png",
                "PNG",
                "BMP",
                "bmp",
            ];
            // if (file.type.match('image.*')) {
            if (FileAccept.includes(fileType)) {
                var reader = new FileReader();
                reader.addEventListener("load", () => {
                    var preview = document.createElement("div");
                    preview.classList.add("preview");
                    var img = document.createElement("img");
                    var actions = document.createElement("div");
                    actions.classList.add("action_div");
                    img.src = reader.result;
                    preview.appendChild(img);
                    preview.appendChild(actions);
                    var container = document.createElement("div");
                    var deleteBtn = document.createElement("span");
                    deleteBtn.classList.add("delete-btn");
                    deleteBtn.innerHTML =
                        '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                    deleteBtn.addEventListener("click", () => {
                        Swal.fire({
                            title: LANG.are_you_sure,
                            text: LANG.you_wont_be_able_to_delete,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!",
                        }).then((result) => {
                            if (result) {
                                Swal.fire(
                                    "Deleted!",
                                    LANG.your_image_has_been_deleted,
                                    "success"
                                );
                                files.splice(file, 1);
                                preview.remove();
                                getImages();
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    var cropBtn = document.createElement("span");
                    cropBtn.setAttribute("data-toggle", "modal");
                    cropBtn.setAttribute("data-target", "#imageModal" + key);
                    cropBtn.classList.add("crop-btn");
                    cropBtn.innerHTML =
                        '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener("click", () => {
                        setTimeout(() => {
                            launchImageCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewImageContainer.appendChild(preview);
                });
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    icon: "error",
                    title: '{{ __("Oops...") }}',
                    text: '{{ __("Sorry , You Should Upload Valid Image") }}',
                });
            }
        }

        getImages();
    });
    function launchImageCropTool(img) {
        // Set up Croppie options
        var croppieOptions = {
            viewport: {
                width: 200,
                height: 200,
                type: "square", // or 'square'
            },
            boundary: {
                width: 300,
                height: 300,
            },
            enableOrientation: true,
        };
        // Destroy existing Croppie instance
        if (croppie) {
            croppie.destroy();
        }
        // Create a new Croppie instance with the selected image and options
        var croppie = new Croppie(croppieImageContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieImageModal.style.display = "block";

        // When the user clicks the "Cancel" button, hide the modal
        croppieImageCancelBtn.addEventListener("click", () => {
            croppieImageModal.style.display = "none";
            $("#imageModal" + key).modal("hide");
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieImageSubmitBtn.addEventListener("click", () => {
            croppie
                .result({
                    type: "canvas",
                    size: {
                        width: 800,
                        height: 600,
                    },
                    quality: 1, // Set quality to 1 for maximum quality
                })
                .then((croppedImg) => {
                    img.src = croppedImg;
                    croppieImageModal.style.display = "none";
                    $("#imageModal" + key).modal("hide");
                    croppie.destroy();
                    getImages();
                });
        });
    }

    function getImages() {
        setTimeout(() => {
            var container = document.querySelectorAll(".preview-image-container" + key);
            let images = [];
            $("#cropped_images" + key).empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src);
                var newInput = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "products_[" + key + "][image]")
                    .val(container[0].children[i].children[0].src);
                $("#cropped_images" + key).append(newInput);
            }
            return images;
        }, 300);
    }

// crop logo
});




