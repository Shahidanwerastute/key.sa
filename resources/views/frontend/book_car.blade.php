@extends('frontend.layouts.template')

@section('content')
    <section class="searchNbookSec">
        <div class="container-md <?php echo custom::addClass(); ?>">
            <div class="search-main-container-new-design">
                <?php echo custom::deliveryPickupTabsArea($lang);?>
                <div class="searchBarSec">
                    <div class="serText_1">
                        @lang('labels.book')
                        <span>@lang('labels.car')</span>
                    </div>
                    @include('frontend/layouts/search_area')
                </div>
            </div>
            <div class="bookingStepsLink">
                <ul>
                    {{--<li class="active"><span>01</span> @lang('labels.booking_criteria')</li>--}}
                    <li><div><span>01</span> @lang('labels.select_a_vehicle')</div></li>
                    <li><div><span>02</span> @lang('labels.price_n_extras')</div></li>
                    <li><div><span>03</span> @lang('labels.payment')</div></li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="bookingSec">
        <div class="container-md">
        </div>
    </section>
@endsection