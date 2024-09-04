@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">


            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        {{--<a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" target="_blank" title="Export To Excel">
                            <i class="material-icons"></i>
                            Export
                        </a>--}}
                    </div>
                    <h3 class="heading_b md-card-toolbar-heading-text">Dropoff Charges</h3>

                </div>
                <div class="md-card-content">
                    <div id="DropoffTable"></div>
                </div>
            </div>

        </div>
    </div>

@endsection