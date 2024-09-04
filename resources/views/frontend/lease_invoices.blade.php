@extends('frontend.layouts.template')
@section('content')
@php($currency = $lang == 'eng'?"SR ":"ريال سعودي ")
    <section class="textBannerSec">
        <div class="container-md"></div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                @php($data = custom::loggedInUserProfileInnerInfo("corporate"))
                @php($user_data = $data['user_data'])
                @if(Session::get("user_type") == "individual_customer")
                        @include('frontend.layouts.profile_inner_section')
                    @elseif(Session::get("user_type") == "corporate_customer")
                        @include('frontend.layouts.corporate_profile_inner_section')
                @endif
                <div class="myProfDetail mpd">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="head">
                                <li>
                                    <label>@lang('labels.company_name')</label>
                                    <p>{{$user_data->company_name_en}}</p>
                                </li>
                                <li>
                                    <label>@lang('labels.company_code')</label>
                                    <p>{{$user_data->company_code}}</p>
                                </li>
                                <li>
                                    <label>@lang('labels.due_balance')</label>
                                    <p>{{$currency.number_format($due_balance, 2)}}</p>
                                </li>
                                <li>
                                    <label>@lang('labels.last_update')</label>
                                    <p>{{date('d-m-Y h:i A',strtotime($user_data->updated_at))}}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <div id="old_records">
                                @if(count($my_invoices) > 0)
                                <div class="panel">
                                    <table class="table table-hover" id="corp-invoices">
                                        <thead>
                                        <tr>
                                            <th>@lang('labels.invoice_no')</th>
                                            <th>@lang('labels.invoice_type')</th>
                                            <th>@lang('labels.invoice_month')</th>
                                            <th>@lang('labels.invoice_amount')</th>
                                            <th>@lang('labels.pdf')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($invoiceType = '')
                                            @foreach($my_invoices as $invoice)
                                                @php($leaseDetails = custom::get_lease_invoice_amount($invoice->id))
                                                @if($invoice->invoice_type == 'RNT')
                                                        @php($invoiceType = Lang::get('labels.invoice_type_rnt'))
                                                    @elseif($invoice->invoice_type == 'CN')
                                                        @php($invoiceType = Lang::get('labels.invoice_type_cn'))
                                                    @else
                                                        @php($invoiceType = Lang::get('labels.invoice_type_other'))
                                                    @endif
                                                <tr>
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td>{{$invoiceType}}</td>
                                                    <td>{{date('F-Y',strtotime($invoice->invoice_deserved_date))}}</td>
                                                    <td>{{number_format($leaseDetails['amount'], 2)}}</td>
                                                    <td class="printBtn">
														<a class="edBtn" href="{{custom::baseurl('en/print-lease-invoice').'/'.base64_encode($invoice->id)}}" target="_blank">@lang('labels.eng_print')</a>
														<a class="edBtn" href="{{custom::baseurl('print-lease-invoice').'/'.base64_encode($invoice->id)}}" target="_blank">@lang('labels.arb_print')</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                                @endif
                            </div>
                            <div id="new_records" style="display: none;"></div>
                        </div>
                    </div>
                </div>
				
				<div class="clearfix"></div><br>

        </div>
	</div>
    </section>
@endsection