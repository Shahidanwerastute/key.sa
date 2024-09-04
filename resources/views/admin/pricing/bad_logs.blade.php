@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/pricing'); ?>" title="Click To Go Back To Pricing Page">
                            Go Back
                        </a>
                    </div>

                </div>
                <div class="md-card-content">
                    <div id="BadLogsTable"></div>
                </div>
            </div>
        </div>
    </div>

@endsection