@extends('admin.layouts.template')


@section('content')
        <!-- Content Wrapper. Contains page content -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <form action="{{custom::baseurl('admin/exportUsers')}}" method="post" class="btnSecRightEd">
                        {{--<input type="submit" value="Export Data" class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" title="Export To Excel">--}}
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Export Users To Excel">
                            Export
                        </button>
                    </form>
                </div>

            </div>
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3 uk-width-medium-1-3 uk-push-2-3" >
                        <div class="uk-input-group">
                            <label>Write Here And Click Search</label>
                            <input class="md-input" type="text" id="search_keyword" title="Empty the search field and click search to clear search."/>
                            <span class="uk-input-group-addon">
                                <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                   href="javascript:void(0);" id="search_customer"
                                   title="Click to search customer.">Search</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <div class="uk-overflow-container">
                            <div class="md-card uk-margin-medium-bottom">
                                <div class="md-card-content">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="individualCustomersTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
<!-- /.content-wrapper -->
@endsection