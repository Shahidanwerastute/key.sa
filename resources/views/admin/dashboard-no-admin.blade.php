@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content" style="min-height: 150px !important;">
                    <div class="uk-overflow-container" style="min-height: 100px !important;">
                        <div class="uk-grid uk-grid-width-large-1-5 uk-grid-width-medium-1-2 uk-grid-medium">
                            <h3>Welcome {{ Auth::user()->name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection