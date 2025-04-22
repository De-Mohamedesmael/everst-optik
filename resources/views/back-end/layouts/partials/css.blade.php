<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<!-- Switchery css -->
<link href="{{ asset('assets/back-end/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/plugins/switchery/switchery.min.css') }}" rel="stylesheet">


<link href="{{ asset('assets/back-end/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('assets/back-end/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<!---->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Pnotify css -->
<link href="{{ asset('assets/back-end/plugins/pnotify/css/pnotify.custom.min.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('assets/back-end/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/icons.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/style.css') }}" rel="stylesheet" type="text/css">

<!-- Responsive Datatable css -->
<link href="{{ asset('assets/back-end/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/back-end/css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/back-end/css/style.default.css') }}" id="theme-stylesheet"
    type="text/css">
<link rel="stylesheet" href="{{ asset('assets/back-end/css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/back-end/js/cropperjs/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/back-end/css/style2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back-end/js/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back-end/css/crop/crop.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/back-end/js/toastr/toastr.min.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="{{ asset('assets/back-end/js/jquery-ui.css') }}">
<!-- Select2 css -->
<link href="{{ asset('assets/back-end/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">

<!-- Bootstrap CSS-->
<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-toggle/css/bootstrap-toggle.min.css') }}" type="text/css">

<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}"
    type="text/css">

<link rel="stylesheet" href="{{ asset('vendor/jquery-timepicker/jquery.timepicker.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/awesome-bootstrap-checkbox.css') }}" type="text/css">
{{--
<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap-select.min.css') }}" type="text/css">--}}
<!-- Font Awesome CSS-->
<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" type="text/css">
<!-- Drip icon font-->
<link rel="stylesheet" href="{{ asset('vendor/dripicons/webfont.css') }}" type="text/css">
<!-- Google fonts - Roboto -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700">
<!-- jQuery Circle-->
<link rel="stylesheet" href="{{ asset('css/grasp_mobile_progress_circle-1.0.0.min.css') }}" type="text/css">
<!-- Custom Scrollbar-->
<link rel="stylesheet" href="{{ asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') }}"
    type="text/css">
<!-- virtual keybord stylesheet-->
<link rel="stylesheet" href="{{ asset('vendor/keyboard/css/keyboard.css') }}" type="text/css">
<!-- date range stylesheet-->
<link rel="stylesheet" href="{{ asset('vendor/daterange/css/daterangepicker.min.css') }}" type="text/css">
<!-- table sorter stylesheet-->
<link rel="stylesheet" href="{{ asset('vendor/datatable/dataTables.bootstrap4.min.css') }}" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css"
    type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"
    type="text/css">
<link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}" id="theme-stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/cropperjs/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-treeview.css') }}">


<!-- Custom stylesheet - for your changes-->
<link rel="stylesheet" href="{{ asset('css/custom-default.css') }}" type="text/css" id="custom-style">
{{-- COLOR --}}
<style>
    .error-help-block {
        color: red;
    }

    /* input[type="text"], */
    input[type="month"],
    input[type="number"],
    textarea,
    .custom-select {
        border: 2px solid #cececf !important;
    }
</style>
<style>
    .print-only {
        display: none;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block;
        }

        .ui-pnotify-container {
            display: none !important;
        }

        @livewireScripts {
            display: none !important;
        }
    }

    .hide {
        display: none;
    }


    .wrapper1 {
        display: none;
    }

    .card-header h6 {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .table-top-head {
        top: 0;
        left: 5% !important;
        right: 5% !important;
    }

    select.form-control {

        height: 80% !important;
    }
</style>


@if(app()->getLocale() =="ar")
<style>
    a.item-list-a {
        direction: rtl;
    }

    a.d-flex.flex-row.text-start {
        width: 100%;

    }
</style>
@else
<style>
    .navbar_item::after {
        content: "";
        position: absolute;
        width: 3px;
        height: 0;
        background-color: #476762;
        top: 0;
        left: 0px;
        transition: 0.5s;
    }

    a.d-flex.flex-row.text-start {
        width: 100%;
    }

    a.d-flex.flex-row.text-start {
        width: 100%;

    }

    .form-control.hasDatepicker,
    .form-control.time_picker {
        background: #e6e6e6
    }
</style>
@endif
