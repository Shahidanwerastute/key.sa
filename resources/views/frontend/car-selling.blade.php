@extends('frontend.layouts.template')
@section('content')
    <link rel="stylesheet" href="<?php echo $base_url; ?>/public/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/public/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

      .bannerHeadingSece div#carouselExampleCaptions .carousel-caption {

            /*color: #FE7E00;*/
          color: #ffff;
            font-size: 49px;
            letter-spacing: -1.3px;
            line-height: 1.4;
            margin: 0;
            text-shadow: 0 0 6px rgba(0, 0, 0, 0.9);
        }

      .bannerHeadingSece div#carouselExampleCaptions  .carousel-caption {
          position: absolute;
          top: 0px;
          bottom: 0.25rem;
          right: 15%;
          left: 15%;
           padding-top: 0rem;
           padding-bottom: 0rem;
          color: #fff;
          text-align: center;
          display: flex;

          display: flex !important;
          justify-content: center;
          align-items: center;
      }

      .bannerHeadingSece div#carouselExampleCaptions .carousel-control-next-icon{
          background-size: 20px;
          background-color: #0A0A0A;
          border-radius: 50%;
      }

      .bannerHeadingSece div#carouselExampleCaptions .carousel-control-prev-icon{
          background-size: 20px;
          background-color: #0A0A0A;
          border-radius: 50%;
      }
      .section-1{
            background-color: #f2f2f2;
            padding: 17px 0 0;
            color: #868686;
        }
        .section-1 .main-div {

            background-color: #fff;
            border: 1px solid #e1e1e1;
            border-radius: 7px;
            -webkit-box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.2);
            box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.2);
            max-width: 1240px;
            min-height: 150px;
            padding: 20px 30px;

        }

        .section-1 .main-div .hr{
            background-color: #f2f2f2;
            height: 5px;
            margin: 10px 5px;
        }
        .section-1 .testimonial{
            margin-top: 10px;
            padding: 10px 20px;
            text-align: center;

        }

        .section-1 .testimonial .logo{
            padding: 15px 0px;
            text-align: center;
            display: flex;
            justify-content: center;
        }


        .section-1 .images-area .image{
            margin-top: 10px;
        }

        .owl-carousel {
            padding: 10px 50px;
            background-color: #eeeeee;
            border-radius: 10px;
        }
      .owl-carousel .owl-item img {
          display: block;
          width: 50%;
          margin: 0 auto;
      }
      .owl-carousel .owl-item img.active {
          border: 2px solid #FE7E00;
          border-radius: 10px;
      }

        .standardPageSec .second .date p{
            background-color: #eeeeee;
            border-radius: 20px;
            padding: 10px;
            text-align: center;
            color: black;
            font-weight: bold;
        }

      .standardPageSec .second .date p.active{
          border: 2px solid #FE7E00;
      }

        .servicesSec .first span{
            color: #7f8c8d !important;
            font-size: 16px;
        }


      .carousel-main-div .owl-btn-prev  {
          position: absolute;
          left: 0px;
          padding: 0px 15px;
          z-index: 1;
          top: 35%;
      }
      .carousel-main-div .owl-btn-next  {
          padding: 0px 15px;
          position: absolute;
          z-index: 1;
          top: 35%;
          right: 0px;
      }

      .carousel-main-div i{
          font-size: 30px;
      }

      .owl-carousel .owl-stage {
          display: flex;
          align-items: center;
      }
      /* medium and up screens */

      @media (max-width: 767px) {
          .carousel-inner .carousel-item > div {
              display: none;
          }
          .carousel-inner .carousel-item > div:first-child {
              display: block;
          }
      }

      .carousel-inner .carousel-item.active,
      .carousel-inner .carousel-item-next,
      .carousel-inner .carousel-item-prev {
          display: flex;
      }

      /* medium and up screens */
      @media (min-width: 768px) {

          .carousel-inner .carousel-item-end.active,
          .carousel-inner .carousel-item-next {
              transform: translateX(25%);
          }

          .carousel-inner .carousel-item-start.active,
          .carousel-inner .carousel-item-prev {
              transform: translateX(-25%);
          }
      }

      .carousel-inner .carousel-item-end,
      .carousel-inner .carousel-item-start {
          transform: translateX(0);
      }

.arb i{
    font-family: FontAwesome !important;
}

      @media screen and (max-width: 992px) {
          .bannerHeadingSece div#carouselExampleCaptions .carousel-caption {

              font-size: 30px;

          }
      }
      @media screen and (max-width: 576px) {
          .bannerHeadingSece div#carouselExampleCaptions .carousel-caption {

              font-size: 25px;

          }
      }

    </style>
    <section class="bannerHeadingSece">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($slider_images as $slider_image)
                <div class="carousel-item {{$loop->first ? 'active' : ''}}" data-bs-interval="3000">
                    <img src="{{$base_url}}/public/uploads/{{$slider_image->image}}" class="d-block w-100" alt="{{ $lang == 'eng' ? $slider_image->image_eng_alt : $slider_image->image_arb_alt }}">
                    <div class="carousel-caption d-block">
                        <span><?php echo ($lang == 'eng' ? $slider_image->eng_title : $slider_image->arb_title); ?>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="section-1">
        <div class="container-md">
              <div class="main-div">
                  {!! $content[$lang . '_top_desc'] !!}
                      <div class="images-area">
                          <div class="row d-flex align-items-center ">
                              @if($content['company_images'])
                                  @php $company_images = explode(',', $content['company_images']); @endphp
                                  @foreach($company_images as $company_image)
                                      <div class="col-md-4">
                                          <div class="image">
                                              <img src="{{ custom::baseurl('/public/uploads') . '/' . $company_image }}" class="img-fluid" alt="{{ $lang == 'eng' ? $content['company_images_eng_alt'] : $content['company_images_arb_alt'] }}">
                                          </div>
                                      </div>
                                  @endforeach
                              @endif
                      </div>
                  </div>

                <div class="hr">
                </div>

                  <div class="row d-flex align-items-baseline ">
                      <h4>{{$lang == 'eng' ? 'Our Special Service' : 'خدماتنا المميزة'}}</h4>
                      @foreach($services as $service)
                          <div class="col-md-4">
                              <div class="testimonial">
                                  <div class="logo">
                                      <img src="{{ custom::baseurl('/public/uploads') . '/' . $service->image }}" class="img-fluid" alt="...">
                                  </div>
                                  {!! ($lang == 'eng' ? $service->eng_desc : $service->arb_desc) !!}
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
        </div>
    </section>

    <section class="standardPageSec">
        <div class="container-md">
            <div class="servicesSec">
                <div class="whiteBox1240 first">
                    <?php echo $content[$lang . '_desc']; ?>
                </div>
                <div class="whiteBox1240 second">
                    <div class="carousel-main-div position-relative">
                    <div class="owl-carousel owl-theme position-relative">
                        @foreach ($brands as $brand)
                            <div class="carSellingFilterByBrand" data-id="{{$brand->id}}" style="cursor: pointer;">

                                    <div class="card-img">
                                        <img src="{{ custom::baseurl('/public/uploads') . '/' . $brand->image }}" class="img-fluid" alt="{{($lang == 'eng' ? $brand->eng_title : $brand->arb_title)}}">
                                    </div>

                            </div>
                        @endforeach

                    </div>

                        <div class="owl-btn-prev  " style="cursor: pointer;">
                            <i class="fa-solid {{$lang == 'eng' ? 'fa-angle-left' : 'fa-angle-left'}}"></i>

                        </div>
                        <div class="owl-btn-next " style="cursor: pointer;">
                            <i class="fa-solid {{$lang == 'eng' ? 'fa-angle-right' : 'fa-angle-right'}}"></i>
                        </div>




                    </div>

                    <div class="row justify-content-center date mt-3 ">
                        @foreach ($years as $year)
                            @if($year->year > 2020)
                                <div class="col-md-2 col-sm-3 col-4 carSellingFilterByYear" data-year="{{$year->year}}" style="cursor: pointer;">
                                    <p>{{$year->year}}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="carSelling">
                        <div class="sellCarList search-results-here">
                                <?php echo custom::sellCarsHtml($cars, $base_url, $lang_base_url, $lang); ?>
                            <?php if ($count_of_cars > 3)
                                { ?>
                            <div class="loadMoreBtn">
                                {{--changing limit here to change how many records are loaded on click of button--}}
                                <button type="button" id="loadMoreCars" data-offset="<?php echo count($cars); ?>" data-limit="6"><?php echo ($lang == 'eng' ? 'Load More' : 'تحميل المزيد'); ?></button>
                            </div>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <script src="<?php echo $base_url; ?>/public/OwlCarousel2-2.3.4/src/js/owl.carousel.js"></script>


    <script>
        $(document).ready(function(){
            <?php if ($lang == 'arb') { ?>
            var owl = $('.owl-carousel').owlCarousel({
                center: true,
                loop:true,
                margin:10,
                nav:true,
                autoplay:true,
                rtl:true,
                autoplayTimeout:3000,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });
            <?php } else { ?>
            var owl = $('.owl-carousel').owlCarousel({
                center: true,
                loop:true,
                margin:10,
                nav:true,
                autoplay:true,
                autoplayTimeout:3000,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });
            <?php } ?>


            $('.owl-btn-next').click(function() {
                owl.trigger('next.owl.carousel');
            })
// Go to the previous item
            $('.owl-btn-prev').click(function() {
                // With optional speed parameter
                // Parameters has to be in square bracket '[]'
                owl.trigger('prev.owl.carousel', [300]);
            })
        });

    </script>

@endsection
