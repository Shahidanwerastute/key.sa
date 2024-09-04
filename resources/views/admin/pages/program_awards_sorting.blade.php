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
                                @foreach($content_listing as $item)
                                    <li class="uk-nestable-item sorting" id="arrayorder_{{ $item->id}}">
                                        <div class="uk-nestable-panel">
                                            <i class="material-icons">&#xE5D2;</i>
                                            <img src="{{URL::to('/').'/public/uploads/'.$item->image}}" alt="image" style="width: 25%">
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
        <a class="md-fab md-fab-accent" href="{{ custom::baseurl('/admin/page/program-rewards') }}" id="recordAdd">
            <i class="material-icons">keyboard_backspace</i>
        </a>
    </div>
    <script>
        var sorting = "{{custom::baseurl('/admin/page/program-rewards-update-sorting')}}";
    </script>
@endsection