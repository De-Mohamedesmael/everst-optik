var key;
$(".btn-add-modal").click(function (e) {
    key = $(this).data('key');
    console.log(key);
});



//unit form

//store form
var store_key = null;
var store_index = null;
$("#create-store-btn").click(function (e) {
    e.preventDefault();
    setTimeout(() => {
        $("#add_store").submit();
        $("#quick_add_store_form").submit();
    }, 500)
});

$(document).on("click", ".createStoreModal", function (e) {
    store_key = $(this).data('key');
    store_index = $(this).data('index');
    console.log('store_key' + store_key);
    console.log('store_index' + store_index);
});


// ++++++++++++++++++ ajax : products_ module : quick_add_store ++++++++++++++++++
$(document).on("submit", "form#quick_add_store_form", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                console.log("First Ajax Request : Store new store");
                console.log(result);
                console.log(result.store_id);
                $(".add-store").modal("hide");
                $.ajax({
                    method: "get",
                    url: "/dashboard/products/get-dropdown-store/",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        console.log("Second Ajax Request : Get dropdown of stores");
                        console.log(data_html);
                        $("#store_id").empty().append(data_html[0]);
                        $("#store_id").val(data_html[1]).change();
                        if (store_key != null) {
                            $(".store_id" + store_index + store_key).empty().append(data_html[0]);
                            $(".store_id" + store_index + store_key).val(data_html[1]).change();
                        } else {
                            $(".store_id" + store_index).empty().append(data_html[0]);
                            $(".store_id" + store_index).val(data_html[1]).change();
                        }

                    },
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});



//store form

// ++++++++++++ category form ++++++++++++
var select_category = 0;
var main_category_id = 0;
// ++++++++++++ .openCategoryModal ++++++++++++
$(".openCategoryModal").click(function (e) {
    console.log("+++++++++++++++++++ You Click on + button +++++++++++++++++++")
    select_category = $(this).data('select_category');
    if (select_category == "0") {
        main_category_id = 1;
    }
    else if (select_category == "1") {
        main_category_id = 2;
    }
    else if (select_category == "2") {
        main_category_id = 3;
    }
    else if (select_category == "3") {
        main_category_id = 4;
    }
    console.log("select_category = " + select_category, "main_category_id = " + main_category_id)

    // if((main_category_id!=='' && select_category!=0) || main_category_id===0){
    $(this).addClass('btn-modal');
    // }else{
    //     Swal.fire("warning", LANG.no_parent_category, "warning");
    // }
});
// ++++++++++++ #create-category-btn ++++++++++++
$("#create-category-btn").click(function (e) {
    console.log('++++++++++++++ After Click "save" on Modal ++++++++++++++++++++');
    e.preventDefault();
    setTimeout(() => {
        $("#create-category-form").submit();
        console.log('++++++++++++++ After Submit Form ++++++++++++++++++++');
    }, 500)
});
// ++++++++++++ #create-category-form ++++++++++++
$(document).on("submit", "#create-category-form", function (e) {
    console.log("++++++++++++ On Submit ++++++++++++");
    e.preventDefault();
    var dataArray = $(this).serializeArray();
    var data = {};
    var name = $('.category-name').val();
    // Convert the serialized array into an object
    $.each(dataArray, function (index, field) {
        data[field.name] = field.value;
    });
    console.log(select_category)
    console.log(data);
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: {
            data: data,
            parent_id: main_category_id,
            name: name
        },
        success: function (result) {
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                $("#createCategoryModal").modal("hide");
                $(".createSubCategoryModal").modal("hide");
                console.log(main_category_id);
                var category_id = result.id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/categories/get-dropdown/" + main_category_id,
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        if (typeof key !== 'undefined' && key !== null) {
                            if (select_category == "0") {
                                $("#categoryId" + key).empty().append(data_html);
                                $(".category").empty().append(data_html);
                                $("#categoryId" + key).val(category_id).change();
                            } else if (select_category == "2") {
                                console.log(data_html);

                                $("#subCategoryId2" + key).empty().append(data_html);
                                $(".subcategory2").empty().append(data_html);
                                $("#subCategoryId2" + key).val(category_id).change();
                                // $("#subCategoryId2").val(category_id).trigger();
                            } else if (select_category == "3") {
                                $("#subCategoryId3" + key).empty().append(data_html);
                                $(".subcategory3").empty().append(data_html);
                                $("#subCategoryId3" + key).val(category_id).change();
                                // $("#subCategoryId3").val(category_id).trigger();
                            }
                            else if (select_category == "1") {
                                $("#subcategory_id1" + key).empty().append(data_html);
                                $(".subcategory").empty().append(data_html);
                                $("#subcategory_id1" + key).val(category_id).change();

                                // $("#subcategory_id1").val(category_id).trigger();
                            }
                        }
                        else {
                            if (select_category == "0") {
                                $("#categoryId").empty().append(data_html);
                                $("#categoryId").val(category_id).change();
                            }
                            else if (select_category == "1") {
                                $("#subcategory_id1").empty().append(data_html);
                                $("#subcategory_id1").val(category_id).change();

                                // $("#subcategory_id1").val(category_id).trigger();
                            }
                            else if (select_category == "2") {
                                $("#subCategoryId2").empty().append(data_html);
                                $("#subCategoryId2").val(category_id).change();
                                // $("#subCategoryId2").val(category_id).trigger();
                            }
                            else if (select_category == "3") {
                                $("#subCategoryId3").empty().append(data_html);
                                $("#subCategoryId3").val(category_id).change();
                                // $("#subCategoryId3").val(category_id).trigger();
                            }

                        }

                    }
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});
//
$(document).ready(function () {
    $("#create-products-tax-btn").click(function (e) {
        e.preventDefault();
        setTimeout(() => {
            $("#quick_add_product_tax_form").submit();
        }, 500);
    });
});

$(document).on("submit", "#quick_add_product_tax_form", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                $("#add_product_tax_modal").modal("hide");
                var product_tax_id = result.id;
                $.ajax({
                    method: "get",
                    url: "/dashboard/product-taxes/get-dropdown",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        console.log(data_html)
                        if (typeof key !== 'undefined' && key !== null) {
                            $("#product_tax" + key).empty().append(data_html);
                            $("#product_tax" + key).val(product_tax_id).change();
                        }
                        else {
                            $("#product_tax").empty().append(data_html);
                            $("#product_tax").val(product_tax_id).change();
                        }

                    },
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});
$(document).ready(function () {
    $("#create-customer-btn").click(function (e) {
        e.preventDefault();
        setTimeout(() => {
            $("#quick_add_customer_form").submit();
        }, 500);
    });
});
// ++++++++++++++++++++++++ Customer Type Form +++++++++++++++++++++
// $(document).on("submit", "#quick_add_customer_form", function (e) {
//     e.preventDefault();
//     var data = $(this).serialize();
//     $.ajax({
//         method: "post",
//         url: $(this).attr("action"),
//         dataType: "json",
//         data: data,
//         success: function (result) {
//             if (result.success) {
//                 Swal.fire("Success", result.msg, "success");
//                 $("#add_customer").modal("hide");
//                 var customer_id = result.id;
//                 $.ajax({
//                     method: "get",
//                     url: "/dashboard/customers/get-dropdown",
//                     data: {},
//                     contactType: "html",
//                     success: function (data_html) {
//                         $("#client_id").empty().append(data_html[0]);
//
//                         $("#client_id").val(data_html[1]).change();
//                     },
//                 });
//             } else {
//                 Swal.fire("Error", result.msg, "error");
//             }
//         },
//     });
// });
// +++++++++++++++++++++++ customer_cities_Dropdown ++++++++++++++++++++
$(document).on("submit", "#customer-region-form", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            console.log("First Ajax Request : ", result);
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                $("#createRegionModal").modal("hide");
                var city_id = result.id;
                var state_id = result.state_id;
                console.log("Outer Second Ajax Request : ", result);
                $.ajax({
                    method: "get",
                    url: "/dashboard/customers/get-dropdown-city/" + state_id,
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        console.log("Inner Second Ajax Request : " + data_html);
                        console.log(city_id);
                        // Update the dropdown with the new options
                        $("#city-dd").empty().append(data_html);
                        // Set the selected value in the dropdown
                        $("#city-dd").val(city_id).change(); // Set the newly added city as selected
                    },
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});
// +++++++++++++++++++++++ customer_quarters_Dropdown ++++++++++++++++++++
$(document).on("submit", "#customer-quarter-form", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            console.log("First Ajax Request : ", result);
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                $("#createQuarterModal").modal("hide");
                var quarter_id = result.id;
                var city_id = result.city_id;
                console.log("Outer Second Ajax Request : ", result);
                $.ajax({
                    method: "get",
                    url: "/dashboard/customers/get-dropdown-quarter/" + city_id,
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        console.log("Inner Second Ajax Request : " + data_html);
                        console.log("city_id = " + city_id);
                        console.log("quarter_id = " + quarter_id);
                        console.log("data_html = " + data_html);
                        $("#quarter-dd").empty().append(data_html);
                        $("#quarter-dd").val(quarter_id).change();
                    },
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});
// +++++++++++++++++++++++ customer_quarters_Dropdown ++++++++++++++++++++
$(document).on("submit", "#customer-type-form2", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: "post",
        url: $(this).attr("action"),
        dataType: "json",
        data: data,
        success: function (result) {
            console.log("First Ajax Request : ", result);
            if (result.success) {
                Swal.fire("Success", result.msg, "success");
                $("#createCustomerTypesModal2").modal("hide");
                var customer_type_id = result.customer_type_id;
                console.log("Outer Second Ajax Request : ", result);
                $.ajax({
                    method: "get",
                    url: "/dashboard/customers/get-dropdown-customer-type/",
                    data: {},
                    contactType: "html",
                    success: function (data_html) {
                        // console.log("data_html = ", data_html);
                        console.log("Inner Second Ajax Request : " + data_html);
                        console.log("data_html = " + data_html);
                        $("#customer_type_id").empty().append(data_html);
                        $("#customer_type_id").val(customer_type_id).change();
                    },
                });
            } else {
                Swal.fire("Error", result.msg, "error");
            }
        },
    });
});

//category form
