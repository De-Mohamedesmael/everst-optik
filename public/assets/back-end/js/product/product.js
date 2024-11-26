$(() => {
    let usrCfg = {
        height: "200",
        toolbar: [
            ["style", ["bold", "italic", "underline", "clear"]],
            ["font", ["strikethrough", "superscript", "subscript"]],
            ["fontsize", ["fontsize"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["height", ["height"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "codeview", "help"]],
        ],
        width: "inherit",
    };
    $("#product_details").summernote(usrCfg);
});
$(document).on('change', '.width,.height,.length', function () {
    var key = $(this).data('key');
    let width = parseFloat($('#width' + key).val());
    let height = parseFloat($('#height' + key).val());
    let length = parseFloat($('#length' + key).val());
    let size = width * height * length;
    console.log(size)
    $('#size' + key).val(size);
});
$(document).on("click", ".add_price_row", function () {
    let row_id = parseInt($("#raw_price_index").val());
    $("#raw_price_index").val(row_id + 1);
    $.ajax({
        method: "get",
        url: "/product/get-raw-price",
        data: { row_id: row_id },
        success: function (result) {
            $("#consumption_table_price > tbody").prepend(result);
        },
    });
});
$(document).on("click", ".remove_row", function () {
    row_id = $(this).closest("tr").data("row_id");
    $(this).closest("tr").remove();
});
$(document).on("change", ".is_price_permenant", function () {
    $(this).closest("tr").find(".price_start_date").prop('disabled', (i, v) => !v);
    $(this).closest("tr").find(".price_start_date").val(null);
    $(this).closest("tr").find(".price_end_date").prop('disabled', (i, v) => !v);
    $(this).closest("tr").find(".price_end_date").val(null);
});

// >>>>>>> new_test+
$(document).ready(function () {
    $('.js-example-basic-multiple').select2(
        {
            placeholder: LANG.please_select,
            tags: true
        }
    );
});



function getSelectBoxValues() {
    var selectedValues = [];
    $('.unit_select').each(function () {
        if ($(this).val() !== '') {
            selectedValues.push($(this).val());
        }
    });
    return selectedValues;
}

$(document).on("click", ".add_product_row", function () {
    let row_id = parseInt($("#raw_product_index").val()) + 1;
    $("#raw_product_index").val(row_id);
    console.log(row_id)
    $.ajax({
        method: "get",
        url: "/product/add_product_raw",
        data: { row_id: row_id },
        success: function (result) {
            $(".product_raws").append(result);
            $('.select2').select2();
        },
    });
});

