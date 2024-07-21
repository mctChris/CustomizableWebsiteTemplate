<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>


        <!-- Styles -->
        <link href="{{ asset_admin('css/admin.css') }}" rel="stylesheet">
        <link href="{{ asset_admin('css/media-library.css') }}" rel="stylesheet">
        <link href="{{ asset_admin('css/styles.css') }}" rel="stylesheet">
        @yield('css')
        <script>
            var config = {!! isset($config) ? json_encode($config) : "null" !!};

            var record = {!! (isset($record) && $config['route_section'] == 'detail') ? $record->toJson() : "null" !!};

            var records = {!! isset($records) ? json_encode(method_exists($records, 'items') ? $records->items() : $records->toArray()) : '[]' !!};

        </script>
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed @if(isset($config['menus'])) sidebar-lg-show @endif" _id="app">
        <header class="app-header navbar">
            @if(isset($config['menus']))
            <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            @endif
            <a class="navbar-brand" href="{{ route('admin.home.detail') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            @auth

                @if(isset($config['menus']))
                <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @endif

                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item">
                        Hi, {{ Auth::user()->name }}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="{{ route('index') }}">
                            {{ __('Site Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @else
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="{{ route('index') }}">
                            {{ __('Site Home') }}
                        </a>
                    </li>
                </ul>

            @endauth

        </header>
        <div id="app" class="app-body">
            @if(isset($config['menus']))
                @include('admin.base.menu')
            @endif
            <main class="main">
                @if(isset($config['breadcrumb']) || isset($config['breadcrumb_section']))
                <div class="breadcrumb-button-row">
                    <div class="button-row">
                        @yield('buttons')
                    </div>
                    
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.home.detail') }}">Admin</a>
                        </li>

                        @if(isset($config['breadcrumb_section']) && $config['show_main_breadcrumb'])
                        <li class="breadcrumb-item">
                            <a href="{{ Route::has('admin.' . $config['breadcrumb_section'] . '.listing') ? route('admin.' . $config['breadcrumb_section'] . '.listing', request()->query()) : '' }}">{{ $config['breadcrumb_page_name'] }}</a>
                        </li>
                        @endif

                        @if(isset($config['breadcrumb']))
                            @foreach($config['breadcrumb'] as $breadcrumb)
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a>
                            </li>
                            @endforeach
                        @endif

                        @if(!isset($records) && isset($record))
                            @if($record->is_page)
                                <li class="breadcrumb-item active">{{ $record->id ? 'Detail' : 'Add New' }}</li>
                            @else
                                <li class="breadcrumb-item active">{{ $record->id ? ($record->title ?? 'Detail') : 'Add New' }}</li>
                            @endif
                        @endif
                    </ol>
                </div>
                @endif
                <div class="container-fluid">
                    @if (session('status'))
                        @if (session('status') == 'success')
                        <div class="alert alert-success">
                            <div>{{ session('message') ?? 'Save Successfully!' }}</div>
                            @if($actions_html = session('actions_html'))
                            <div>{!! $actions_html !!}</div>
                            @endif
                        </div>
                        @endif

                        @if (session('status') == 'failure')
                        <div class="alert alert-danger">{{ session('message') ?? 'Save Failure.' }}</div>
                        @endif
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div id="app-content">
                        @yield('content')
                    </div>
                    
                </div>
            </main>
            @auth
                @if(isset($config['route_action']) && $config['route_action'] == 'detail')
                <div :class="'media-manager-popup ' + (mediaManagerShown ? 'active' : '')">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <div class="container">
                                <media-manager id="media-manager" ref="manager" :isSingle="mediaManagerIsSingle" watermark="{{ (config('appcustom.watermark')) }}"></media-manager> 
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endauth
        </div>

        <footer class="app-footer">
            <div>
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</span>
            </div>
            <div class="ml-auto">
                <span>Powered by</span>
                <a href="https://ysd.hk">YSD</a>
            </div>
        </footer>
        <script>window.mixins = [];</script>
        @yield('js-before')
        <script src="{{ asset_admin('js/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset_admin('js/admin.js') }}"></script>
        <script src="{{ asset_frontend_common('js/jquery.bpopup.min.js') }}"></script>
        <script src="{{ asset_admin('js/polyfill.min.js') }}"></script>
        <script src="{{ asset_admin('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset_admin('js/jquery.validate.additional-methods.min.js') }}"></script>
        <script src="{{ asset_admin('js/main.js') }}"></script>
        @yield('js')
    </body>
</html>
