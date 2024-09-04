@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a href="{{custom::baseurl('admin/corporate-sales/exportData')}}" target="_blank">
                            <button type="submit"
                                    class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                    title="Export Website / Mobile Survey Feedback To Excel">
                                Export Data
                            </button>
                        </a>
                    </div>
                </div>
                <div class="md-card-content">
                    <div id="corporateSalesResponseTable"></div>
                </div>
            </div>
        </div>
    </div>
@endsection