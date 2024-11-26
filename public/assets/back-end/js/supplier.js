

Dropzone.autoDiscover = false;
myDropzone = new Dropzone("div#my-dropzone", {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: false,
    parallelUploads: 100,
    maxFilesize: 12,
    maxFiles: 1,
    paramName: "cover",
    clickable: true,
    method: "POST",
    url: $("form#products-form").attr("action"),
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    // renameFile: function (file) {
    //     var dt = new Date();
    //     var time = dt.getTime();
    //     return time + file.name;
    // },
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    init: function () {
        var myDropzone = this;
        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            if ($("#products-form").valid()) {
                // tinyMCE.triggerSave();
                if (myDropzone.getAcceptedFiles().length) {
                    myDropzone.processQueue();
                } else {
                    // document.getElementById("loader").style.display = "block";
                    // document.getElementById("content").style.display = "none";
                    $.ajax({
                        type: "POST",
                        url: $("form#products-form").attr("action"),
                        data: $("#products-form").serialize(),
                        success: function (response) {
                            // Swal.fire(response.status);
                            // Swal.fire("Success", response.status, "success");
                            Swal.fire({
                                title: "Success",
                                text: response.status,
                                icon: "success",
                                timer: 1000, // Set the timer to 1000 milliseconds (1 second)
                                showConfirmButton: false // This will hide the "OK" button
                            });
                            location.replace('/suppliers');
                            // $(".ajaxform")[0].reset();
                        },
                        error: function (response) {
                            // Swal.fire(response.status);
                            // Swal.fire("Error", response.status, "error");
                            Swal.fire({
                                title: "Error",
                                text: response.status,
                                icon: "error",
                                timer: 1000, // Set the timer to 1000 milliseconds (1 second)
                                showConfirmButton: false // This will hide the "OK" button
                            });
                        },

                    });
                }
            }

        });

        this.on("sending", function (file, xhr, formData) {
            // document.getElementById("loader").style.display = "block";
            // document.getElementById("content").style.display = "none";
            var data = $("#products-form").serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
        });
        this.on("complete", function (file) {
            this.removeAllFiles(true);
            // myFunction();
        });
    },
    error: function (file, response) {
        console.log(response);
    },
    success: function (file, response) {
        if (response.success) {
            // Swal.fire(response.status);
            // Swal.fire("Error", response.status, "error");
            Swal.fire({
                title: "Error",
                text: response.status,
                icon: "error",
                timer: 1000, // Set the timer to 1000 milliseconds (1 second)
                showConfirmButton: false // This will hide the "OK" button
            });
        }
        if (!response.success) {
            // Swal.fire(response.status);
            // Swal.fire("Success", response.status, "success");
            Swal.fire({
                title: "Success",
                text: response.status,
                icon: "success",
                timer: 1000, // Set the timer to 1000 milliseconds (1 second)
                showConfirmButton: false // This will hide the "OK" button
            });
            location.replace('/categories');
        }
    },
    completemultiple: function (file, response) { },
    reset: function () {
        this.removeAllFiles(true);
    },
});

var modalTemplate = $("#product_cropper_modal");

myDropzone.on("thumbnail", function (file) {
    if (file.cropped) return;

    var cachedFilename = file.name;
    myDropzone.removeFile(file);

    var $cropperModal = $(modalTemplate);
    var $uploadCrop = $cropperModal.find("#product_crop");

    $cropperModal.find(".product_preview_div").empty();

    var $img = document.getElementById("product_sample_image");

    var reader = new FileReader();
    var cropper;
    reader.onloadend = function () {
        $($img).attr("src", reader.result);
        $cropperModal.modal("show");
        modalTemplate.on("shown.bs.modal", function () {
            cropper = null;
            cropper = new Cropper($img, {
                initialAspectRatio: 1 / 1,
                aspectRatio: 1 / 1,
                cropBoxResizable: false,
                viewMode: 2,
                preview: ".product_preview_div",
            });
        });
    };
    reader.readAsDataURL(file);

    $uploadCrop.on("click", function () {
        var blob = cropper.getCroppedCanvas().toDataURL();
        var newFile = dataURItoBlob(blob);
        newFile.cropped = true;
        newFile.name = cachedFilename;

        myDropzone.addFile(newFile);
        $cropperModal.modal("hide");
        cropper.destroy();
        cropper = null;
    });
});
function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(",")[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: "image/jpeg" });
}

