@extends('back-end.layouts.auth.login')

@php
    $logo = \Modules\Setting\Entities\System::getProperty('logo');
    $site_title = \Modules\Setting\Entities\System::getProperty('site_title');

@endphp
@section('title')

    {{translate('login')}}
@endsection


@section('content')
    <section class="mt-for-div">
        <div class="container ">
            <div class="screen animate__animated animate__rotateIn">
                <div class="screen__content ">
                    <form class="login" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div style="width: 125px;height:125px">

                            <img src="{{ asset('assets/back-end/system/' . $logo) }}" alt="logo" class="img-fluid">
                        </div>
                        <div class="login__field">
                            <i class="login__icon fas fa-user"></i>
                            <input name="email" id="email" type="email"
                                   class="login__input {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   placeholder="{{ translate('E-Mail Address') }}" value="{{ old('email') }}" required
                                   autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                            @endif
                        </div>
                        <div class="login__field">
                            <i class="login__icon fas fa-lock"></i>
                            <input name="password" id="password" type="password"
                                   class="login__input {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   placeholder="{{ translate('Password') }}" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                            @endif
                        </div>
                        <a href="{{ route('password.request') }}" style="font-size: 11px"
                           class=" forgot-password text-muted"><i
                                class="mdi mdi-lock me-1"></i>
                            {{ translate('forgot_password') }}</a>
                        <p class="text-center contact-us-a">
                            <a href="{{ route('admin.contact-us') }}">
                                {{ translate('contact_us') }}
                            </a>
                        </p>

                        <button type="submit" class="button login__submit">
                            <span class="button__text"> {{ translate('Login') }}</span>
                            <i class="button__icon fas fa-chevron-right"></i>
                        </button>

                    </form>
                    <div class="social-login">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            {{ translate('Remember Me') }}
                        </label>


                    </div>
                </div>
                <div class="screen__background">
                    <span class=" screen__background__shape screen__background__shape4"></span>
                    <span class="animate__animated  screen__background__shape screen__background__shape3"></span>
                    <span class="animate__animated  screen__background__shape screen__background__shape2"></span>
                    <span class="animate__animated  screen__background__shape screen__background__shape1"></span>
                </div>
            </div>
            <div class="login__footer">
                @include('back-end.layouts.partials.copyright')
            </div>
        </div>
    </section>

@endsection
