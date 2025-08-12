$(document).ready(function () {


    customer_sales_table = $("#customer_sales_table").DataTable({
        lengthChange: true,
        paging: true,
        info: false,
        bAutoWidth: false,
        language: {
            url: dt_lang_url,
        },
        lengthMenu: [
            [10, 25, 50, 75, 100, 200, 500, -1],
            [10, 25, 50, 75, 100, 200, 500, "All"],
        ],
        dom: "lBfrtip",
        stateSave: true,
        buttons: buttons,
        processing: true,
        serverSide: true,
        aaSorting: [[0, "desc"]],
        initComplete: function () {
            $(this.api().table().container())
                .find("input")
                .parent()
                .wrap("<form>")
                .parent()
                .attr("autocomplete", "off");
        },
        ajax: {
            url: "/dashboard/pos/get-recent-transactions",
            data: function (d) {
                d.customer_id = $("#customer_id").val();
            },
        },
        columnDefs: [
            {
                targets: [8],
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: "transaction_date", name: "transaction_date" },
            { data: "invoice_no", name: "invoice_no" },
            { data: "final_total", name: "final_total" },
            { data: "method", name: "transaction_payments.method" },
            { data: "ref_number", name: "transaction_payments.ref_number" },
            { data: "status", name: "transactions.status" },
            { data: "created_by", name: "users.name" },
            { data: "canceled_by", name: "canceled_by" },
            { data: "action", name: "action" },
        ],
        createdRow: function (row, data, dataIndex) {},
        footerCallback: function (row, data, start, end, display) {
            var intVal = function (i) {
                return typeof i === "string"
                    ? i.replace(/[\$,]/g, "") * 1
                    : typeof i === "number"
                        ? i
                        : 0;
            };

            this.api()
                .columns(".sum", { page: "current" })
                .every(function () {
                    var column = this;
                    if (column.data().count()) {
                        var sum = column.data().reduce(function (a, b) {
                            a = intVal(a);
                            if (isNaN(a)) {
                                a = 0;
                            }

                            b = intVal(b);
                            if (isNaN(b)) {
                                b = 0;
                            }

                            return a + b;
                        });
                        $(column.footer()).html(
                            __currency_trans_from_en(sum, false)
                        );
                    }
                });
        },
    });
    $('#store_table_filter input').attr('autocomplete', 'off');
    //Prevent enter key function except texarea
    $("form").on("keyup keypress", function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13 && e.target.tagName != "TEXTAREA") {
            e.preventDefault();
            return false;
        }
    });

    if ($("form#edit_pos_form").length > 0) {
        pos_form_obj = $("form#edit_pos_form");
    } else {
        pos_form_obj = $("form#add_pos_form");
    }
    setTimeout(() => {
        $("input#search_product").focus();
    }, 2000);
});

$(document).on("click", "#category-filter", function (e) {
    e.stopPropagation();
    if ($(this).prop("checked")) {
        $(".filter-window").show("slide", { direction: "right" }, "fast");
        $(".category").show();
        $(".brand").hide();
    } else {
        getFilterProductRightSide();
    }
});


$(document).on("click", "#brand-filter", function (e) {
    e.stopPropagation();
    if ($(this).prop("checked")) {
        $(".filter-window").show("slide", { direction: "right" }, "fast");
        $(".brand").show();
        $(".category").hide();
        $(".sub_category").hide();
    } else {
        getFilterProductRightSide();
    }
});

$(
    ".selling_filter, .price_filter, .expiry_filter, .sorting_filter, .sale_promo_filter"
).change(function () {
    let class_name = $(this).attr("class");
    let this_status = $(this).prop("checked");

    $("." + class_name).prop("checked", false);
    if (this_status !== false) {
        $(this).prop("checked", true);
    }
    getFilterProductRightSide();
});

$("body").on("click", function (e) {
    $(".filter-window").hide("slide", { direction: "right" }, "fast");
});

function getFilterCheckboxValue(class_name) {
    let val = null;

    $("." + class_name).each((i, ele) => {
        if ($(ele).prop("checked")) {
            val = $(ele).val();
        }
    });
    return val;
}
function roundToNearestQuarter(number) {
    return Math.round(number * 4) / 4;
}
$(document).on("click", ".filter-by", function () {
    let type = $(this).data("type");
    let id = $(this).data("id");

    if (type === "category" && $("#category-filter").prop("checked")) {
        getFilterProductRightSide(id, null, null);
    }
    if (type === "sub_category" && $("#sub-category-filter").prop("checked")) {
        getFilterProductRightSide(null, id, null);
    }
    if (type === "brand" && $("#brand-filter").prop("checked")) {
        getFilterProductRightSide(null, null, id);
    }
});

//on change event jquery
$(document).on("change", "#store_id", function () {
    getFilterProductRightSide();
    if ($("form#edit_pos_form").length == 0) {
        getCurrencyDropDown();
    }
    if ($("#store_id").val()) {
        $.ajax({
            method: "get",
            url: "/dashboard/store-pos/get-pos-details-by-store/" + $("#store_id").val(),
            data: {},
            success: function (result) {
                if (result) {
                    $("#store_pos_id").html(
                        `<option value="${result.id}">${result.name}</option>`
                    );
                    $("#store_pos_id").selectpicker("refresh");
                    $("#store_pos_id").selectpicker("val", result.id);
                } else {
                    $("#store_pos_id").html("");
                    $("#store_pos_id").selectpicker("refresh");
                }
            },
        });

        $.ajax({
            method: "GET",
            url: "/dashboard/tax/get-dropdown-html-by-store",
            data: { store_id: $("select#store_id").val() },
            success: function (result) {
                $('select#tax_id').html(result);
                $('select#tax_id').val($('#tax_id_hidden').val());
                $('select#tax_id').selectpicker('refresh');
            },
        });
    }
});

function getCurrencyDropDown() {
    let store_id = $("#store_id").val();
    let default_currency_id = $("#default_currency_id").val();

    $.ajax({
        method: "get",
        url: "/dashboard/exchange-rate/get-currency-dropdown",
        data: { store_id: store_id },
        success: function (result) {
            $("#received_currency_id").html(result);
            $("#received_currency_id").val(default_currency_id);
            $("#received_currency_id").change();
            $("#received_currency_id").selectpicker("refresh");
        },
    });
}

$(document).on("change", "select#received_currency_id", function () {
    let currency_id = $(this).val();
    let store_id = $("#store_id").val();
    getFilterProductRightSide();
    $.ajax({
        method: "GET",
        url: "/dashboard/exchange-rate/get-exchange-rate-by-currency",
        data: {
            store_id: store_id,
            currency_id: currency_id,
        },
        success: function (result) {
            $("#exchange_rate").val(result.conversion_rate);
            $("#exchange_rate").change();
        },
    });
});
$(document).on("change", "input[name=restaurant_filter]", function () {
    let product_class_id = null;
    if ($(this).val() === "all") {
        $(".sale_promo_filter").prop("checked", false);
    } else if ($(this).val() === "promotions") {
        $(".sale_promo_filter").prop("checked", true);
    } else {
        $(".sale_promo_filter").prop("checked", false);
        product_class_id = $(this).val();
    }
    getFilterProductRightSide(null, null, null, product_class_id);
});
$(document).ready(function () {
    $("#store_id").change();
    getFilterProductRightSide();
});

function getFilterProductRightSide(
    category_id = null,
    brand_id = null,
) {
    var selling_filter = getFilterCheckboxValue("selling_filter");
    var price_filter = getFilterCheckboxValue("price_filter");
    var sorting_filter = getFilterCheckboxValue("sorting_filter");
    var store_id = $("#store_id").val();
    let currency_id = $("select#received_currency_id").val();

    $.ajax({
        method: "get",
        url: "/dashboard/pos/get-products-items-by-filter",
        data: {
            selling_filter,
            price_filter,
            sorting_filter,
            store_id,
            category_id,
            brand_id,
            currency_id,
        },
        dataType: "html",
        success: function (result) {
            $("#filter-product-table > tbody").hide();
            $("#filter-product-table > tbody").empty().append(result);
            $("#filter-product-table > tbody").show(100);
        },
    });
}

$(document).ready(function () {
    //Add products_

    if ($("#search_product").length > 0) {
        $("#search_product")
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(
                        "/dashboard/pos/get-products",
                        { store_id: $("#store_id").val(), term: request.term },
                        response
                    );
                },
                minLength: 2,
                response: function (event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        $(this)
                            .data("ui-autocomplete")
                            ._trigger("select", "autocompleteselect", ui);
                        $(this).autocomplete("close");
                    }
                    else if (ui.content.length == 0) {
                        get_label_product_row(
                            null,
                            null,
                            null,
                            1,
                            0,
                            $("#search_product").val()
                        );
                    }
                },
                focus: function (event, ui) {
                    if (ui.item.qty_available <= 0) {
                        return false;
                    }
                },
                select: function (event, ui) {
                    if (ui.item.is_sale_promotion) {
                        get_sale_promotion_products(ui.item.sale_promotion_id);
                        return;
                    }
                    if (!ui.item.is_service) {
                        if (ui.item.qty_available > 0) {
                            $(this).val(null);
                            if(ui.item.batch_number!==null){
                                get_label_product_row(
                                   ui.item.product_id,
                                   ui.item.add_stock_lines_id
                               );
                           }else{
                                 get_label_product_row(
                                   ui.item.product_id
                               );
                           }
                        } else {
                            out_of_stock_handle(
                                ui.item.product_id
                            );
                        }
                    } else {
                        get_label_product_row(
                            ui.item.product_id,
                            ui.item.add_stock_lines_id
                        );
                    }
                },
                messages: {
                    noResults: "",
                    results: function () {},
                },
            })
            .autocomplete("instance")._renderItem = function (ul, item) {
            var string = "";
            if (item.is_service == 0 && item.qty_available <= 0) {
                string +=
                    '<li class="ui-state-disabled">'
                    +item.text +
                    " (" +
                    LANG.out_of_stock +
                    ") </li>";
            } else {
                if(item.batch_number==null){
                    string += item.text ;
                    }
                    else{
                    string += item.text +"  "+ item.batch_number;
                    }
            }
            return $("<li>")
                .append("<div>" + string + "</div>")
                .appendTo(ul);
        };
    }
});

function get_label_product_row(
    product_id = null,
    add_stock_lines_id=null,
    edit_quantity = 1,
    edit_row_count = 0,
    weighing_scale_barcode = null,
    KeyLens=null,
    sell_lines_id=null,
) {
    //Get item addition method
    var add_via_ajax = true;

    var is_added = false;
    var is_batch = false;
    console.log('get_label_product_row => ', sell_lines_id);
    //Search for variation id in each row of pos table
    $("#product_table tbody")
        .find("tr")
        .each(function () {
            var row_p_id = $(this).find(".product_id").val();
            var row_batch_number = $(this).find(".batch_number_id").val();
            if(add_stock_lines_id!=null){
                if (row_p_id == product_id && row_batch_number ==add_stock_lines_id && !is_added) {
                    add_via_ajax = false;
                    is_added = true;
                    is_batch=true;
                    //Increment products quantity
                    qty_element = $(this).find(".quantity");
                    var qty = __read_number(qty_element);
                    var max = $('#quantity').attr('max');
                    if (qty+1 > max){
                        out_of_stock_handle(
                            row_p_id
                        );
                    }
                    else {
                        __write_number(qty_element, qty + 1);
                        qty_element.change;
                        calculate_sub_totals();
                        $("input#search_product").val("");
                        $("input#search_product").focus();
                        $(this).insertBefore($("#product_table  tbody tr:first"));
                    }

                }
            }else{
                let row_p_id = $(this).find(".product_id").val();

                if (row_p_id == product_id && row_batch_number ==false && !is_added) {
                    add_via_ajax = false;
                    is_added = true;
                    is_batch=false;
                    //Increment products quantity
                    qty_element = $(this).find(".quantity");
                    var qty = __read_number(qty_element);
                    var max = $('#quantity').attr('max');
                    if (qty+1 > max){
                        out_of_stock_handle(
                            row_p_id
                        );
                    }
                    else {
                        __write_number(qty_element, qty + 1);
                        qty_element.change;
                        calculate_sub_totals();
                        $("input#search_product").val("");
                        $("input#search_product").focus();
                        $(this).insertBefore($("#product_table  tbody tr:first"));
                    }

                }
            }
        });

    if (add_via_ajax) {
        var store_id = $("#store_id").val();
        var customer_id = $("#customer_id").val();
        let currency_id = $("#received_currency_id").val();
        var store_pos_id = $("#store_pos_id").val();
        if (edit_row_count !== 0) {
            row_count = edit_row_count;
        } else {
            var row_count = parseInt($("#row_count").val());
            $("#row_count").val(row_count + 1);
        }

        $.ajax({
            method: "GET",
            url: "/dashboard/pos/add-products-row",
            dataType: "json",
            async: false,
            data: {
                product_id: product_id,
                row_count: row_count,
                store_id: store_id,
                store_pos_id : store_pos_id,
                customer_id: customer_id,
                currency_id: currency_id,
                edit_quantity: edit_quantity,
                weighing_scale_barcode: $("#search_product").val(),
                dining_table_id: $("#dining_table_id").val(),
                is_direct_sale: $("#is_direct_sale").val(),
                batch_number_id:add_stock_lines_id,
                sell_lines_id:sell_lines_id,
                KeyLens:KeyLens
            },
            success: function (result) {

                if (!result.success) {
                     Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
                    return;
                }

                $("table#product_table tbody").prepend(result.html_content);
                $("input#search_product").val("");
                $("input#search_product").focus();
                calculate_sub_totals();
                reset_row_numbering();
            },
        });
    }
}
function reset_row_numbering() {
    $("#product_table > tbody  > tr").each((ele, tr) => {
        $(tr)
            .find(".row_number")
            .text(ele + 1);
    });
}



function calculate_sub_totals() {
    var grand_total = 0; //without any discounts
    var total = 0;
    var item_count = 0;
    var product_discount_total = 0;
    var product_surplus_total = 0;
    var total_item_tax = 0;
    var total_tax_payable = 0;
    var total_coupon_discount = 0;
    let item_quantity=0;
    let total_before_discount=0;
    var exchange_rate = __read_number($("#exchange_rate"));
    $("#product_table > tbody  > tr").each((ele, tr) => {
        if (!$(tr).hasClass("is_lens_row")) {
            let quantity = __read_number($(tr).find(".quantity"));
            item_quantity+=quantity;
            let sell_price = __read_number($(tr).find(".sell_price"),false,true);

            let price_hidden = __read_number($(tr).find(".price_hidden"),false,true);
            console.log(price_hidden,sell_price);
            let sub_total = 0;
            if (sell_price > price_hidden) {

                let price_discount = (sell_price - price_hidden);

                $(tr).find(".product_discount_type").val("surplus");
                __write_number(
                    $(tr).find(".product_discount_value"),
                    price_discount / exchange_rate
                );
                __write_number(
                    $(tr).find(".product_discount_amount"),
                    price_discount / exchange_rate
                );
                $(tr).find(".plus_sign_text").text("+");
                sub_total = sell_price * quantity;
            } else if (sell_price < price_hidden) {
                let price_discount = (price_hidden - sell_price);

                $(tr).find(".product_discount_type").val("fixed");
                __write_number(
                    $(tr).find(".product_discount_value"),
                    price_discount / exchange_rate
                );
                __write_number(
                    $(tr).find(".product_discount_amount"),
                    price_discount / exchange_rate
                );
                $(tr).find(".plus_sign_text").text("-");
                sub_total = price_hidden * quantity;
            } else {
                sub_total = price_hidden * quantity;
            }
            __write_number($(tr).find(".sub_total"), sub_total);
            let product_discount = calculate_product_discount(tr);
            product_discount_total += product_discount;

            total_before_discount+=sub_total;
            sub_total -= product_discount;
            grand_total += sub_total;
            $(".grand_total_span").text(
                __currency_trans_from_en(grand_total, false)
            );

            let coupon_discount = calculate_coupon_discount(tr);
            if (sub_total > coupon_discount) {
                total_coupon_discount += coupon_discount;
            }
            if (sub_total <= coupon_discount) {
                total_coupon_discount += sub_total;
            }

            __write_number($(tr).find(".sub_total"), sub_total);
            $(tr)
                .find(".sub_total_span")
                .text(__currency_trans_from_en(sub_total, false));
            total +=  roundToNearestQuarter(sub_total);
            item_count++;

            calculate_promotion_discount(tr);
            product_surplus_total += calculate_product_surplus(tr);

            let tax_method = $(tr).find(".tax_method").val();
            let tax_rate = $(tr).find(".tax_rate").val();
            let item_tax = (sub_total * tax_rate) / 100;
            item_tax = item_tax / exchange_rate;
            __write_number($(tr).find(".item_tax"), item_tax);
            total_item_tax += item_tax;
            if (tax_method === "exclusive") {
                total_tax_payable += item_tax;
            }

        }

    });

    $('#total_before_discount').text(__currency_trans_from_en(total_before_discount, false));
    $("#subtotal").text(__currency_trans_from_en(total, false));
    $(".subtotal").text(__currency_trans_from_en(total, false));
    $("#item").text(item_count);
    $("#item-quantity").text(item_quantity);
    $(".payment_modal_discount_text").text(
        __currency_trans_from_en(product_discount_total, false)
    );
    $(".payment_modal_surplus_text").text(
        __currency_trans_from_en(product_surplus_total, false)
    );
    $("#tax").text(__currency_trans_from_en(total_item_tax, false));
    __write_number($("#total_item_tax"), total_item_tax);
    console.log(total_item_tax);
    total += total_tax_payable;

    __write_number($("#grand_total"), grand_total); // total without any discounts
    total -= total_coupon_discount;
    let discount_amount = get_discount_amount(total);
    $(".discount_span").text(__currency_trans_from_en(discount_amount, false));
    total -= discount_amount;

    //calculate service fee
    let service_fee_rate = __read_number($("#service_fee_rate"));
    let dining_table_id = $("#dining_table_id").val();

    if (
        dining_table_id != null &&
        dining_table_id !== "" &&
        dining_table_id !== undefined
    ) {
        let service_fee_value = __get_percent_value(total, service_fee_rate);
        $("#service_fee_value").val(service_fee_value);
        $(".service_value_span").text(
            __currency_trans_from_en(service_fee_value, false)
        );
        service_fee_value = service_fee_value / exchange_rate;
        total += service_fee_value;
    }


    __write_number($("#final_total"), total);
    $("#final_total").change();

    $(".final_total_span").text(__currency_trans_from_en(total, false));
}
function hasManyDigits(num, digits) {
    const str = num.toString();
    const decimalIndex = str.indexOf('.');
    if (decimalIndex !== -1) {
        const numDigits = str.substr(decimalIndex + 1).length;
        return numDigits >= digits;
    }
    return false;
}



function calculate_product_surplus(tr) {
    let surplus = 0;

    let value = __read_number($(tr).find(".product_discount_value"));
    let type = $(tr).find(".product_discount_type").val();
    if (type == "surplus") {
        surplus = value;
    }

    return surplus;
}
function calculate_product_discount(tr) {
    let discount = 0;
    let exchange_rate = __read_number($("#exchange_rate"));
    let value = __read_number($(tr).find(".product_discount_value"));
    let type = $(tr).find(".product_discount_type").val();
    let quantity = $(tr).find(".quantity").val();
    let sub_total = __read_number($(tr).find(".sub_total"));
    if (type == "fixed" || type == "surplus") {
        discount = quantity * value;
    }
    if (type == "percentage") {
        discount = __get_percent_value(roundToNearestQuarter(sub_total), value);
    }
    if(exchange_rate==0){
        exchange_rate=1;
    }
    discount = discount / exchange_rate;
    if (type == "surplus") {
        discount = 0;
    }
     __write_number($(tr).find(".product_discount_amount"), discount);
    return discount;
}
function calculate_promotion_discount(tr) {
    let discount = 0;
    let exchange_rate = __read_number($("#exchange_rate"));
    let value = __read_number($(tr).find(".promotion_discount_value"));
    let type = $(tr).find(".promotion_discount_type").val();
    let sub_total = __read_number($(tr).find(".sub_total"));
    if (type == "fixed") {
        discount = value;
    }
    if (type == "percentage") {
        discount = __get_percent_value(roundToNearestQuarter(sub_total), value);
    }
    discount = discount / exchange_rate;
    $(tr).find(".promotion_discount_amount").val(discount);
}

function apply_promotion_discounts() {
    let exchange_rate = __read_number($("#exchange_rate"));
    let promo_discount = 0;
    let final_total = __read_number($("#final_total"));
    $("#product_table > tbody  > tr").each((ele, tr) => {
        let promotion_discount_amount = __read_number(
            $(tr).find(".promotion_discount_amount")
        );
        let promotion_purchase_condition = __read_number(
            $(tr).find(".promotion_purchase_condition")
        );
        let promotion_purchase_condition_amount = __read_number(
            $(tr).find(".promotion_purchase_condition_amount")
        );

        if (promotion_purchase_condition) {
            if (final_total > promotion_purchase_condition_amount) {
                promo_discount += promotion_discount_amount;
            }
        } else {
            promo_discount += promotion_discount_amount;
        }
    });
    let total_package_promotion_discount = __read_number(
        $("#total_pp_discount")
    );
    let total_sp_discount = total_package_promotion_discount + promo_discount;
    $("#total_sp_discount").val(total_sp_discount / exchange_rate);

    return promo_discount;
}
function calculate_coupon_discount(tr) {
    let discount = 0;
    let exchange_rate = __read_number($("#exchange_rate"));
    let value = __read_number($(tr).find(".coupon_discount_value"));
    let type = $(tr).find(".coupon_discount_type").val();
    let sub_total = __read_number($(tr).find(".sub_total"));
    if (type == "fixed") {
        discount = value;
    }
    if (type == "percentage") {
        discount = __get_percent_value(roundToNearestQuarter(sub_total), value);
    }
    discount = discount / exchange_rate;
    $(tr).find(".coupon_discount_amount").val(discount);

    return discount;
}
$(document).on("change", "#final_total", function (e) {
    let final_total = __read_number($("#final_total"));

    __write_number($("#final_total"), final_total);
    $(".final_total_span").text(__currency_trans_from_en(final_total, false));

    __write_number($("#amount"), final_total);
    __write_number($("#paying_amount"), final_total);
});

$("#discount_btn").click(function () {
    calculate_sub_totals();
});

$("#tax_btn").click(function () {
    calculate_sub_totals();
});

// function get_tax_amount(total) {
//     console.log('get_tax_amount')
//     let tax_rate = parseFloat($("#tax_id").find(":selected").data("rate"));
//     let tax_type = $("#tax_type").val();
//     let tax_method = $("#tax_method").val();
//     let tax_amount = 0;
//     // if (tax_type === "general_tax") {
//     //     if (!isNaN(tax_rate)) {
//             tax_amount = __get_percent_value(total, tax_rate);
//     //     }
//     // }
//
//     if (tax_method === "exclusive") {
//         $("#tax").text(__currency_trans_from_en(tax_amount, false));
//     } else {
//         $("#tax").text(__currency_trans_from_en(0, false));
//     }
//     __write_number($("#total_tax"), tax_amount);
//
//     if (tax_method === "exclusive") {
//         return tax_amount;
//     }
//     return 0;
// }
function get_discount_amount(total) {
    let discount_type = $("#discount_type").val();
    let discount_value = __read_number($("#discount_value"));
    let exchange_rate = __read_number($("#exchange_rate"));
    let discount_amount = 0;
    if (discount_value) {
        if (discount_type === "fixed") {
            discount_amount = discount_value / exchange_rate;
        }
        if (discount_type === "percentage") {
            discount_amount = __get_percent_value(total, discount_value);
        }
    }
    discount_amount = discount_amount;
    $("#discount").text(__currency_trans_from_en(discount_amount, false));
    __write_number($("#discount_amount"), discount_amount);
    return discount_amount;
}

$(document).on(
    "change",
    "#discount_value, #discount_type, #tax_id",
    function () {
        calculate_sub_totals();
    }
);

$(document).on("change", ".sell_price", function () {
    let tr = $(this).parents("tr");
    let sell_price = __read_number($(this));
    let purchase_price = __read_number($(tr).find(".purchase_price"));

    if (sell_price < purchase_price) {
        Swal.fire({
            title:LANG.warning,
            text:LANG.sell_price_less_than_purchase_price,
            icon:"warning"});
        return;
    }else{
        //change price
        Swal.fire({
            title: "",
            text: LANG.change_price_permenatly,
            icon: "warning",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
            confirmButtonText: 'Save',
        })
        .then((isConfirm) => {
            if (isConfirm) {
            $.ajax({
                type: "post",
                url: "/dashboard/pos/change-selling-price/"+$(this).data('product_id'),
                data: {sell_price:sell_price},
                success: function (response) {
                     Swal.fire({
                        title: 'Success',
                        text: response.msg,
                        icon: 'success',
                    })
                }
            });
            } else {
                Swal.fire({
                    title: "Success",
                    text:LANG.price_changed_only_for_this_transaction,
                    icon:"success"
                });
            }
        });
    }
});
$(document).on("change", ".quantity, .sell_price", function () {
    calculate_sub_totals();
});
$(document).on("click", ".remove_row", function () {
    var index=$(this).data('index');
    $(this).closest("tr").remove();
    $("tr.lens-row-"+index).remove();

    calculate_sub_totals();
    reset_row_numbering();
    $(this).find(".change").text(0);
    __write_number($("#add_to_customer_balance"),0);
    $(".add_to_customer_balance").attr("disabled", false);
    $(".add_to_customer_balance").addClass("hide");
});
$(document).on("click", ".minus", function () {
    let tr = $(this).closest("tr");
    let qty = parseFloat($(tr).find(".quantity").val());

    let new_qty = qty - 1;
    if (new_qty < 0.1) {
        return;
    }

    $(tr).find(".quantity").val(new_qty).change();
});
$(document).on("click", ".plus", function () {
    let tr = $(this).closest("tr");
    let qty = parseFloat($(tr).find(".quantity").val());
    let max = parseFloat($(tr).find(".quantity").attr("max"));
    let is_service = parseInt($(tr).find(".is_service").val());
    let new_qty = qty + 1;
    if (!is_service) {
        if (new_qty < 0.1 || new_qty > max) {
            return;
        }
    }
    $(tr).find(".quantity").val(new_qty);
    $(tr).find(".quantity").trigger("change");
});

$(document).on("submit", "form#quick_add_customer_form", function (e) {
    e.preventDefault();
    var data = new FormData(this);
    $("#close_model_cus_create").trigger("click");

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
                $(".view_modal").modal("hide");
                var customer_id = result.customer_id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/customers/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        $("#customer_id").empty().append(data_html);
                        $("#customer_id").selectpicker("refresh");
                        $("#customer_id").selectpicker("val", customer_id);
                        getCustomerBalance();


                    },
                });
            } else {
                 Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
            }
        },
    });
});

$(document).on("click", ".quick_add_purchase_order", function () {
    let tr = $(this).closest("tr");
    let href = $(this).data("href");

    $.ajax({
        method: "get",
        url: href,
        data: { store_id: $("#store_id").val() },
        success: function (result) {
            if (result.success) {

                 Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                $(tr).find(".quick_add_purchase_order").remove();
            } else {
                 Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
            }
        },
    });
});

function out_of_stock_handle(product_id) {
    Swal.fire({
        title: LANG.out_of_stock,
        text: "",
        icon: "error",
        buttons: true,
        dangerMode: true,
        buttons: ["Cancel", "PO+"],
    }).then((addPO) => {
        if (addPO) {
            $.ajax({
                method: "get",
                url:
                    "/purchase-order/quick-add-draft?product_id=" +
                    product_id,
                data: { store_id: $("#store_id").val() },
                success: function (result) {
                    if (result.success) {
                         Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
                    } else {
                         Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
                    }
                },
            });
        }
    });
}

$(document).on("click", ".payment-btn", function (e) {
    var audio = $("#mysoundclip2")[0];
    audio.play();
    $(".btn-add-payment").removeClass("hide");
    let method = $(this).data("method");
    $(".method").val(method);
    $(".method").change();
    if (method === "deposit") {
        $(".deposit-fields").removeClass("hide");
        $(".customer_name_div").removeClass("hide");
        $(".btn-add-payment").addClass("hide");
        __write_number($("#amount"), 0);
    } else {
        $(".deposit-fields").addClass("hide");
        $(".customer_name_div").addClass("hide");
        $(".card_bank_field").addClass("hide");
        $(".btn-add-payment").removeClass("hide");

        let final_total = __read_number($("#final_total"));
        __write_number($("#amount"), final_total);
    }
    if (method === "cheque") {
        $(".cheque_field").removeClass("hide");
    } else {
        $(".cheque_field").addClass("hide");
    }
    if (method === "bank_transfer") {
        $(".bank_field").removeClass("hide");
        $(".card_bank_field").removeClass("hide");
    } else {
        $(".bank_field").addClass("hide");
    }
    if (method === "card") {
        $(".card_field").removeClass("hide");
        $(".card_bank_field").removeClass("hide");
    } else {
        $(".card_field").addClass("hide");
    }
    if (method === "gift_card") {
        $(".gift_card_field").removeClass("hide");
    } else {
        $(".gift_card_field").addClass("hide");
    }
    if (method === "cash") {
        $(".qc").removeClass("hide");
    } else {
        $(".qc").addClass("hide");
    }
    $("#status").val("final");
});

$(document).on("change", ".method", function (e) {
    let row = $(this).parents(".payment_row");
    let method = $(this).val();

    changeMethodFields(method, row);
});

function changeMethodFields(method, row) {
    $(".card_bank_field").addClass("hide");
    if (method === "deposit") {
        $(".deposit-fields").removeClass("hide");
        $(".customer_name_div").removeClass("hide");
        $(".btn-add-payment").addClass("hide");
    } else {
        $(".deposit-fields").addClass("hide");
        $(".customer_name_div").addClass("hide");
        $(".card_bank_field").addClass("hide");
        $(".btn-add-payment").removeClass("hide");
    }
    if (method === "cheque") {
        $(row).find(".cheque_field").removeClass("hide");
    } else {
        $(row).find(".cheque_field").addClass("hide");
    }
    if (method === "bank_transfer") {
        $(row).find(".bank_field").removeClass("hide");
        $(row).find(".card_bank_field").removeClass("hide");
    } else {
        $(row).find(".bank_field").addClass("hide");
    }
    if (method === "card") {
        $(row).find(".card_field").removeClass("hide");
        $(row).find(".card_bank_field").removeClass("hide");
    } else {
        $(row).find(".card_field").addClass("hide");
    }
    if (method === "gift_card") {
        $(".gift_card_field").removeClass("hide");
    } else {
        $(".gift_card_field").addClass("hide");
    }
}
$(document).on("click", ".qc-btn", function (e) {
    let first_amount_input = $("#payment_rows .payment_row")
        .first()
        .find(".received_amount");
    if ($(this).data("amount")) {
        if ($(".qc").data("initial")) {
            $(first_amount_input).val($(this).data("amount").toFixed(2));
            $(".qc").data("initial", 0);
        } else {
            $(first_amount_input).val(
                (
                    parseFloat($(first_amount_input).val()) +
                    $(this).data("amount")
                ).toFixed(2)
            );
        }
    } else {
        $(first_amount_input).val("0.00");
    }
    $(first_amount_input).change();
    $("#paying_amount").change();
});

$(document).on("change", ".received_amount", function () {
    let this_row = $(this).parents(".payment_row");

    $(this_row).nextAll().remove(); //remove all the next row if exist and recalculate the next row total
    let received_amount = 0;
    $("#payment_rows .payment_row").each((ele, row) => {
        let row_received_amount = parseFloat(
            $(row).find(".received_amount").val()
        );
        received_amount += row_received_amount;
    });

    let paying_amount = __read_number($("#paying_amount"));
    let change = Math.abs(received_amount - paying_amount);

    if (received_amount >= paying_amount) {
        $(this_row).find(".change_text").text("Change :");
        $(this_row)
            .find(".change")
            .text(__currency_trans_from_en(change, false));
        $(this_row).find(".change_amount").val(change);
        $(".add_to_customer_balance").removeClass("hide");
        $(document).on("click", ".add_to_customer_balance", function () {
            if($(".payment_way").val() != 'deposit'){ // or this.value == 'volvo'
                $(this_row).find("#add_to_customer_balance").val(change);
                $(this_row).find(".change_amount").val(0);
                $(this_row).find(".change").text(0);
                $(this).attr("disabled", true);
                let new_amount  = received_amount - change;
                $(this_row).find(".received_amount").val(new_amount)
            }else{
                $(".add_to_customer_balance").addClass("hide");
            }
        });

    } else {
        $(this_row)
            .find(".change")
            .text(__currency_trans_from_en(change, false));
        $(this_row).find(".pending_amount").val(change);
        $(this_row).find(".change_text").text("Pending Amount :");
    }
});

$(document).on("click", ".close-payment-madal", function () {
    __write_number($("#add_to_customer_balance"),0);
    $(".add_to_customer_balance").attr("disabled", false);
    $(".add_to_customer_balance").addClass("hide");

});





$(document).on("click", "#add_payment_row", function () {
    var row_count = $("#payment_rows .payment_row").length;
    let pending_amount = $("#payment_rows .payment_row")
        .last()
        .find(".pending_amount")
        .val();
    $.ajax({
        method: "get",
        url: "/dashboard/pos/get-payment-row",
        data: { index: row_count },
        dataType: "html",
        success: function (result) {
            $("#payment_rows").append(result);
            $("#payment_rows .payment_row")
                .last()
                .find(".received_amount")
                .val(pending_amount);
        },
    });
});
$(document).on("change", "#amount_to_be_used", function () {
    let amount_to_be_used = __read_number($("#amount_to_be_used"));
    let gift_card_current_balance = __read_number(
        $("#gift_card_current_balance")
    );

    let remaining_balance = gift_card_current_balance - amount_to_be_used;
    __write_number($("#remaining_balance"), remaining_balance);

    let final_total = __read_number($("#final_total"));

    let new_total = final_total - amount_to_be_used;
    __write_number($("#gift_card_final_total"), new_total);
    __write_number($("#amount"), amount_to_be_used);
});
$(document).on("change", "#gift_card_number", function () {
    let gift_card_number = $(this).val();
    let customer_id = $("#customer_id").val();
    $.ajax({
        method: "get",
        url: "/gift-card/get-details/" + gift_card_number,
        data: {},
        success: function (result) {
            if (!result.success) {
                $(".gift_card_error").text(result.msg);
            } else {
                let data = result.data;
                $("#gift_card_id").val(data.id);
                $(".gift_card_error").text("");
                $(".gift_card_current_balance").text(
                    __currency_trans_from_en(data.balance, false)
                );
                __write_number($("#gift_card_current_balance"), data.balance);
            }
        },
    });
});

var coupon_products = [];
var coupon_value = 0;
var coupon_type = null;
var amount_to_be_purchase = 0;
var amount_to_be_purchase_checkbox = 0;
$(document).on("click", ".coupon-check", function () {
    let coupon_code = $("#coupon-code").val();
    let customer_id = $("#customer_id").val();
    $.ajax({
        method: "get",
        url: "/coupon/get-details/" + coupon_code + "/" + customer_id,
        data: { store_id: $("#store_id").val() },
        success: function (result) {
            if (!result.success) {
                $("#coupon-code").val("");
                $(".coupon_error").text(result.msg);
            } else {
                $("#coupon_modal").modal("hide");
                let data = result.data;
                coupon_products = data.product_ids;
                coupon_value = data.amount;
                coupon_type = data.type;
                amount_to_be_purchase = data.amount_to_be_purchase;
                amount_to_be_purchase_checkbox =
                    data.amount_to_be_purchase_checkbox;
                $("#coupon_id").val(data.id);
                $(".coupon_error").text("");
                apply_coupon_to_products();
                calculate_sub_totals();
            }
        },
    });
});

function apply_coupon_to_products() {
    if (coupon_products.length) {
        $("#product_table > tbody  > tr").each((ele, tr) => {
            let product_id = parseInt($(tr).find(".product_id").val());
            if (amount_to_be_purchase_checkbox) {
                let grand_total = __read_number($("#grand_total"));
                if (grand_total >= amount_to_be_purchase) {
                    if (coupon_products.includes(product_id)) {
                        $(tr).find(".coupon_discount_value").val(coupon_value);
                        $(tr).find(".coupon_discount_type").val(coupon_type);
                    }
                }
            } else {
                if (coupon_products.includes(product_id)) {
                    $(tr).find(".coupon_discount_value").val(coupon_value);
                    $(tr).find(".coupon_discount_type").val(coupon_type);
                }
            }
        });
    }
}

$(document).on("click", "#print_and_draft", function (e) {
    $("#status").val("draft");
    $("#print_and_draft_hidden").val("print_and_draft");
    $("#sale_note_modal").modal("hide");
    //Check if products is present or not.
    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }

    pos_form_obj.submit();
});
$(document).on("click", "#draft-btn", function (e) {
    $("#status").val("draft");
    $("#sale_note_modal").modal("hide");
    //Check if products is present or not.
    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }
    $("#pay_from_balance").val(3);
    pos_form_obj.submit();
});
$(document).on("click", "#pay-later-btn", function (e) {
    //Check if products is present or not.
    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }
    // Get the text content of the element with the class "customer_balance"
    var balanceText = $(".customer_balance").text();

    // Convert the text content to a number
    var balance = parseFloat(balanceText);

    // if(balance > 0){
    //     $("#pay_from_balance").val(2);
    //     pos_form_obj.submit();
    // }else{
        $("#amount").val(0);
        pos_form_obj.submit();
    // }


});
$(document).on("click", "#quick-pay-btn", function (e) {
    //Check if products is present or not.
    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }
    $("#pay_from_balance").val("1")
    pos_form_obj.submit();
});
$(document).on("click", "#submit-btn", function (e) {
    //Check if products is present or not.

    // $("body").css({
    //     "overflow-y": "auto",
    //     "height": "fit-content"
    // }).removeClass("modal-open");
    // document.body.style.paddingRight = "";
    // $("#add-payment").attr("aria-hidden",true);
    // $("#add-payment").attr("aria-modal",'');
    // $("#add-payment").modal("hide").removeClass('show').hide();

    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }

    $(this).attr("disabled", true);
    $("#add-payment").modal("hide");
    $("#pay_from_balance").val("1")
    pos_form_obj.submit();
    setTimeout(() => {
        $("#submit-btn").attr("disabled", false);
    }, 2000);
});
var updateBtnClicked = false;
$("button#update-btn").click(function () {
        // Check if the button has not been clicked yet
        if (!updateBtnClicked) {
            // Perform the desired action
            $("#is_edit").val("");
            pos_form_obj.submit();

            // Set the flag to true to indicate the button has been clicked
            updateBtnClicked = true;

            // Disable the button after it has been clicked
            $(this).prop('disabled', true);
        }

});

$(document).ready(function () {
    pos_form_validator = pos_form_obj.validate({
        submitHandler: function (form) {
            $("#pos-save").attr("disabled", "true");
            var data = $(form).serialize();
            var balanceText = $(".customer_balance").text();

            // Convert the text content to a number
            var balance = parseFloat(balanceText);

            if(balance > 0){
                data =
                data +"&pay_from_balance="+$("#pay_from_balance").val()+
                "&terms_and_condition_id=" +
                $("#terms_and_condition_id").val();
            }else{
                data =
                data +
                "&terms_and_condition_id=" +
                $("#terms_and_condition_id").val();
            }

            var url = $(form).attr("action");
            $.ajax({
                method: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (result) {
                    if (result.success == 1) {

                        $("#add-payment").modal("hide");
                        toastr.success(result.msg);
                        if ($("#print_the_transaction").prop("checked") == false) {
                            if ($("#edit_pos_form").length > 0) {
                                setTimeout(() => {
                                    window.close();
                                }, 1500);
                            }
                        }

                        if (
                            $("#print_the_transaction").prop("checked") &&
                            $("#status").val() !== "draft" &&
                            $("#dining_action_type").val() !== "save"
                        ) {
                            pos_print(result.html_content);
                        }
                        if ($("#is_edit").val() == "1") {
                            pos_print(result.html_content);
                        }


                        reset_pos_form();
                        getFilterProductRightSide();
                        // get_recent_transactions();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
            $("div.pos-processing").hide();
            $("#pos-save").removeAttr("disabled");
        },
    });
});
function syntaxHighlight(json) {
    if (typeof json != "string") {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    return json.replace(
        /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
        function (match) {
            var cls = "number";
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = "key";
                } else {
                    cls = "string";
                }
            } else if (/true|false/.test(match)) {
                cls = "boolean";
            } else if (/null/.test(match)) {
                cls = "null";
            }
            return '<span class="' + cls + '">' + match + "</span>";
        }
    );
}
function pos_print(receipt) {
    $("#receipt_section").html(receipt);
    __currency_convert_recursively($("#receipt_section"));
    __print_receipt("receipt_section");
}

function reset_pos_form() {
    //If on edit page then redirect to Add POS page
    if ($("form#edit_pos_sell_form").length > 0) {
        setTimeout(function () {
            window.location = $("input#pos_redirect_url").val();
        }, 4000);
        return true;
    }
    if (pos_form_obj[0]) {
        pos_form_obj[0].reset();
    }
    $(
        "span.grand_total_span, span#subtotal, span.subtotal, span.discount_span, span.service_value_span, span#item, span#discount, span#tax, span#delivery-cost,span#sales_promotion-cost_span, span.final_total_span, span.customer_points_span, span.customer_points_value_span, span.customer_total_redeemable_span, .remaining_balance_text, .current_deposit_balance, span.gift_card_current_balance "
    ).text(0);
    $(
        "#uploaded_file_names, #amount,.received_amount, .change_amount, #paying_amount, #discount_value, #final_total, #grand_total,  #gift_card_id, #total_tax, #total_item_tax, #coupon_id, #change, .delivery_address, .delivery_cost, #delivery_cost, #customer_points_value, #customer_total_redeemable, #rp_redeemed, #rp_redeemed_value, #is_redeem_points, #add_to_deposit, #remaining_deposit_balance, #used_deposit_balance, #current_deposit_balance, #change_amount, #total_sp_discount, #customer_size_id_hidden, #customer_size_id, #sale_note_draft, #sale_note, #deliveryman_id_hidden, #total_sp_discount, #total_pp_discount, #dining_table_id, #print_and_draft_hidden, #manual_delivery_zone"
    ).val("");
    $("#dining_action_type").val("");
    $("#status").val("final");
    $("#row_count").val(0);
    $("#service_fee_value").val(0);
    $("button#submit-btn").attr("disabled", false);
    $("button#redeem_btn").attr("disabled", false);
    $("button.add_to_deposit").attr("disabled", false);
    set_default_customer();
    $("#tax_method").val("");
    $("#tax_rate").val("0");
    $("#tax_type").val("");
    $("#tax_id").val("");
    $("#tax_id").selectpicker("refresh");
    $("#payment_status").val("");
    $("#payment_status").selectpicker("refresh");
    $("#payment_status").change();
    $("#deliveryman_id").val("");
    $("#deliveryman_id").selectpicker("refresh");
    $("#delivery_zone_id").val("");
    $("#delivery_zone_id").selectpicker("refresh");
    $("#commissioned_employees").val("");
    $("#commissioned_employees").selectpicker("refresh");
    $(".shared_commission_div").addClass("hide");
    $("#terms_and_condition_id").val($("#terms_and_condition_hidden").val());
    $("#terms_and_condition_id").selectpicker("render");
    $("tr.product_row").remove();
    $(this).attr("disabled", false);
    $("#product_table > tbody").empty();
    $(".table_room_hide").removeClass("hide");
    $(".table_room_show").addClass("hide");

    let first_row = $("#payment_rows .payment_row").first();
    $(first_row).find(".change").text(__currency_trans_from_en(0, false));
    $(first_row).find(".change_text").text("Pending Amount :");
    $(first_row).find(".change_text").text("Change :");
    $(first_row).nextAll().remove();
    $("#customer_size_detail_section").empty();

    let setting_invoice_lang = $("#setting_invoice_lang").val();
    if (setting_invoice_lang) {
        $("#invoice_lang").val(setting_invoice_lang);
        $("#invoice_lang").selectpicker("refresh");
    } else {
        $("#invoice_lang").val("en");
        $("#invoice_lang").selectpicker("refresh");
    }

    let default_currency_id = $("#default_currency_id").val();
    $("#received_currency_id").val(default_currency_id);
    $("#received_currency_id").change();
    $("#received_currency_id").selectpicker("refresh");

    __write_number($("#add_to_customer_balance"),0);
    $(".add_to_customer_balance").attr("disabled", false);
    $(".add_to_customer_balance").addClass("hide");
}
$(document).ready(function () {
    $("#terms_and_condition_id").val($("#terms_and_condition_hidden").val());
    $("#terms_and_condition_id").selectpicker("render");
});
function set_default_customer() {
    var default_customer_id = parseInt($("#default_customer_id").val());

    $("select#customer_id").val(default_customer_id).trigger("change");
}

function confirmCancel() {
    var audio = $("#mysoundclip2")[0];
    audio.play();
    if (confirm("Are you sure want to reset?")) {
        if ($("form#edit_pos_form").length > 0) {
            if (
                $("#dining_table_id").val() != null &&
                $("#dining_table_id").val() != undefined &&
                $("#dining_table_id").val() != ""
            ) {
                let transaction_id = $("#transaction_id").val();

                $.ajax({
                    method: "POST",
                    url:
                        "/dashboard/pos/update-transaction-status-cancel/" +
                        transaction_id,
                    data: {},
                    success: function (result) {
                        setTimeout(() => {
                            window.close();
                        }, 2000);
                    },
                });
            }
        }

        reset_pos_form();
    }
    return false;
}

$(document).on("click", "td.filter_product_add", function () {
    let qty_available = parseFloat($(this).data("qty_available"));
    let is_service = parseInt($(this).data("is_service"));
    let product_id = $(this).data("product_id");

    if (!is_service) {
        if (qty_available > 0) {
            get_label_product_row(product_id);
        } else {
            out_of_stock_handle(product_id);
        }
    } else {
        get_label_product_row(product_id);
    }
});

$(document).on("click", "#recent-transaction-btn", function () {
    $("#recentTransaction").modal("show");
});


$(document).on("shown.bs.modal", "#contact_details_modal", function () {
    customer_sales_table.ajax.reload();
});
$(document).on("shown.bs.modal", "#recentTransaction", function () {
    // recent_transaction_table.ajax.reload();
    get_recent_transactions();
});
$(document).on("click", "#view-lens-btn", function () {
    $("#lensTransaction").modal("show");

    $("#lens_table").DataTable({
        lengthChange: true,
        paging: true,
        info: false,
        bAutoWidth: false,
        language: {
            url: dt_lang_url,
        },
        lengthMenu: [
            [10, 25, 50, 75, 100, 200, 500, -1],
            [10, 25, 50, 75, 100, 200, 500, "All"],
        ],
        dom: "lBfrtip",
        stateSave: true,
        buttons: buttons,
        processing: true,
        serverSide: true,
        aaSorting: [[0, "desc"]],
        initComplete: function () {
            $(this.api().table().container())
                .find("input")
                .parent()
                .wrap("<form>")
                .parent()
                .attr("autocomplete", "off");
        },
        ajax: {
            url: "/dashboard/pos/get-lens-transactions",
            data: function (d) {
                d.start_date = $("#draft_start_date").val();
                d.end_date = $("#draft_end_date").val();
                d.deliveryman_id = $("#draft_deliveryman_id").val();
            },
        },
        columnDefs: [
            {
                targets: [8],
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: "transaction_date", name: "transaction_date" },
            { data: "invoice_no", name: "invoice_no" },
            { data: "final_total", name: "final_total" },
            { data: "customer_type", name: "customer_types.name" },
            { data: "customer_name", name: "customers.name" },
            { data: "mobile_number", name: "customers.mobile_number" },
            { data: "method", name: "transaction_payments.method" },
            { data: "status", name: "transactions.status" },
            { data: "action", name: "action" },
        ],
        createdRow: function (row, data, dataIndex) {},
        footerCallback: function (row, data, start, end, display) {
            var intVal = function (i) {
                return typeof i === "string"
                    ? i.replace(/[\$,]/g, "") * 1
                    : typeof i === "number"
                        ? i
                        : 0;
            };

            this.api()
                .columns(".sum", { page: "current" })
                .every(function () {
                    var column = this;
                    if (column.data().count()) {
                        var sum = column.data().reduce(function (a, b) {
                            a = intVal(a);
                            if (isNaN(a)) {
                                a = 0;
                            }

                            b = intVal(b);
                            if (isNaN(b)) {
                                b = 0;
                            }

                            return a + b;
                        });
                        $(column.footer()).html(
                            __currency_trans_from_en(sum, false)
                        );
                    }
                });
        },
    });
    $('#store_table_filter input').attr('autocomplete', 'off');
});
$(document).on("click", "#view-online-order-btn", function () {
    $("#onlineOrderTransaction").modal("show");
    $(".online-order-badge").hide();
    $(".online-order-badge").text(0);
});
$(document).ready(function () {


    $(document).on(
        "change",
        "#rt_start_date, #rt_end_date, #rt_customer_id, #rt_created_by, #rt_method, #rt_deliveryman_id",
        function () {
            // get_recent_transactions();
        }
    );
});
$(document).on('change', '.filter_transactions', function() {
    get_recent_transactions();
})
function get_recent_transactions() {
    // recent_transaction_table.ajax.reload();
     $('#recent_transaction_table').DataTable().clear().destroy();
    recent_transaction_table = $("#recent_transaction_table").DataTable({
        lengthChange: true,
        paging: true,
        info: false,
        bAutoWidth: false,
        language: {
            url: dt_lang_url,
        },
        lengthMenu: [
            [10, 25, 50, 75, 100, 200, 500, -1],
            [10, 25, 50, 75, 100, 200, 500, "All"],
        ],
        dom: "lBfrtip",
        stateSave: true,
        buttons: buttons,
        processing: true,
        serverSide: true,
        aaSorting: [[0, "desc"]],
        initComplete: function () {
            $(this.api().table().container())
                .find("input")
                .parent()
                .wrap("<form>")
                .parent()
                .attr("autocomplete", "off");
        },
        ajax: {
            url: "/dashboard/pos/get-recent-transactions",
            data: function (d) {
                d.start_date = $("#rt_start_date").val();
                d.end_date = $("#rt_end_date").val();
                d.method = $("#rt_method").val();
                d.created_by = $("#rt_created_by").val();
                d.customer_id = $("#rt_customer_id").val();
            },
        },
        columnDefs: [
            {
                targets: [13],
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: "transaction_date", name: "transaction_date" },
            { data: "invoice_no", name: "invoice_no" },
            {
                data: "received_currency_symbol",
                name: "received_currency_symbol",
                searchable: false,
            },
            { data: "final_total", name: "final_total" },
            { data: "customer_type_name", name: "customer_types.name" },
            { data: "customer_name", name: "customers.name" },
            { data: "mobile_number", name: "customers.mobile_number" },
            { data: "method", name: "transaction_payments.method" },
            { data: "ref_number", name: "transaction_payments.ref_number" },
            { data: "status", name: "transactions.status" },
            { data: "payment_status", name: "transactions.payment_status" },
            { data: "created_by", name: "users.name" },
            { data: "canceled_by", name: "canceled_by" },
            { data: "action", name: "action" },
        ],
        createdRow: function (row, data, dataIndex) {},
        footerCallback: function (row, data, start, end, display) {
            var intVal = function (i) {
                return typeof i === "string"
                    ? i.replace(/\./g, '').replace(',', '.') * 1
                    : typeof i === "number"
                    ? i
                    : 0;
            };

            this.api()
                .columns(".currencies", {
                    page: "current",
                })
                .every(function () {
                    var column = this;
                    let currencies_html = "";
                    $.each(currency_obj, function (key, value) {
                        currencies_html += `<h6 class="footer_currency" data-is_default="${value.is_default}"  data-currency_id="${value.currency_id}">${value.symbol}</h6>`;
                        $(column.footer()).html(currencies_html);
                    });
                });

            this.api()
                .columns(".sum", {
                    page: "current"
                })
                .every(function () {
                    var column = this;
                    var currency_total = [];
                    $.each(currency_obj, function (key, value) {
                        currency_total[value.currency_id] = 0;
                    });
                    column.data().each(function (group, i) {
                        b = $(group).text();
                        currency_id = $(group).data("currency_id");

                        $.each(currency_obj, function (key, value) {
                            if (currency_id == value.currency_id) {
                                currency_total[value.currency_id] += intVal(b);
                            }
                        });
                    });
                    var footer_html = "";
                    $.each(currency_obj, function (key, value) {
                        footer_html += `<h6 class="currency_total currency_total_${
                            value.currency_id
                        }" data-currency_id="${
                            value.currency_id
                        }" data-is_default="${
                            value.is_default
                        }" data-conversion_rate="${
                            value.conversion_rate
                        }" data-base_conversion="${
                            currency_total[value.currency_id] *
                            value.conversion_rate
                        }" data-orig_value="${
                            currency_total[value.currency_id]
                        }">${__currency_trans_from_en(
                            currency_total[value.currency_id],
                            false
                        )}</h6>`;
                    });
                    $(column.footer()).html(footer_html);
                });
        },
    });
}

$(document).on("change", "#customer_id", function () {
    // getPrescriptionData();
    getCustomerData();
    getCustomerBalance();
    $('#store_table_filter input').attr('autocomplete', 'off');

});
// function getPrescriptionData() {
//     let customer_id = $("#customer_id").val();
//     $.ajax({
//         method: "get",
//         url: "/dashboard/prescriptions/get-dropdown?customer_id="+customer_id,
//         data: {},
//         contactType: "html",
//         success: function (data_html) {
//             $("#prescription_id").empty().append(data_html);
//             $("#prescription_id").selectpicker("refresh");
//             old_prescription_id=  $('#old_prescription_id').val();
//
//             if(old_prescription_id){
//                 $("#prescription_id").selectpicker("val", old_prescription_id);
//             }
//         },
//     });
//
// }
function getCustomerData() {
    let customer_id = $("#customer_id").val();
    if(customer_id){
        $.ajax({
            method: "get",
            url:
                "/dashboard/customers/get-details-by-transaction-type/" +
                customer_id +
                "/sell",
            data: {},
            success: function (result) {
                $(".customer_name").text(result.name);
                $(".customer_name_span").text(result.name);
                $(".customer_address").text(result.address);
                $(".customer_address_span").text(result.address);
                $(".customer_age_span").text(result.age);
                $(".customer_gender_span").text(result.gender);
                $(".customer_type_name").text(result.customer_type);

                $(".customer_due_span").text(
                    __currency_trans_from_en(result.due, false)
                );
                $(".customer_due").text(
                    __currency_trans_from_en(result.due, false)
                );
            },
        });
    }


}


$(document).on("change", "#customer_size_id", function () {
    $("#customer_size_id_hidden").val($(this).val());
});

$(document).on("click", ".use_it_deposit_balance", function () {
    let current_deposit_balance = __read_number($("#current_deposit_balance"));
    let final_total = __read_number($("#final_total"));

    let remaining_balance = 0;
    if (current_deposit_balance > 0) {
        if (current_deposit_balance > final_total) {
            $("#used_deposit_balance").val(final_total);
            remaining_balance = current_deposit_balance - final_total;
        } else if (current_deposit_balance < final_total) {
            $("#used_deposit_balance").val(current_deposit_balance);
            remaining_balance = 0;
        }
        $(".remaining_balance_text").text(
            __currency_trans_from_en(remaining_balance, false)
        );
        $("#remaining_deposit_balance").val(remaining_balance);
    } else {
        $(".balance_error_msg").removeClass("hide");
    }

    let used_deposit_balance = __read_number($("#used_deposit_balance"));
    __write_number($("#amount"), used_deposit_balance);
    $(".received_amount").change();
});

$(document).on("click", ".add_to_deposit", function () {
    let amount = __read_number($("#amount"));
    __write_number($("#amount"), 0);
    let current_deposit_balance = __read_number($("#current_deposit_balance"));

    total_deposit = current_deposit_balance + amount;
    $(".current_deposit_balance").text(
        __currency_trans_from_en(total_deposit, false)
    );
    $("#add_to_deposit").val(amount);
    $(this).attr("disabled", true);
});

function clearOrderLens() {
    $('#orderLensFormCreate')[0].reset();

    $('#orderLensFormCreate input[type="checkbox"], #orderLensFormCreate input[type="radio"]').prop('checked', false);
    $("#price-lens").text("0.00");
    $("#total-lens").text("0.00");
    $('#orderLensFormCreate select').prop('selectedIndex', 0);
    $("#orderLensFormCreate .selectpicker").selectpicker("refresh");
    $('#orderLensFormCreate input[type="text"], #orderLensFormCreate input[type="number"]').val('');
    $('#moreInfoCollapse').removeClass('show');
    $('.color_class').addClass('d-none');
    $('.VABaseCheck_class').addClass('d-none');
    $('.specific_diameter_class').addClass('d-none');
    $('.owf-page-shapeDefinition-manual-shape').addClass('d-none');
    $('#div-price-TinTing').addClass('d-none');
    $('#div-price-Base').addClass('d-none');
    $('#div-price-Ozel').addClass('d-none');


}

    function getCustomerBalance() {

    let customer_id = $("#customer_id").val();

        // clearOrderLens();
        if(customer_id){
            $.ajax({
                method: "get",
                url: "/dashboard/pos/get-customer-balance/" + customer_id,
                data: {},
                dataType: "json",
                success: function (result) {
                    $(".customer_balance").text(
                        __currency_trans_from_en(result.balance, false)
                    );
                    $(".staff_note").text(result.staff_note);
                    $(".customer_balance").removeClass("text-red");
                    if (result.balance < 0) {
                        $(".customer_balance").addClass("text-red");
                    }
                    $(".remaining_balance_text").text(
                        __currency_trans_from_en(result.balance, false)
                    );
                    $(".balance_error_msg").addClass("hide");
                    $("#remaining_deposit_balance").val(result.balance);
                    $(".current_deposit_balance").text(
                        __currency_trans_from_en(result.balance, false)
                    );
                    $("#current_deposit_balance").val(result.balance);
                    if (result.balance < 0) {
                        $("#pay_customer_due_btn").attr("disabled", false);
                    } else {
                        $("#pay_customer_due_btn").attr("disabled", true);
                    }
                    calculate_sub_totals();
                },
            });
        }

}

$(document).on("click", ".redeem_btn", function () {
    $("#is_redeem_points").val(1);
    $(this).attr("disabled", true);
    $("#contact_details_modal").modal("hide");
    calculate_sub_totals();
});

$("#customer_id").change();

$(document).on("change", "#tax_id", function () {
    $("#tax_id_hidden").val($(this).val());
    $.ajax({
        method: "GET",
        url: "/tax/get-details/" + $(this).val(),
        data: {},
        success: function (result) {
            $("#tax_method").val(result.tax_method);
            $("#tax_rate").val(result.rate);
            $("#tax_type").val(result.type);
            calculate_sub_totals();
        },
    });
});
$(document).on("change", "#deliveryman_id", function () {
    $("#deliveryman_id_hidden").val($(this).val());
});
$(document).on("click", "#delivery_cost_btn", function () {
    $("#deliveryman_id_hidden").val($("#deliveryman_id").val());
});
var updateadd_payment_formClicked = false;
$(document).on("submit", "form#add_payment_form", function (e) {
    e.preventDefault();
    let data = $(this).serialize();
    let submitButton = $("#submit_form_button");
    if (!updateadd_payment_formClicked) {
        $.ajax({
            method: "post",
            url: $(this).attr("action"),
            data: data,
            success: function (result) {
                if (result.success) {
                     Swal.fire({
                        title: 'Success',
                        text: result.msg,
                        icon: 'success',
                    });
                } else {
                     Swal.fire({
                        title: 'Error',
                        text: result.msg,
                        icon: 'error',
                    });
                }
                $(".view_modal").modal("hide");
                get_recent_transactions();
            },
        });

        // Set the flag to true to indicate the button has been clicked
        updateadd_payment_formClicked = true;

        // Disable the button after it has been clicked
        submitButton.prop('disabled', true);
    }
});
$(document).on("submit", "form#complete_order_form", function (e) {
    e.preventDefault();
    let data = $(this).serialize();
    let submitButton = $("#complete_order_button");
    submitButton.prop('disabled', false);
        $.ajax({
            method: "post",
            url: $(this).attr("action"),
            data: data,
            success: function (result) {
                if (result.success) {
                    Swal.fire({
                        title: 'Success',
                        text: result.msg,
                        icon: 'success',
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.msg,
                        icon: 'error',
                    });
                }
                $(".view_modal").modal("hide");
                get_recent_transactions();
            },
        });
        // Disable the button after it has been clicked
    submitButton.prop('disabled', true);

});
$(document).on("click", ".print-invoice", function () {
    $(".modal").modal("hide");
    $.ajax({
        method: "get",
        url: $(this).data("href"),
        data: {},
        success: function (result) {
            if (result.success) {
                pos_print(result.html_content);
            }
        },
    });
});

function pos_print(receipt) {
    $("#receipt_section").html(receipt);
    __currency_convert_recursively($("#receipt_section"));
    __print_receipt("receipt_section");
}

$(document).on("click", ".remove_draft", function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure You Wanna Delete it?",
        icon: "warning",
    }).then((willDelete) => {
        if (willDelete) {
            var check_password = $(this).data("check_password");
            var href = $(this).data("href");
            var data = $(this).serialize();

            Swal.fire({
                title: "Please Enter Your Password.",
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Type your password",
                        type: "password",
                    },
                },
                inputAttributes: {
                    autocapitalize: "off",
                    autocorrect: "off",
                },
            }).then((result) => {
                if (result) {
                    $.ajax({
                        url: check_password,
                        method: "POST",
                        data: {
                            value: result,
                        },
                        dataType: "json",
                        success: (data) => {
                            if (data.success == true) {
                                Swal.fire({
                                    title:"Success",
                                    text:"Correct Password!",
                                    icon:"success"
                                });

                                $.ajax({
                                    method: "DELETE",
                                    url: href,
                                    dataType: "json",
                                    data: data,
                                    success: function (result) {
                                        if (result.success == true) {
                                            Swal.fire({
                                                title:"Success",
                                                text:result.msg,
                                                icon:"success"
                                            });
                                            lens_table.ajax.reload();
                                        } else {
                                             Swal.fire({
                                                title: 'Error',
                                                text: result.msg,
                                                icon: 'error',
                                            });
                                        }
                                    },
                                });
                            } else {

                                Swal.fire({
                                    title: 'Failed!',
                                    text: "Wrong Password!",
                                    icon: 'error',
                                })
                            }
                        },
                    });
                }
            });
        }
    });
});


$(document).on("click", "a.draft_cancel", function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure You Wanna Cancel it?",
        icon: "warning",
    }).then((willDelete) => {
        if (willDelete) {
            var check_password = $(this).data("check_password");
            var href = $(this).data("href");

        Swal.fire({
                title: "Please Enter Your Password.",
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Type your password",
                        type: "password",
                    },
                },
                inputAttributes: {
                    autocapitalize: "off",
                    autocorrect: "off",
                },
            }).then((result) => {
                if (result) {
                    $.ajax({
                        url: check_password,
                        method: "POST",
                        data: {
                            value: result,
                        },
                        dataType: "json",
                        success: (data) => {
                            if (data.success == true) {
                                Swal.fire({
                                    title:"Success",
                                    text:"Correct Password!",
                                    icon:"success"
                                });

                                $.ajax({
                                    method: "GET",
                                    url: href,
                                    dataType: "json",
                                    data: data,
                                    success: function (result) {
                                        if (result.success == true) {
                                            Swal.fire({
                                                title:"Success",
                                                text:result.msg,
                                                icon:"success"
                                            });

                                            lens_table.ajax.reload();
                                        } else {
                                             Swal.fire({
                                                title: 'Error',
                                                text: result.msg,
                                                icon: 'error',
                                            });
                                        }
                                    },
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "Wrong Password!",
                                    icon: 'error',
                                });
                            }
                        },
                    });
                }
            });
        }
    });
});



const buttonRight = document.getElementById("slideRight");
const buttonLeft = document.getElementById("slideLeft");

if (buttonRight !== undefined && buttonRight !== null) {
    buttonRight.onclick = function () {
        document.getElementById("scroll-horizontal").scrollLeft += 50;
    };
}

if (buttonLeft !== undefined && buttonLeft !== null) {
    buttonLeft.onclick = function () {
        document.getElementById("scroll-horizontal").scrollLeft -= 50;
    };
}

$(document).ready(function () {
    $("#weighing_scale_modal").on("shown.bs.modal", function (e) {
        //Attach the scan event
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            onScan: function (sCode, iQty) {
                $("input#weighing_scale_barcode").val(sCode);
                $("button#weighing_scale_submit").trigger("click");
            },
            onScanError: function (oDebug) {
                console.log(oDebug);
            },
            minLength: 2,
            onKeyDetect: function (iKeyCode) {
                // output all potentially relevant key events - great for debugging!
                console.log("Pressed: " + iKeyCode);
            },
        });

        $("input#weighing_scale_barcode").focus();
    });

    $("#weighing_scale_modal").on("hide.bs.modal", function (e) {
        //Detach from the document once modal is closed.
        onScan.detachFrom(document);
    });

    $("button#weighing_scale_submit").click(function () {
        if ($("#weighing_scale_barcode").val().length > 0) {
            get_label_product_row(
                null,
                null,
                1,
                0,
                $("#weighing_scale_barcode").val()
            );
            $("#weighing_scale_modal").modal("hide");
            $("input#weighing_scale_barcode").val("");
        } else {
            $("input#weighing_scale_barcode").focus();
        }
    });
});

$(document).on("keyup", function () {
    let first_tr = $("table#product_table tbody tr").first();
    let quantity = __read_number(first_tr.find(".quantity"));
    if (event.which == 38) {
        quantity = quantity + 1;
        __write_number(first_tr.find(".quantity"), quantity);
        first_tr.find(".quantity").change();
    }
    if (event.which == 40) {
        quantity = quantity - 1;
        __write_number(first_tr.find(".quantity"), quantity);
        first_tr.find(".quantity").change();
    }
});

$(document).on("click", "#non_identifiable_submit", function () {
    $("#non_identifiable_item_modal").modal("hide");

    let name = $("#nonid_name").val();
    let purchase_price = $("#nonid_purchase_price").val();
    let sell_price = $("#nonid_sell_price").val();
    let quantity = $("#nonid_quantity").val();

    if (purchase_price == "") {
        Swal.fire({
            title: 'Error',
            text: LANG.please_enter_purchase_price,
            icon: 'error',
        });
        return;
    }
    if (sell_price == "") {
        Swal.fire({
            title: 'Error',
            text: LANG.please_enter_sell_price,
            icon: 'error',
        });

        return;
    }
    if (quantity == "") {
        Swal.fire({
            title: 'Error',
            text: LANG.please_enter_quantity,
            icon: 'error',
        });
        return;
    }

    var row_count = parseInt($("#row_count").val());
    var store_id = $("#store_id").val();
    var customer_id = $("#customer_id").val();

    $("#row_count").val(row_count + 1);

    $.ajax({
        method: "get",
        url: "/dashboard/pos/get-non-identifiable-item-row",
        data: {
            name: name,
            purchase_price: purchase_price,
            sell_price: sell_price,
            quantity: quantity,
            row_count: row_count,
            store_id: store_id,
            customer_id: customer_id,
            is_unidentifable_product:1
        },
        success: function (result) {
            if (!result.success) {
                 Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
                return;
            }
            $("table#product_table tbody").prepend(result.html_content);
            $("input#search_product").val("");
            $("input#search_product").focus();
            calculate_sub_totals();

            $("#nonid_name").val("");
            $("#nonid_purchase_price").val("");
            $("#nonid_sell_price").val("");
            $("#nonid_quantity").val("");
        },
    });
});

$(document).on("click", "#submit-btn-add-products", function (e) {
    e.preventDefault();
    var sku = $("#sku").val();
    if ($("#products-form-quick-add").valid()) {
        tinyMCE.triggerSave();
        $.ajax({
            type: "POST",
            url: "/product",
            data: $("#products-form-quick-add").serialize(),
            success: function (response) {
                if (response.success) {
                     Swal.fire({
                    title: 'Success',
                    text: response.msg,
                    icon: 'success',
                })
                    $("#search_product").val(sku);
                    $("input#search_product").autocomplete("search");
                    $(".view_modal").modal("hide");
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
            },
        });
    }
});

$(document).on("change", "#sale_note_draft", function () {
    let sale_note = $(this).val();
    $("#sale_note").val(sale_note);
});
$(document).on("click", ".draft_pay", function () {
    $("#draftTransaction").modal("hide");
});
$(document).on("click", ".promotion_add", function () {
    let sale_promotion_id = $(this).data("sale_promotion_id");

    get_sale_promotion_products(sale_promotion_id);
});

function get_sale_promotion_products(sale_promotion_id) {
    $.ajax({
        method: "get",
        url: "/sales-promotion/get-sale-promotion-details/" + sale_promotion_id,
        data: {},
        success: function (result) {
            result.forEach((data, index) => {
                get_label_product_row(
                    data.product_id,
                    null,
                    data.qty,
                    index
                );
            });
        },
    });
}
$(document).on("click", "#dining_table_print, #dining_table_save", function () {
    if ($("table#product_table tbody").find(".product_row").length <= 0) {
        toastr.warning("No Product Added");
        return false;
    }
    $("#dining_action_type").val($(this).val());
    $("#amount").val(0);
    pos_form_obj.submit();
});

$(document).on("change", "#service_fee_id", function () {
    let service_fee_id = $(this).val();
    $("#service_fee_id_hidden").val(service_fee_id);
    $.ajax({
        method: "get",
        url: "/service-fee/get-details/" + service_fee_id,
        data: {},
        success: function (result) {
            $("#service_fee_rate").val(0);
            $("#service_fee_value").val(0);
            if (result.rate) {
                $("#service_fee_rate").val(result.rate);
            }
            calculate_sub_totals();
        },
    });
});

$(document).on("click", ".filter-btn", function () {
    $(this)
        .parents(".filter-btn-div")
        .siblings(".filter-btn-div")
        .find(".btn")
        .removeClass("active");
});

$(document).on("change", "#delivery_zone_id", function () {
    let delivery_zone_id = $(this).val();

    $.ajax({
        method: "get",
        url: "/delivery-zone/get-details/" + delivery_zone_id,
        data: {},
        success: function (result) {
            __write_number($("#delivery_cost"), result.cost);
            $("#deliveryman_id").val(result.deliveryman_id);
            $("#deliveryman_id").selectpicker("refresh");
            $("#deliveryman_id").change();
            calculate_sub_totals();
        },
    });
});

$(document).on("click", "#update_customer_address", function () {
    let customer_id = $("#customer_id").val();
    let address = $("#customer_address").val();

    $.ajax({
        method: "post",
        url: "/dashboard/customers//update-address/" + customer_id,
        data: { address },
        success: function (result) {
            if (result.success) {
                 Swal.fire({
                    title: 'Success',
                    text: result.msg,
                    icon: 'success',
                });
            } else {
                 Swal.fire({
                    title: 'Error',
                    text: result.msg,
                    icon: 'error',
                });
            }
        },
    });
});

$(document).on("change", "select#commissioned_employees", function () {
    let commissioned_employees = $(this).val();
    if (commissioned_employees.length > 1) {
        $(".shared_commission_div").removeClass("hide");
    } else {
        $(".shared_commission_div").addClass("hide");
    }
});

function readFileAsText(file) {
    return new Promise(function (resolve, reject) {
        let fr = new FileReader();

        fr.onload = function () {
            resolve(fr.result);
        };

        fr.onerror = function () {
            reject(fr);
        };

        fr.readAsText(file);
    });
}

$(document).on("change", "#upload_documents", function (event) {
    var files = this.files;
    var files_names = [];
    if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
            var form = new FormData();
            clone = files[i].slice(i, files[i].size, files[i].type);
            form.append("file", files[i]);

            $.ajax({
                url: "/general/upload-file-temp",
                method: "POST",
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.success) {
                        files_names.push(data.filename);
                        $("#uploaded_file_names").val(files_names.toString());
                    }
                },
            });
        }
    }
});

$(document).on("change", ".discount_category", function (e) {
    product_discount_id=$(this).val();
    product_id=$(this).parent('td').find('.p-id').val();
    $.ajax({
        method: "get",
        url: "/dashboard/pos/get-products-discount",
        data:{
            product_discount_id: product_discount_id,
            // product_id,product_id
        },
        success: function (response) {
            if(response.result){
                qty=__read_number($(this).find('.quantity'))
                $(".discount_type"+product_id).val(response.result.discount_type);
                __write_number($(".discount_value"+product_id), response.result.discount);
                $(".discount_category_name"+product_id).val(response.result.discount_category);

                __write_number($(".discount_amount"+product_id), response.result.discount*qty);

                __write_number($(".discount_amount"+product_id), response.result.discount*qty);

            }
            else{
                $(".discount_type"+product_id).val('');
                __write_number($(".discount_value"+product_id), 0);
                __write_number($(".discount_amount"+product_id), 0);
            }
            calculate_sub_totals();
        },
    });
});
