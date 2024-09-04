@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            @if(custom::rights(60, 'add'))
                {!! isset($_REQUEST['msg']) ? '<h5 style="color:red;">'.$_REQUEST['msg'].'</h5>' : '' !!}
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="heading_b md-card-toolbar-heading-text">Import Corporate Quotations</h3>
                    </div>
                    <div class="md-card-content">
                        <form method="post" id="importCorporateQuotations" action="{{custom::baseurl('admin/corporate_quotations/import')}}" enctype="multipart/form-data" style="display: inline; margin-right: 5em;">
                            <input type="file" name="import_file" id="file">
                            <input type="hidden" name="corporate_customer_id" value="{{$corporate_customer_id}}">
                            <button type="submit"
                                    class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"
                                    title="Import Corporate Quotations To Database" onclick="altair_helpers.content_preloader_show();">
                                Import Corporate Quotations
                            </button>
                            <br>
                            <a href="<?php echo custom::baseurl('public/sample-corporate-quotation-prices.xlsx'); ?>" style="font-size: 13px;">Download Sample Sheet</a>
                        </form>
                    </div>
                </div>
            @endif
            @if(custom::rights(60, 'view'))
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="heading_b md-card-toolbar-heading-text">Corporate Quotations</h3>
                    </div>
                    <div class="md-card-content">
                        <div id="CorporateQuotations"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection