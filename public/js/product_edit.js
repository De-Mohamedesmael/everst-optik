$(document).ready(function () {
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

$(".show_to_customer_type_div").slideUp();
$("#show_to_customer").change(function () {
    if ($(this).prop("checked")) {
        $(".show_to_customer_type_div").slideUp();
    } else {
        $(".show_to_customer_type_div").slideDown();
    }
});
$(document).on("click", ".remove_row", function () {
    row_id = $(this).closest("tr").data("row_id");
    $(this).closest("tr").remove();
});



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





$(document).on("click", ".add_discount_row", function () {
    let row_id = parseInt($("#raw_discount_index").val());
    $("#raw_discount_index").val(row_id + 1);

    $.ajax({
        method: "get",
        url: "/product/get-raw-discount",
        data: { row_id: row_id },
        success: function (result) {
            $("#consumption_table_discount > tbody").prepend(result);
            $(".selectpicker").selectpicker("refresh");
            // $(".datepicker").datepicker("refresh");
            $(".datepicker").datepicker({refresh:"refresh",todayHighlight: true});

            // $(".raw_material_unit_id").selectpicker("refresh");
        },
    });
});
var brand_id = null;
$(document).on("submit", "form#quick_add_brand_form", function (e) {
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
                swal("Success", result.msg, "success");
                $(".view_modal").modal("hide");
                brand_id = result.brand_id;
                get_brand_dropdown();
            } else {
                swal("Error", result.msg, "error");
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
                swal("Success", result.msg, "success");
                $(".view_modal").modal("hide");
                var tax_id = result.tax_id;
                $.ajax({
                    method: "get",
                    url: "dashboard/taxes/get-dropdown",
                    data: { type: "product_tax" },
                    contactType: "html",
                    success: function (data_html) {
                        $("#tax_id").empty().append(data_html);
                        $("#tax_id").selectpicker("refresh");
                        $("#tax_id").selectpicker("val", tax_id);
                    },
                });
            } else {
                swal("Error", result.msg, "error");
            }
        },
    });
});


$(document).on("submit", "form#quick_add_color_form", function (e) {
    $("form#quick_add_color_form").validate();
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
                swal("Success", result.msg, "success");
                $(".view_modal").modal("hide");
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
                            color_id
                        );
                    },
                });
            } else {
                swal("Error", result.msg, "error");
            }
        },
    });
});

$(document).on("submit", "form#quick_add_size_form", function (e) {
    $("form#quick_add_size_form").validate();
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
                swal("Success", result.msg, "success");
                $(".view_modal").modal("hide");
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
                            size_id
                        );
                    },
                });
            } else {
                swal("Error", result.msg, "error");
            }
        },
    });
});

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

$(document).on("change", "#sell_price", function () {
    $(".store_prices").val($(this).val());
});
$(document).on("change", "#sku", function () {
    let sku = $(this).val();

    $.ajax({
        method: "get",
        url: "/dashboard/products/check-sku/" + sku,
        data: {},
        success: function (result) {
            console.log(result.success);
            if (!result.success) {
                swal("Error", result.msg, "error");
            }
        },
    });
});
$(document).on("change", "#purchase_price", function () {
    $(".default_purchase_price").val($(this).val());
});
$(document).on("change", "#sell_price", function () {
    $(".default_sell_price").val($(this).val());
});
$(document).on("click", ".delete-image", function () {
    let url = $(this).attr("data-href");
    let images_div = $(this).parent(".images_div");

    $.ajax({
        method: "get",
        url: url,
        data: {},
        success: function (result) {
            if (result.success) {
                swal("Success", result.msg, "success");
                $(images_div).remove();
            }
            if (!result.success) {
                swal("Error", result.msg, "error");
            }
        },
    });
});


$(document).on("click", ".remove_raw_material_btn", function () {
    calculate_price_base_on_raw_material();
});
$(document).ready(function () {
    $("#discount").change();
    $("#consumption_table > tbody > tr").each(function () {
        let raw_material_price = __read_number(
            $(this).find(".raw_material_price")
        );
        let raw_material_quantity = __read_number(
            $(this).find(".raw_material_quantity")
        );
        let raw_material_total = raw_material_price * raw_material_quantity;

        $(this)
            .find(".cost_label")
            .text(__currency_trans_from_en(raw_material_total, false));
    });
});
$(document).on("change", "#discount", function () {
    let discount = __read_number($(this));
    if (discount > 0) {
        $("select#discount_customer_types").attr("required", true);
    } else {
        $("select#discount_customer_types").attr("required", false);
    }
});
$(document).on("change", "#is_service", function () {
    if ($(this).prop("checked")) {
        $(this).val(1);
        $(".supplier_div").removeClass("hide");
        $(".sell_price").removeClass('hide');
        $(".purchase_price").removeClass('hide');
        $(".purchase_price_th").removeClass('hide');
        $(".sell_price_th").removeClass('hide');
        $(".default_purchase_price_td").removeClass('hide');
        $(".default_sell_price_td").removeClass('hide');
        $(".default_purchase_price_th").removeClass('hide');
        $(".default_sell_price_th").removeClass('hide');
    } else {
        $(this).val(0);
        $(".supplier_div").addClass("hide");
        $(".sell_price").addClass('hide');
        $(".purchase_price").addClass('hide');
        $(".purchase_price_th").addClass('hide');
        $(".sell_price_th").addClass('hide');
        $(".default_purchase_price_td").addClass('hide');
        $(".default_sell_price_td").addClass('hide');
        $(".default_purchase_price_th").addClass('hide');
        $(".default_sell_price_th").addClass('hide');
    }
});
$(document).on("change", "#sell_price", function () {
    let sell_price = __read_number($(this));
    let purchase_price = __read_number($("#purchase_price"));

    if (sell_price < purchase_price) {
        swal(LANG.warning, LANG.sell_price_less_than_purchase_price, "warning");
        return;
    }
});
$(document).on("change",".is_discount_permenant",function () {
    $(this).closest("tr").find(".discount_start_date").prop('disabled', (i, v) => !v);
    $(this).closest("tr").find(".discount_start_date").val(null);
    $(this).closest("tr").find(".discount_end_date").prop('disabled', (i, v) => !v);
    $(this).closest("tr").find(".discount_end_date").val(null);
});
var multiple_categories_array = [];
$("#category_id").change(function () {
    multiple_categories_array.push($(this).val());
});
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
                    url:"/dashboard/categories/get-dropdown",
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
                swal("Error", result.msg, "error");
            }
        },
    });
});
