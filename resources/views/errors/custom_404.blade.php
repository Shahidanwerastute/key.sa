<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 500;
            src: url(https://colorlib.com/fonts.gstatic.com/s/montserrat/v18/JTURjIg1_i6t8kCHKm45_ZpC3gnD-w.ttf) format('truetype');
        }

        @font-face {
            font-family: 'Titillium Web';
            font-style: normal;
            font-weight: 700;
            src: url(https://colorlib.com/fonts.gstatic.com/s/titilliumweb/v10/NaPDcZTIAOhVxoMyOr9n_E7ffHjDGItzZg.ttf) format('truetype');
        }

        @font-face {
            font-family: 'Titillium Web';
            font-style: normal;
            font-weight: 900;
            src: url(https://colorlib.com/fonts.gstatic.com/s/titilliumweb/v10/NaPDcZTIAOhVxoMyOr9n_E7ffEDBGItzZg.ttf) format('truetype');
        }

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            padding: 0;
            margin: 0
        }

        #notfound {
            position: relative;
            height: 100vh
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .notfound {
            max-width: 767px;
            width: 100%;
            line-height: 1.4;
            padding: 0 15px
        }

        .notfound .notfound-404 {
            position: relative;
            height: 150px;
            line-height: 150px;
            margin-bottom: 25px
        }

        .notfound .notfound-404 h1 {
            font-family: titillium web, sans-serif;
            font-size: 186px;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
            background-color: #D3D3D3;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: cover;
            background-position: center
        }

        .notfound h2 {
            font-family: titillium web, sans-serif;
            font-size: 26px;
            font-weight: 700;
            margin: 0
        }

        .notfound p {
            font-family: montserrat, sans-serif;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0;
            text-transform: uppercase
        }

        .notfound a {
            font-family: titillium web, sans-serif;
            display: inline-block;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            border: none;
            background: #5c91fe;
            padding: 10px 40px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 1px;
            margin-top: 15px;
            -webkit-transition: .2s all;
            transition: .2s all
        }

        .notfound a:hover {
            opacity: .8
        }

        @media only screen and (max-width: 767px) {
            .notfound .notfound-404 {
                height: 110px;
                line-height: 110px
            }

            .notfound .notfound-404 h1 {
                font-size: 120px
            }
        }
    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="notfound">
    <div class="notfound">
        <div class="notfound-404">
            <h1>404</h1>
        </div>
        <h2>Oops! This Page Could Not Be Found</h2>
        <p>Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily
            unavailable</p>
        <a href="<?php echo custom::baseurl('/'); ?>">Go To Homepage</a>
    </div>
</div>
</body>
</html>
