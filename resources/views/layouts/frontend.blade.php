<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="5eeaddc33bdf61e2c4d5eef9" data-wf-site="5eeaddc33bdf610310d5eeeb">
    <head>
        <meta charset="utf-8">
        <title>{{ $seo['title'] ?? $seo['page_title'] ?? '' }}</title>
        <meta name="description" content="{{ $seo['description'] ?? '' }}" />
        <meta name="keywords" content="{{ $seo['keywords'] ?? '' }}" />
        <meta property="og:title" content="{{ $seo['title'] ?? '' }}"/> 
        <meta property="og:description" content="{{ $seo['description'] ?? '' }}"/> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            var config = {
                endpoints: {
                    refresh_csrf: "{{ route('refresh-csrf') }}"
                }
            }
        </script>
        
        <link href="{{ asset_frontend('css/normalize.css') }}" rel="stylesheet">
        <link href="{{ asset_frontend('css/components.css') }}" rel="stylesheet">
        <link href="{{ asset_frontend('css/bubble-bubble-v01-epa.css') }}" rel="stylesheet">        
        <link href="{{ asset_frontend('css/website.css') }}" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>        

        <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Nunito:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic"]
            }
        });
        </script>
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);
        </script>
        <link href="images/favicon.png" rel="shortcut icon" type="image/x-icon">
        <link href="images/webclip.png" rel="apple-touch-icon">
        <style>
        .nav-menu {
            -webkit-overflow-scrolling: touch;
        }

        * {
            -webkit-print-color-adjust: exact;
        }

        @media print {
            .nav-menu {
                display: none;
            }

            .header {
                position: absolute;
            }
        }

        .w-slider-dot {
            width: 16px;
            height: 16px;
            background: #7356A7;
            margin: 0 7px .3em;
            border-radius: 0;
        }

        .w-slider-dot.w-active {
            width: 16px;
            height: 16px;
            background: #ffffff;
            border: 2px solid #7356A7;
        }

        .services-col {
            background-color: #F1EEF6;
        }
        .services-col:nth-of-type(6n + 1),
        .services-col:nth-of-type(6n + 3),
        .services-col:nth-of-type(6n + 5) {
            background-color: #fafafa;
        }
        @media(max-width: 767px) {
        .services-col:nth-of-type(6n + 1),
        .services-col:nth-of-type(6n + 3),
        .services-col:nth-of-type(6n + 5) {
          background-color: #F1EEF6;
        }
        .services-col:nth-of-type(4n + 1),
        .services-col:nth-of-type(4n + 4) {
            background-color: #fafafa;
        }
        }
        </style>           
    </head>
    <body class="body">
        @include('frontend.common.header')
        @yield('content')
        @include('frontend.common.footer')
        {{-- <script src="{{ asset_frontend('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script> --}}
        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.4.1.min.220afd743d.js?site=5eeaddc33bdf610310d5eeeb" type="text/javascript" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>        
        <script src="{{ asset_frontend('js/jquery.bpopup.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset_frontend('js/refresh-csrf.js') }}" type="text/javascript"></script>
        @include('frontend.common.ie')

        <script src="{{ asset_frontend('js/bubble-bubble-v01-epa.js') }}" type="text/javascript"></script>
        <script src="{{ asset_frontend('js/website.js') }}" type="text/javascript"></script>
        @yield('js')
    </body>
</html>
