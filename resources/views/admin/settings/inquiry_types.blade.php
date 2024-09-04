@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-2">
                    {{--Inquiry Type settings--}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="InquiryTypesTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    {{--Depatment settings --}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="DepartmentTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection