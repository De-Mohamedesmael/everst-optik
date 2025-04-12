<script src="{{ asset('assets/back-end/js/jquery.min.js') }}"></script>

@php
$moment_time_format = \Modules\Setting\Entities\System::getProperty('time_format') == '12' ? 'hh:mm A' : 'HH:mm';
@endphp
<script>
    var moment_time_format = "{{ $moment_time_format }}";
</script>
<script type="text/javascript" src="{{ asset('js/lang/' . session('language') . '.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('vendor/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/popper.js/umd/popper.min.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>--}}
{{--<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('vendor/daterange/js/moment.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}">
</script>
{{-- <script type="text/javascript"
    src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.' . session('language') . '.min.js') }}">
</script>--}}
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}">
</script>

<script type="text/javascript" src="{{ asset('vendor/bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap-select.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('vendor/keyboard/js/jquery.keyboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/keyboard/js/jquery.keyboard.extension-autocomplete.js') }}">
</script>
<script type="text/javascript" src="{{ asset('js/grasp_mobile_progress_circle-1.0.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery.cookie/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/charts-custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/front.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterange/js/knockout-3.4.2.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterange/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-treeview.js') }}"></script>

<!-- table sorter js-->
<script type="text/javascript" src="{{ asset('vendor/datatable/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/buttons.bootstrap4.min.js') }}">
</script>
<script type="text/javascript" src="{{ asset('vendor/datatable/buttons.colVis.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/buttons.print.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('vendor/datatable/sum().js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/dataTables.checkboxes.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/date-eu.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/accounting.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js">
</script>
<script type="text/javascript" src="{{ asset('vendor/cropperjs/cropper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/printThis.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/currency_exchange.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/customer.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cropper.js') }}"></script>
<script>
    // Show the button when the user scrolls down 20px from the top
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        var button = document.getElementById("toTopBtn");
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            button.style.display = "block";
        } else {
            button.style.display = "none";
        }
    }

    // Scroll to the top when the button is clicked
    function scrollToTop() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
    }
</script>
<script>
    function toggleAccordion(sectionId) {
        const section = document.getElementById(sectionId);
        let arrow = document.querySelector(`.${sectionId}`);

        if (section.style.display === "block") {
            section.style.display = "none";

            // Check if the element exists
            if (arrow) {
                // Remove all children from the "wrap" element
                while (arrow.firstChild) {
                    arrow.removeChild(arrow.firstChild);
                }

                // Create a new <i> element with the desired attributes
                let newIElement = document.createElement('i');
                newIElement.className = 'fas fa-arrow-down';
                newIElement.style.fontSize = '0.8rem';

                // Append the new <i> element to the "wrap" element
                arrow.appendChild(newIElement);
            }
        } else {
            section.style.display = "block";

            // Check if the element exists
            if (arrow) {
                // Remove all children from the "wrap" element
                while (arrow.firstChild) {
                    arrow.removeChild(arrow.firstChild);
                }

                // Create a new <i> element with the desired attributes
                let newIElement = document.createElement('i');
                newIElement.className = 'fas fa-arrow-up';
                newIElement.style.fontSize = '0.8rem';

                // Append the new <i> element to the "wrap" element
                arrow.appendChild(newIElement);
            }
        }
    }

    function toggleProductAccordion(sectionId) {
        const section = document.getElementById(sectionId);
        let arrow = document.querySelector(`.${sectionId}`);

        if (section.style.display === "block") {
            section.style.display = "none";

            // Check if the element exists
            if (arrow) {
                // Remove all children from the "wrap" element
                while (arrow.firstChild) {
                    arrow.removeChild(arrow.firstChild);
                }

                // Create a new <i> element with the desired attributes
                let newIElement = document.createElement('i');
                newIElement.className = 'fas fa-arrow-left d-flex justify-content-center align-items-center';


                newIElement.style.fontSize = '0.8rem';
                newIElement.style.color = 'black';
                newIElement.style.backgroundColor = 'white';
                newIElement.style.width = '20px';
                newIElement.style.height = '20px';
                newIElement.style.borderRadius = '50%';

                // Append the new <i> element to the "wrap" element
                arrow.appendChild(newIElement);
            }
        } else {
            section.style.display = "block";

            // Check if the element exists
            if (arrow) {
                // Remove all children from the "wrap" element
                while (arrow.firstChild) {
                    arrow.removeChild(arrow.firstChild);
                }

                // Create a new <i> element with the desired attributes
                let newIElement = document.createElement('i');
                newIElement.className = 'fas fa-arrow-right d-flex justify-content-center align-items-center';
                newIElement.style.fontSize = '0.8rem';
                newIElement.style.color = 'black';
                newIElement.style.backgroundColor = 'white';
                newIElement.style.width = '20px';
                newIElement.style.height = '20px';
                newIElement.style.borderRadius = '50%';


                // Append the new <i> element to the "wrap" element
                arrow.appendChild(newIElement);
            }
        }
    }

    function toggleFillAccordion(sectionId, sectionArrowId) {
        let sections = document.querySelectorAll(`.${sectionId}`);
        let arrows = document.querySelectorAll(`.${sectionArrowId}`);

        sections.forEach(section => {
            if (section.style.display === "block") {
                section.style.display = "none";


                // Check if the element exists

                arrows.forEach(arrow => {
                    // Remove all children from the "wrap" element
                    while (arrow.firstChild) {
                        arrow.removeChild(arrow.firstChild);
                    }

                    // Create a new <i> element with the desired attributes
                    let newIElement = document.createElement('i');
                    newIElement.className =
                        'fas fa-arrow-down d-flex justify-content-center align-items-center';
                    newIElement.style.fontSize = '0.8rem';
                    newIElement.style.color = 'white';
                    newIElement.style.width = '20px';
                    newIElement.style.height = '20px';
                    newIElement.style.borderRadius = '50%';

                    // Append the new <i> element to the "wrap" element
                    arrow.appendChild(newIElement);

                })

            } else {
                section.style.display = "block";

                // Check if the element exists

                arrows.forEach(arrow => {
                    // Remove all children from the "wrap" element
                    while (arrow.firstChild) {
                        arrow.removeChild(arrow.firstChild);
                    }

                    // Create a new <i> element with the desired attributes
                    let newIElement = document.createElement('i');
                    newIElement.className =
                        'fas fa-arrow-up d-flex justify-content-center align-items-center';
                    newIElement.style.fontSize = '0.8rem';
                    newIElement.style.color = 'white';
                    newIElement.style.width = '20px';
                    newIElement.style.height = '20px';
                    newIElement.style.borderRadius = '50%';


                    // Append the new <i> element to the "wrap" element
                    arrow.appendChild(newIElement);
                })


            }
        });

    }
</script>
<script>
    // Select both elements by their class names
    var div1 = document.querySelector('.div1');
    var div2 = document.querySelector('.div2');
    if (div2) {
        // Get the width of the "div2" element
        var div2Width = div2.offsetWidth;

        // Set the width of "div1" to the width of "div2"
        div1.style.width = div2Width + 'px';

        document.addEventListener("DOMContentLoaded", function () {
            var wrapper1 = document.querySelector(".wrapper1");
            var wrapper2 = document.querySelector(".wrapper2");

            wrapper1.addEventListener("scroll", function () {
                wrapper2.scrollLeft = wrapper1.scrollLeft;
            });

            wrapper2.addEventListener("scroll", function () {
                wrapper1.scrollLeft = wrapper2.scrollLeft;
            });
        });
    }

</script>
<script>
    document.addEventListener('componentRefreshed', function () {
        const value = sessionStorage.getItem("showHideDollar");

        if (value === "show") {
            var dollarCells = document.getElementsByClassName('dollar-cell');

            for (var i = 0; i < dollarCells.length; i++) {

                dollarCells[i].classList.remove('showHideDollarCells')
            }
        }

    });
</script>
<script type="text/javascript">
    base_path = "{{ url('/') }}";
    current_url = "{{ url()->current() }}";
</script>

<script src="{{ asset('assets/back-end/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/detect.js') }}"></script>
<script src="{{ asset('assets/back-end/js/horizontal-menu.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/back-end/plugins/datatables/1.13.6/api/sum().js') }}"></script>
{{--<script src="{{ asset('assets/back-end/js/custom/custom-table-datatable.js') }}"></script>--}}


<script type="text/javascript" src="{{ asset('assets/back-end/js/jquery-validation/jquery.validate.min.js') }}">
</script>
<script type="text/javascript"
    src="{{ asset('assets/back-end/js/jquery-validation/localization/messages_' . app()->getLocale() . '.js') }}">
</script>
<script type="text/javascript" src="{{ asset('assets/back-end/js/cropperjs/cropper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/back-end/js/dropzone.js') }}"></script>

{{-- select library --}}

<script type="text/javascript" src="{{ asset('assets/back-end/js/select/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/back-end/js/select/form-select2.js') }}"></script>
<script src="{{ asset('assets/back-end/js/summernote.min.js') }}" referrerpolicy="origin"></script>
<script type="text/javascript" src="{{ asset('assets/back-end/js/submitform/submit-form.js') }}"></script>
<script src="{{ asset('assets/back-end/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/lang/' . app()->getLocale() . '.js') }}"></script>
<script></script>


<!-- Pnotify js -->
<script src="{{ asset('assets/back-end/plugins/pnotify/js/pnotify.custom.min.js') }}"></script>
<script src="{{ asset('assets/back-end/js/custom/custom-pnotify.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="{{ asset('assets/back-end/plugins/switchery/switchery.min.js') }}"></script>
<!-- Core js -->
{{-- <script src="{{asset('js/core.js')}}"></script> --}}
<script>
    $(document).on("click", "#discount_from_original_price", function () {
        var value = $('#discount_from_original_price').is(':checked') ? 1 : 0;
        $.ajax({
            method: "get",
            url: "/create-or-update-system-property/discount_from_original_price/" + value,
            contentType: "html",
            success: function (result) {
                if (result.success) {
                    // Swal.fire("Success", response.msg, "success");
                    Swal.fire({
                        title: "Success",
                        text: response.status,
                        icon: "success",
                        timer: 1000, // Set the timer to 1000 milliseconds (1 second)
                        showConfirmButton: false // This will hide the "OK" button
                    });

                }
            },
        });
    });

    $(document).on("click", "#clear_all_input_form", function () {
        var value = $('#clear_all_input_form').is(':checked') ? 1 : 0;
        $.ajax({
            method: "get",
            url: "/create-or-update-system-property/clear_all_input_stock_form/" + value,
            contentType: "html",
            success: function (result) {
                if (result.success) {
                    Swal.fire("Success", result.msg, "success");
                }
            },
        });
    });

    document.addEventListener('livewire:load', function () {
        window.addEventListener('initialize-select2', event => {
            $('.select2').select2();
            $('.js-example-basic-multiple').select2({
                placeholder: LANG.please_select,
                tags: true
            });

        });
    });

    @if (session('status'))
    new PNotify({
        title: '{{ session('status.msg') }} !',
        text: '{{ session('status.msg') }}',
        delay: 700,
        @if (session('status.success') == '1')
        type: "success"
        @else
        type: "Error"
        @endif
    });
    @endif

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.delete_item', function (e) {
        e.preventDefault();
        var deletetype = $(this).data('deletetype');
        var title = "{{translate('are_you_sure')}}";
        if (deletetype === 1) {
            title = "{{translate('it_will_delete_the_product_and_all_its_operations')}}"
        }
        Swal.fire({
            title: title,
            text: "{{translate('are_you_sure_you_wanna_delete_it')}}",
            icon: 'warning',
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                Swal.fire({
                    title: "{!! __('lang.please_enter_your_password') !!}",
                    input: 'password',
                    inputAttributes: {
                        placeholder: "{!! __('lang.type_your_password') !!}",
                        autocomplete: 'off',
                        autofocus: true,
                    },
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            url: '{{ route('admin.check-password',auth()->guard('admin')->id()) }}',
                            method: 'POST',
                            data: {
                                value: result,
                            },
                            dataType: 'json',
                            success: (data) => {

                                if (data.success == true) {
                                    Swal.fire(
                                        'success',
                                        "{!! __('lang.correct_password') !!}",
                                        'success'
                                    );
                                    // location.reload();
                                    $.ajax({
                                        method: 'DELETE',
                                        url: href,
                                        dataType: 'json',
                                        data: data,
                                        success: function (result) {
                                            if (result.success ===
                                                true) {
                                                new PNotify({
                                                    title: result
                                                        .msg,
                                                    text: 'Check me out! I\'m a notice.',
                                                    type: 'success'
                                                });
                                                setTimeout(() => {
                                                    location
                                                        .reload();
                                                }, 1500);
                                                location.reload();
                                            } else {
                                                new PNotify({
                                                    title: result
                                                        .msg,
                                                    text: 'Check me out! I\'m a notice.',
                                                    type: 'error'
                                                });
                                            }
                                        },
                                    });

                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        'Wrong Password!',
                                        'error'
                                    )

                                }
                            }
                        });
                    }
                });
            }
        });
    });

    //open edit modal for modules
    $(document).on('click', '.btn-modal', function (e) {
        e.preventDefault();

        var container = $(this).data('container');
        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function (result) {
                console.log('ddd');
                $(container).html(result);
                $('#editModal').modal('show');
                $('.select2').select2();
                $('.datepicker').datepicker({

                });
            },
        });
    });

    //make translation open if there is translation when edit
    $(document).ready(function () {
        $('table.editTogle')
            .find("tr")
            .each(function () {
                if ($(this).find('input.translations').val()) {
                    $('table.editTogle').removeClass('collapse')
                    return;
                }
            });
        $('table.editTextareaTogle')
            .find("tr")
            .each(function () {
                if ($(this).find('textarea.translations').val()) {
                    $('table.editTextareaTogle').removeClass('collapse')
                    return;
                }
            });
    });


    $('.select2').select2();
    $('.datepicker').datepicker({

    });


</script>
<script>
    const menuButton = document.getElementById('menu-button');
    const menu = document.getElementById('menu');

    menuButton.addEventListener('click', function () {
        if (menu.style.display === 'block') {
            menu.style.display = 'none'
        } else {
            menu.style.display = 'block'
        }
    })


    $('.click-item-route').on('click', function (e) {
        e.preventDefault();
        let url = this.data('url');
        document.body.classList.add('animated-element');
        // window.location.href = url;
        window.open(url, "_blank")
    })

</script>
<script>
    $(document).on("click", ".buttons-collection", function () {
        if (!$(this).hasClass('toggleVisColList')) {
            $(this).addClass('toggleVisColList')
        }

    })
    $(document).on("click", ".toggleVisColList", function () {
        $('.dt-button-collection').toggle()
    })
</script>


@stack('javascripts')