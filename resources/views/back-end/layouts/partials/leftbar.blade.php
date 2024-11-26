<!-- Start Leftbar -->
<div class="leftbar" style="z-index: 9999">

    <!-- Start Profilebar -->
    <div class="profilebar text-center">
        <div class="profilename">

        </div>
        <div class="userbox">
            <a href="{{ route('logout') }}" class="profile-icon"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img src="{{ asset('assets/back-end/images/svg-icon/logout.svg') }}" class="img-fluid log-out-button" alt="logout">
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <div class="profilename" style="padding-top: 20px">
            <a href="https://api.whatsapp.com/send?phone={{-- $settings['watsapp_numbers'] --}}">
                <img src="{{ asset('assets/back-end/images/topbar/whatsapp.jpg') }}" class="img-fluid" alt="notifications" width="45px"
                    height="45px">
            </a>
        </div>
        @can('sale.pos.create_and_edit')
            <div class="profilename">
                <a href="{{ route('admin.pos.create') }}">
                    <img src="{{ asset('assets/back-end/images/topbar/cash-machine.png') }}" class="img-fluid" alt="notifications"
                        width="45px" height="45px">
                </a>
            </div>
            @php
            $cash_register = \Modules\CashRegister\Entities\CashRegister::where('admin_id', auth('admin')->user()->id)
            ->where('status', 'open')
            ->first();
            @endphp
            <div class="profilename  {{empty($cash_register) ? 'd-none' : ''}} ">
                <a>
                    <span style="background-color: transparent;width:45px;height:45px;cursor: pointer;" id="power_off_btn">
                        <img src="{{ asset('assets/back-end/images/power-button.svg') }}" class="img-fluid w-100 h-100" alt="power-off">
                    </span>
                </a>
            </div>
        @endcan

        {{-- +++++++++++++++++ Notification +++++++++++++++++ --}}
        @include('back-end.layouts.partials.notification_list')
        @php
            $config_languages = config('constants.langs');
            $languages = [];
            foreach ($config_languages as $key => $value) {
            $languages[$key] = $value['full_name'];
            }
        @endphp
        <div class="languagebar">
            <div class="dropdown">
                <a class="dropdown-toggle text-black text-decoration-none" href="#" role="button" id="languagelink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="dripicons-web"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languagelink">
                    @foreach ($languages as $key => $lang)

                            <a  class="dropdown-item" rel="alternate" href="{{ route('general.switch-language', $key) }}">
                                {{ $lang }}</a>

                    @endforeach
                </div>
            </div>
        </div>
        <div class="languagebar">

        </div>
    </div>



    <!-- End Profilebar -->

</div>
<!-- End Leftbar -->

