@extends('frontend.layouts.template')

@section('content')

    <style>
        .servicesSec .whiteBox1240 {
            margin: 0 0 15px;
            padding: 40px 20px 40px 40px;
            color: #868686;
            max-width: 100%;
        }
        .bannerHeadingSec {
            background-position: unset;
            background-repeat: unset;
            background-size: unset;
            min-height: unset;
            padding-top: unset;
            position: unset;
        }
        .bannerHeading {
            background-color: #f2f2f2;
            background-position: center top;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 280px;
            position: relative;
            display: flex;
            align-items: center;
            padding-top: unset;
        }
    </style>

    <section class="standardPageSec">
        <div class="container-md">
            <div class="servicesSec">
                <div class="whiteBox1240 mt-5">
                    {!! $content[$lang . '_page_content'] !!}
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="imgBox">
                            <img src="<?php echo $base_url; ?>/public/uploads/{{ $content[$lang . '_page_image'] }}"
                                 alt="{{ $content[$lang.'_title'] }}" style="width: 100%;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection