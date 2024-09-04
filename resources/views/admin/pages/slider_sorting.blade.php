@extends('admin.layouts.template')
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <h4 class="heading_a">
                        Sorting
                        <span class="sub-heading">Sort slider banner by dragging the below bars.</span>
                    </h4>
                </div>
            </div>
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <ul class="uk-nestable" data-uk-nestable="{handleClass:'uk-nestable-handle'}">
                                @if($mobile_slider == 1)
                                    @php
                                    $height = '250';
                                    $width = '150';
                                    @endphp
                                @else
                                    @php
                                    $height = '150';
                                    $width = '200';
                                    @endphp
                                @endif
                                @foreach($sliders as $slider)
                                    <li class="uk-nestable-item sorting" id="arrayorder_{{ $slider->id}}">
                                        <div class="uk-nestable-panel">
                                            <i class="material-icons">&#xE5D2;</i>
                                            <span><strong>English Banner </strong></span>
                                            <img src="{{URL::to('/').'/public/uploads/'.$slider->image1}}" alt="Slider" height="{{$height}}" width="{{$width}}">
                                            <span>&nbsp;</span>
                                            <span>&nbsp;</span>
                                            <span>&nbsp;</span>
                                            <span>&nbsp;</span>
                                            <span><strong>Arabic Banner </strong></span>
                                            <img src="{{URL::to('/').'/public/uploads/'.$slider->image2}}" alt="Slider" height="{{$height}}" width="{{$width}}">
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-accent" href="{{ custom::baseurl('/admin/page/home-slider') }}" id="recordAdd">
            <i class="material-icons">keyboard_backspace</i>
        </a>
    </div>
    <script>
        var sorting = "{{custom::baseurl('/admin/page/sorting')}}";
    </script>
@endsection