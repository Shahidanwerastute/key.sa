@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="NotificationsTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="chunk_size" value="1000">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection