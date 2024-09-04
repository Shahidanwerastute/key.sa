<?php if(custom::is_mobile()){ ?>

    @include('frontend/mobile/layouts/header')
    @yield('content')
    @include('frontend/mobile/layouts/footer')

<?php }else{ ?>

    @include('frontend/layouts/header')
    {{--@include('frontend/layouts/search_area');--}}
    @yield('content')
    {{--@include('frontend/layouts/footer_social_block');--}}
    @include('frontend/layouts/footer')
    {{--if (custom::checkIfUserLoggedIn() == true)--}}

<?php } ?>

<?php
$segments = Request::segments();
if (end($segments) == 'search-results') {
    Session::forget('show_customer_popup_after_search');
    Session::save();
}
?>
