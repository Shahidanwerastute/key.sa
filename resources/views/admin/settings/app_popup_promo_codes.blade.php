@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="md-card-content">
                        <h3 class="heading_a uk-margin-bottom">App Popup Promo Codes</h3>
                        <table class="uk-table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title (En)</th>
                                <th>Title (Ar)</th>
                                <th>Sub Title (En)</th>
                                <th>Sub Title (Ar)</th>
                                <th>Prefix</th>
                                <th>Total Codes</th>
                                <th>Used Codes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($rows && count($rows) > 0)
                                @php($i = 1)
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$row->eng_title}}</td>
                                        <td>{{$row->arb_title}}</td>
                                        <td>{{$row->eng_sub_title}}</td>
                                        <td>{{$row->arb_sub_title}}</td>
                                        <td>{{$row->prefix}}</td>
                                        <td>{{$row->no_of_codes}}</td>
                                        <td>{{$row->used_codes_count}}</td>
                                        <td>{{$row->status == 1 ? 'Active' : 'In-Active'}}</td>
                                        <td>
                                            <a href="{{custom::baseurl('admin/app-popup-promo-codes/edit/' . $row->id)}}" title="Edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="{{custom::baseurl('admin/app-popup-promo-codes/export/' . $row->id)}}" title="Export">
                                                <i class="material-icons">file_download</i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php($i++)
                                @endforeach
                            @else
                                <tr style="text-align: center;">
                                    <td colspan="13">No Records Found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-accent" href="{{custom::baseurl('admin/app-popup-promo-codes/add')}}">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>
@endsection