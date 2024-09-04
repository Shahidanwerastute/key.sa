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
                    @elseif (Session::get("user_type") == "corporate_customer")
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
                    <div class="row align-items-center pb-5">
                        <div class="col-md-3">
                            <select id="filter-month">
                                <option value="0" selected>{{$lang == 'eng' ? 'Select Month' : 'اختر الشهر'}}</option>
                                <?php
                                $months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
                                foreach($months as $key => $month) { ?>
                                    <option value="{{$key}}" {{isset($_REQUEST['month']) && $_REQUEST['month'] == $key ? 'selected' : ''}}>{{$lang == 'eng' ? $month : custom::month_arabic($month)}}</option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-year">
                                <option value="0" selected>{{$lang == 'eng' ? 'Select Year' : 'اختر السنة'}}</option>
                                <?php
                                $years = range(date('Y'), date('Y') - 10);
                                foreach($years as $year) { ?>
                                <option value="{{$year}}" {{isset($_REQUEST['year']) && $_REQUEST['year'] == $year ? 'selected' : ''}}>{{$year}}</option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-danger" onclick="filterInvoicesData($('#filter-month').val(), $('#filter-year').val());"><?php echo($lang == 'eng' ? 'Filter' : 'تصفية'); ?></button>
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
                                            <th>@lang('labels.invoice_month')</th>
                                            <th>@lang('labels.invoice_amount')</th>
                                            <th>@lang('labels.paid')</th>
                                            <th>@lang('labels.due_amount')</th>
                                            <th>@lang('labels.pdf')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($my_invoices as $invoice)
                                                @php($contracts = custom::get_invoice_amount($invoice->id))
                                                @if($contracts['due_balance'] > 0)
                                                    @php($add_style = 'background-color: rgba(248, 177, 47, 0.38);')
                                                @else
                                                    @php($add_style = '')
                                                @endif
                                                <tr style="{{$add_style}}">
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td>{{date('F-Y',strtotime($invoice->invoice_issue_date))}}</td>
                                                    <td>{{number_format($contracts['amount'], 2)}}</td>
                                                    <td>{{number_format($contracts['paid'], 2)}}</td>
                                                    <td>{{number_format($contracts['due_balance'], 2)}}</td>
                                                    <td class="printBtn">
														<a class="edBtn" href="{{custom::baseurl('en/print-invoice').'/'.custom::encode_with_jwt($invoice->id)}}" target="_blank">@lang('labels.eng_print')</a>
														<a class="edBtn" href="{{custom::baseurl('print-invoice').'/'.custom::encode_with_jwt($invoice->id)}}" target="_blank">@lang('labels.arb_print')</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($my_invoices_total_count > 10)
                                        <div class="load-more-btn" style="text-align: center;margin-top: 20px;margin-bottom: 20px;">
                                            <a href="javascript:void(0);" data-user_id="{{$user_data->id}}" class="my_invoices_paginate">
                                                <button class="yellowButton" style="height: 26px;width: 100px;">
                                                    @lang('labels.load_more')
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @else
                                    <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="clearfix"></div><br>

        </div>
	</div>
    </section>

    <script>
        function filterInvoicesData(month, year) {
            if (month > 0 && year > 0)
            window.location.href = '<?php echo $lang_base_url . '/my-invoices'; ?>' + '?month=' + month + '&year=' + year;
        }
    </script>
@endsection