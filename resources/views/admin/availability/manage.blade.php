@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a href="{{custom::baseurl('admin/availability/export')}}" target="_blank"><button type="button" class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" title="Export Data To Excel"> Export </button></a>
                    </div>
                </div>
                <div class="md-card-content">
                    <div id="availabilitySetupTable"></div>
                </div>
            </div>
        </div>
    </div>
@endsection