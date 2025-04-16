$(document).ready(function () {
    if (!$('#clear_all_input_form').is(':checked')) {
        $('.clear_input_form').val('');
        $('.clear_input_form').selectpicker('refresh');
    }
    //Prevent enter key function except texarea
    $("form").on("keyup keypress", function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13 && e.target.tagName != "TEXTAREA") {
            e.preventDefault();
            return false;
        }
    });
    tinymce.init({
        selector: "#product_details",
        height: 130,
        plugins: [
            "advlist autolink lists link charmap print preview anchor textcolor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime table contextmenu paste code wordcount",
        ],
        toolbar:
            "insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat",
        branding: false,
    });
});
$(".different_prices_for_stores_div").slideUp();
$("#different_prices_for_stores").change(function () {
    if ($(this).prop("checked")) {
        $(".different_prices_for_stores_div").slideDown();
    } else {
        $(".different_prices_for_stores_div").slideUp();
    }
});
$(".show_to_customer_type_div").slideUp();
$("#show_to_customer").change(function () {
    if ($(this).prop("checked")) {
        $(".show_to_customer_type_div").slideUp();
    } else {
        $(".show_to_customer_type_div").slideDown();
    }
});


$(document).on("click", "#clear_all_input_form", function () {
    var value = $('#clear_all_input_form').is(':checked') ? 1 : 0;
    $.ajax({
        method: "get",
        url: "/create-or-update-system-property/clear_all_input_form/" + value,
        contentType: "html",
        success: function (result) {
            if (result.success) {
                swal("Success", "all inputs cleared", "success");
            }
        },
    });
});



$("#submit-btn").on("click", function (e) {
    e.preventDefault();
    let sku = $('#sku').val();

    if (sku.trim() !== "" && sku) {
        $.ajax({
            method: "get",
            url: "/dashboard/products/check-sku/" + sku,
            data: {},
            success: function (result) {
                console.log(result.success);
                if (!result.success) {
                    Swal.fire({
                        title: 'Error',
                        text: result.msg,
                        icon: 'error',
                    })
                } else {
                    submitForm();
                }
            },
        });
    } else {
        submitForm();
    }
});

function submitForm() {
    if ($("#products-form").valid()) {
        tinyMCE.triggerSave();
        document.getElementById("loader").style.display = "block";
        document.getElementById("content").style.display = "none";
        $.ajax({
            type: "POST",
            url: $("form#products-form").attr("action"),
            data: $("#products-form").serialize(),
            success: function (response) {

                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: response.msg,
                        icon: 'success',
                    })
                    $("#sku").val("").change();
                    $("#show_at_the_main_pos_page").prop('checked', false);
                    $("#name").val("").change();
                    $(".translations").val("").change();

                    if (!$('#clear_all_input_form').is(':checked')) {
                        $('.clear_input_form').val('');
                        $('.clear_input_form').selectpicker('refresh');
                    }
                    const previewContainer = document.querySelector('.preview-container');
                    previewContainer.innerHTML = '';
                    document.getElementById("content").style.display = "block";
                    document.getElementById("loader").style.display = "none";

                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.msg,
                        icon: 'error',
                    })

                }
            },
            error: function (response) {

                if (!response.success) {
                    Swal.fire({
                        title: 'Error',
                        text: response.msg,
                        icon: 'error',
                    });
                }
                document.getElementById("content").style.display = "block";
                document.getElementById("loader").style.display = "none";

            },
        });

    }
}


// transform cropper dataURI output to a Blob which Dropzone accepts
function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(",")[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: "image/jpeg" });
}



$(document).on("submit", "form#quick_add_category_form", function (e) {
    e.preventDefault();
    var data = new FormData(this);

    var category_id = null;

    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        data: data,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                })
                $("#editModal").modal("hide");
                category_id = result.category_id;
                multiple_categories_array.push(category_id);

                $.ajax({
                    method: "get",
                    url: "/dashboard/categories/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        if (category_id) {
                            $("#category_id").empty().append(data_html);
                            $("#category_id").selectpicker("refresh");
                            $("#category_id").selectpicker(
                                "val",
                                multiple_categories_array
                            );
                        }
                    },
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                })
            }
        },
    });
});



var brand_id = null;
$(document).on("submit", "form#quick_add_brand_form", function (e) {
    console.log('dddd');
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                $("#editModal").modal("hide");
                Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                brand_id = result.brand_id;
                get_brand_dropdown(brand_id);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                })
            }
        },
    });
});

function get_brand_dropdown() {
    $.ajax({
        method: "get",
        url: "/dashboard/brands/get-dropdown",
        data: {},
        contactType: "html",
        success: function (data_html) {
            $("#brand_id").empty().append(data_html);
            $("#brand_id").selectpicker("refresh");
            if (brand_id) {
                $("#brand_id").selectpicker("val", brand_id);
            }
        },
    });
}
$(document).on("submit", "form#quick_add_tax_form", function (e) {
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                $("#editModal").modal("hide");
                var tax_id = result.tax_id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/taxes/get-dropdown",
                    data: { type: "product_tax" },
                    contactType: "html",
                    success: function (data_html) {
                        $("#tax_id").empty().append(data_html);
                        $("#tax_id").selectpicker("refresh");
                        $("#tax_id").selectpicker("val", tax_id);
                    },
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                })
            }
        },
    });
});


$(document).on("submit", "form#quick_add_color_form", function (e) {
    $("form#quick_add_color_form").validate();
    e.preventDefault();
    var data = new FormData(this);
    $("#editModal").modal("hide");
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                var color_id = result.color_id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/colors/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        $("#color_id").empty().append(data_html);
                        $("#color_id").selectpicker("refresh");
                        $("#color_id").selectpicker(
                            "val",
                            [color_id]
                        );
                    },
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                })
            }
        },
    });
});
var multiple_categories_array = [];
$("#category_id").change(function () {
    multiple_categories_array.push($(this).val());
});
$(document).on("submit", "form#quick_add_size_form", function (e) {
    $("form#quick_add_size_form").validate();
    e.preventDefault();
    var data = new FormData(this);
    $("#editModal").modal("hide");

    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                var size_id = result.size_id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/sizes/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        $("#size_id").empty().append(data_html);
                        $("#size_id").selectpicker("refresh");
                        $("#size_id").selectpicker(
                            "val",
                            [size_id]
                        );
                    },
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                })
            }
        },
    });
});
var multiple_grades_array = [];

$("#expiry_date").change(function () {
    if (
        $(this).val() != undefined &&
        $(this).val() != "" &&
        $(this).val() != null
    ) {
        $(".warning").removeClass("hide");
        $(".convert_status_expire").removeClass("hide");
    } else {
        $(".warning").addClass("hide");
        $(".convert_status_expire").addClass("hide");
    }
});


$(document).on("change", "#sku", function () {
    let sku = $(this).val();
    if (sku !== undefined &&
        sku !== "" &&
        sku !== null) {
        $.ajax({
            method: "get",
            url: "/dashboard/products/check-sku/" + sku,
            data: {},
            success: function (result) {
                if (!result.success) {
                    Swal.fire({
                        title: 'Error',
                        text: result.msg,
                        icon: 'error',
                    })
                    $("#sku").val("");
                }
            },
        });
    }

});
// $(document).on("change", "#name", function () {
//     checkName();
// });
//
// function checkName() {
//     let name = $("#name").val();
//     let system_mode = $("#system_mode").val();
//     if (system_mode != "garments") {
//         $.ajax({
//             method: "get",
//             url: "/dashboard/products/check-name",
//             data: {
//                 name: name,
//             },
//             success: function (result) {
//                 if (!result.success) {
//                     Swal.fire({
//                         title: 'Error',
//                         text: result.msg,
//                         icon: 'error',
//                     })
//                     $("#name").val("");
//                 }
//             },
//         });
//     }
// }

$(document).on("click", ".add_discount_row", function () {
    let row_id = parseInt($("#raw_discount_index").val());
    $("#raw_discount_index").val(row_id + 1);
    $.ajax({
        method: "get",
        url: "/dashboard/products/get-raw-discount",
        data: { row_id: row_id },
        success: function (result) {
            $("#consumption_table_discount > tbody").prepend(result);
            $(".selectpicker").selectpicker("refresh");
            $(".datepicker").datepicker({ refresh: "refresh", todayHighlight: true, });

            // $(".raw_material_unit_id").selectpicker("refresh");
        },
    });
});


$(document).on("change", "#discount", function () {
    let discount = __read_number($(this));
    if (discount > 0) {
        $("#discount_customer_types").attr("required", true);
    } else {
        $("#discount_customer_types").attr("required", false);
    }
});


$(document).on("change", "#is_discount_permenant", function () {
    $(".discount_start_date").prop('disabled', (i, v) => !v);
    $(".discount_start_date").val(null);
    $(".discount_end_date").prop('disabled', (i, v) => !v);
    $(".discount_end_date").val(null);
});
