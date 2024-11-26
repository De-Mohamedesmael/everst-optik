<style>
    .unread {
        color: black !important;
        font-weight: bold !important;
        cursor: pointer;
    }

    .read {
        opacity: 0.8 !important;
        cursor: pointer;
    }

    ul.dropdown-menu {
        /* height: 205px; */
        overflow-y: auto;
        /* min-width: 360px; */
        overflow-x: hidden;
        padding: 10px !;
        text-align: left !important;
    }

    ul.dropdown-menu li {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .no_new_notification_div {
        padding-top: 27px !important;
        padding-bottom: 27px !important;
    }

    .notification-box {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px 5px;
        border-radius: 5px;
    }
</style>
@php
    // $new_notifications = App\Models\Notification::where('id', Auth::user()->id)->whereDate('created_at',
    // date('Y-m-d'))->orderBy('created_at', 'desc')->with(['created_by_user', 'products', 'transaction'])->limit(2)->get();
    // $new_count = $new_notifications->where('is_seen', 0)->count();
    // $earlier_notifications = App\Models\Notification::where('id', Auth::user()->id)->whereDate('created_at', '<', date('Y-m-d'))->orderBy('created_at', 'desc')->with(['created_by_user', 'products', 'transaction'])->limit(2)->get();
@endphp
{{-- +++++++++++++++++ New (Unread) Notification +++++++++++++++++ --}}
<ul class="m-0 pr-2 pl-0 mt-1">
    <li class="nav-item dropdown">
        <a class="nav-link text-light  notification-list position-relative" href="#" id="navbarDropdown"
            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="badge text-bg-danger position-absolute p-0"
                style="width: 21px;height: 13px;right: 4px;top: -7px;z-index: 0;">{{-- auth()->user()->unreadNotifications->count() --}}9</span>

            <i class="fa fa-bell" style="color:rgb(101,110,249);font-size:18px"></i>
            {{-- ///////// Unread Notification Count ///////// --}}
{{--            @if (auth()->user()->unreadNotifications->count() > 0)--}}

{{--            @endif--}}
        </a>
        {{-- ///////// Unread Notifications ///////// --}}
        <ul class="dropdown-menu">
                <li class="head text-light bg-dark">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-12 text-center">

                            <a href="#" class="text-light text-decoration-none">Mark all as
                                read</a>
                        </div>
                    </div>
                </li>

                <div class="text-center no_new_notification_div">
                    <span class="text-muted" style="font-size: 12px;">@lang('lang.no_new_notification')</span>
                </div>

        </ul>
    </li>
</ul>
