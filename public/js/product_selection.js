var product_selected = [];
$(document).ready(function () {
    const typeRequest = $("#typeRequest").val();
    let url_;
    if(typeRequest === 'lenses'){
          url_ ="/dashboard/products?type=lenses";
    }else if(typeRequest === 'all'){
        url_ ="/dashboard/products?type=all";
    }else{
          url_ ="/dashboard/products";
    }

    product_table = $("#product_selection_table").DataTable({
        lengthChange: true,
        paging: true,
        info: false,
        bAutoWidth: false,
        deferLoading: 0,
        order: [],
        language: {
            url: dt_lang_url,
        },
        lengthMenu: [
            [10, 25, 50, 75, 100, 200, 500, -1],
            [10, 25, 50, 75, 100, 200, 500, "All"],
        ],
        dom: "lBfrtip",
        buttons: buttons,
        processing: true,
        serverSide: true,
        aaSorting: [[2, "asc"]],
        ajax: {
            url:url_ ,
            data: function (d) {
                d.category_id = $("#filter_category_id").val();
                d.brand_id = $("#filter_brand_id").val();
                d.color_id = $("#filter_color_id").val();
                d.size_id = $("#filter_size_id").val();
                d.tax_id = $("#filter_tax_id").val();
                if ($("#sender_store_id").length) {
                    //in add transfer page
                    d.store_id = $("#sender_store_id").val();
                }else if($("#store_id").length){
                    d.store_id = $("#store_id").val();

                } else {
                    d.store_id = $("#filter_store_id").val();
                }
                d.customer_type_id = $("#filter_customer_type_id").val();
                d.created_by = $("#filter_created_by").val();
                d.is_add_stock = $("#is_add_stock").val();
            },
        },
        columnDefs: [
            {
                targets: [0, 1],
                orderable: false,
                searchable: false,
            },
        ],
        columns: [



            {
                data: "selection_checkbox",
                name: "selection_checkbox",
                searchable: false,
                orderable: false,
            },
            { data: "image", name: "image" },
            { data: "name", name: "products.name" },
            { data: "sku", name: "products.sku" },
            { data: "categories_names", name: "categories_names" },
            { data: "purchase_history", name: "purchase_history" },
            { data: "batch_number", name: "add_stock_lines.batch_number" },
            {
                data: "sell_price",
                name: "sell_price",
            },
            { data: "tax", name: "taxes.name" },
            { data: "brand", name: "brands.name" },
            { data: "color", name: "colors.name" },
            { data: "size", name: "sizes.name" },
            { data: "current_stock", name: "current_stock", searchable: false },
            { data: "customer_type", name: "customer_type" },
            {
                data: "manufacturing_date",
                name: "add_stock_lines.manufacturing_date",
            },
            { data: "discount", name: "discount" ,searchable: false},
            {
                data: "default_purchase_price",
                name: "default_purchase_price",
                searchable: false,
            },
            { data: "created_by", name: "admins.name" },
            { data: "edited_by_name", name: "edited.name" },
            { data: "action", name: "action" },
        ],
        createdRow: function (row, data, dataIndex) {},
        fnDrawCallback: function (oSettings) {
            __currency_convert_recursively($("#product_table"));
        },
    });
});


$(document).on("change", ".filter_product", function () {
    product_table.ajax.reload();
});
$(document).on("click", ".clear_filters", function () {
    $(".filter_product").val("");
    $(".filter_product").selectpicker("refresh");
    product_table.ajax.reload();
});

$(document).on("change", ".product_selected", function () {
    let this_variation_id = $(this).val();
    let this_product_id = $(this).data("product_id");
    if ($(this).prop("checked")) {
        var obj = {};
        obj["product_id"] = this_product_id;
        obj["variation_id"] = this_variation_id;
        obj["qty"] = 1;
        product_selected.push(obj);
    } else {
        product_selected = product_selected.filter(function (item) {
            return (
                item.product_id !== this_product_id &&
                item.variation_id !== this_variation_id
            );
        });
    }
    //remove duplicate object from array
    product_selected = product_selected.filter(
        (value, index, self) =>
            index ===
            self.findIndex(
                (t) =>
                    t.product_id === value.product_id &&
                    t.variation_id === value.variation_id
            )
    );
});

$("#select_products_modal").on("shown.bs.modal", function () {
    product_selected = [];
    product_table.ajax.reload();
});
