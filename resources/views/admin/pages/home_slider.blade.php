@extends('admin.layouts.template')
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="" style="text-align: right;margin-bottom: 15px;">
                        <a class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ custom::baseurl('/admin/page/slider-sorting') }}">
                            <i class="uk-icon-sliders uk-icon-medium"></i>
                            Sorting
                        </a>
                    </div>
                    <div id="HomeSliderListTable"></div>
                </div>
            </div>
            <div class="md-card">
                <div class="md-card-content">
                    <div class="" style="text-align: right;margin-bottom: 15px;">
                        <a class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ custom::baseurl('/admin/page/slider-sorting').'?m=1' }}">
                            <i class="uk-icon-sliders uk-icon-medium"></i>
                            Sorting
                        </a>
                    </div>
                    <div id="MobileSliderListTable"></div>
                </div>
            </div>
        </div>
    </div>
@endsection