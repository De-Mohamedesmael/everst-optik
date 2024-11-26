<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/back-end/css/animate.min.css') }}" />
<!-- Switchery css -->
<link href="{{ asset('assets/back-end/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/plugins/switchery/switchery.min.css') }}" rel="stylesheet">

<!-- DataTables css -->
<link href="{{ asset('assets/back-end/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/back-end/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!---->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Pnotify css -->
<link href="{{ asset('assets/back-end/plugins/pnotify/css/pnotify.custom.min.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('assets/back-end/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/icons.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/flag-icon.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/back-end/css/style.css') }}" rel="stylesheet" type="text/css">

<!-- Responsive Datatable css -->
<link href="{{ asset('assets/back-end/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/back-end/css/style.default.css') }}" id="theme-stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Raleway, sans-serif;
    }
    .screen {
        background: linear-gradient(90deg, #8acac0, #3e5d58);
        position: relative;
        height: 600px;
        width: 30%;
        box-shadow: 0px 0px 24px #3e5d58;
    }
    body {
        background: linear-gradient(90deg, #8acac0, #3e5d58);
        height: 90vh;
        overflow: hidden;
        /*padding-top:150px ;*/
    }
    .mt-for-div{
        /*margin-top:150px ;*/
    }
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100% !important;
    }

    @media (min-width: 576px) {
        .container {
            max-width: 100% !important;
        }
    }

    @media (min-width: 768px) {
        .container {
            max-width: 100% !important;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 100% !important;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 100% !important;
        }
    }



    .screen__content {
        z-index: 1;
        position: relative;
        height: 100%;
    }

    .screen__background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        -webkit-clip-path: inset(0 0 0 0);
        clip-path: inset(0 0 0 0);
    }

    .screen__background__shape {
        transform: rotate(45deg);
        position: absolute;
    }

    .screen__background__shape1 {
        height: 520px;
        width: 520px;
        background: #FFF;
        top: -50px;
        right: 120px;
        border-radius: 0 72px 0 0;
    }

    .screen__background__shape2 {
        height: 220px;
        width: 220px;
        background: #3e5d58;
        top: -172px;
        right: 0;
        border-radius: 32px;
    }

    .screen__background__shape3 {
        height: 540px;
        width: 190px;
    background: linear-gradient(270deg, #8acac0, #3e5d58);
        top: -24px;
        right: 0;
        border-radius: 32px;
    }

    .screen__background__shape4 {
        height: 400px;
        width: 200px;
        background: #557c73;
        top: 420px;
        right: 50px;
        border-radius: 60px;
    }

    .login {
        width: 80%;
        padding: 30px;
        /* padding-top: 80px; */
    }

    .login__field {
        padding: 15px 0px;
        position: relative;
    }

    .login__icon {
        position: absolute;
        top: 30px;
        color: #3e5d58;
    }

    .login__input {
        border: none;
        border-bottom: 2px solid #D1D1D4;
        background: none;
        padding: 10px;
        padding-left: 24px;
        font-weight: 700;
        width: 90%;
        transition: .2s;
    }

    .login__input:active,
    .login__input:focus,
    .login__input:hover {
        outline: none;
        border-bottom-color: #3e5d58;
    }

    .login__submit {
        background: #fff;
        font-size: 14px;
        margin-top: 15px;
        padding: 16px 20px;
        border-radius: 26px;
        border: 1px solid #D4D3E8;
        text-transform: uppercase;
        font-weight: 700;
        display: flex;
        align-items: center;
        width: 100%;
        color: #27ba93;
        box-shadow: 0px 2px 2px #42a883;
        cursor: pointer;
        transition: .2s;
    }

    .login__submit:active,
    .login__submit:focus,
    .login__submit:hover {
        border-color: #3e5d58;
        outline: none;
    }

    .button__icon {
        font-size: 24px;
        margin-left: auto;
        color: #3e5d58;
    }

    .social-login {
        position: absolute;
        height: 140px;
        width: 160px;
        text-align: center;
        bottom: 0px;
        right: 0px;
        color: #fff;
    }

    .social-icons {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .social-login__icon {
        padding: 20px 10px;
        color: #fff;
        text-decoration: none;
        text-shadow: 0px 0px 8px #3e5d58;
    }

    .social-login__icon:hover {
        transform: scale(1.5);
    }

    .login__footer {
        position: absolute;
        bottom: 0;
        height: 35px;
        left: 0;
        right: 0;
    }
    p.text-center.contact-us-a a {
        color: #4dbb6f;
    }
    a.forgot-password.text-muted {
        color: #dc3545 !important;
        font-size: 13px !important;
        font-style: initial;
    }
</style>
