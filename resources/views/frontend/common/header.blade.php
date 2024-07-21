<div data-collapse="all" data-animation="default" data-duration="0" role="banner" class="header w-nav">
    <div class="container-menu w-container">
        <div class="menu-btn w-nav-button" data-ix="menu-btn">

            <div class="header-btn-open-b">
                <img src="{{ asset_frontend('images/btn-menu-black.svg') }}" alt="" class="header-btn-top">
                <img src="{{ asset_frontend('images/btn-menu-black.svg') }}" alt="" class="header-btn-hover">
            </div>
            <div class="header-btn-close-b" data-ix="header-menu-close">
                <img src="{{ asset_frontend('images/btn-menu-close-black.svg') }}" alt="" class="header-btn-close-top">
                <img src="{{ asset_frontend('images/btn-menu-close-black.svg') }}" alt="" class="header-btn-close-hover">
            </div>

        </div>
        <nav role="navigation" class="nav-menu w-clearfix w-nav-menu" data-ix="nav-menu">
            <div class="nav-b">
                <div class="nav-link-b">
                    <div class="nav-title">{!! $page_title['about'] !!}</div>
                    <div class="submenu-link-b">
                        <a href="{{ route('about.story', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_story'] ?? 'STORY' }}</div>
                        </a>
                        <a href="{{ route('about.values', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_values'] ?? 'VALUES' }}</div>
                        </a>
                        <a href="{{ route('about.process', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_process'] ?? 'PROCESS' }}</div>
                        </a>
                    </div>
                </div>

                <div class="nav-link-b">
                    <a href="{{ route('products', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['products'] ?? 'PRODUCTS' }}</div>
                    </a>
                </div>                
                <div class="nav-link-b">
                    <a href="{{ route('news', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['news'] ?? 'NEWS' }}</div>
                    </a>
                </div>
                <div class="nav-link-b">
                    <a href="{{ route('certificates', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['certificates'] ?? 'CERTIFICATES' }}</div>
                    </a>
                </div>
                <div class="nav-link-b">
                    <a href="{{ route('contact', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['contact'] ?? 'CONTACT' }}</div>
                    </a>
                </div>                

                <div class="header-lang">
                    @foreach($all_languages as $lang)
                        <a href="{{ $lang->url }}" class="lang-link chi nav-link">{{ $lang->display_name }}</a>
                        @if(!$loop->last)
                        <span class="nav-link"> | </span>
                        @endif
                    @endforeach
                </div>


            </div>
        </nav>

        @if($logo = $header->getMedia('site_logo'))
        <a href="{{ route('index', [], false) }}" aria-current="page" class="header-logo w-nav-brand w--current">
            <div class="header-logo-img-b"><img src="{{ $logo->path }}" alt="" class="header-logo-img"></div>
        </a>
        @endif

        <div class="header-btn-b">
            @foreach($header->social_media as $social_media)
                <a href="{{ $social_media['link'] }}" target="_blank" class="header-btn w-inline-block" data-ix="header-btn-hover">
                    <div class="header-btn-img-b" data-ix="header-btn-hover">
                        <img src="{{ $social_media['medias']['image'][0]['path'] }}" alt="" class="header-btn-top">
                        <img src="{{ $social_media['medias']['image'][0]['path'] }}" alt="" class="header-btn-hover">
                    </div>
                </a>
            @endforeach
        </div>


    </div>
</div>
<div data-collapse="all" data-animation="over-right" data-duration="800" role="banner" class="header-mobile w-nav">
    <div class="menu-btn w-nav-button" data-ix="menu-btn">
        <div class="header-btn-open-b">

            <img src="{{ asset_frontend('images/btn-menu-black.svg') }}" alt="" class="header-btn-top">
            <img src="{{ asset_frontend('images/btn-menu-black.svg') }}" alt="" class="header-btn-hover">
        </div>
        <div class="header-btn-close-b" data-ix="header-menu-close">
            <img src="{{ asset_frontend('images/btn-menu-close-black.svg') }}" alt="" class="header-btn-close-top">
            <img src="{{ asset_frontend('images/btn-menu-close-black.svg') }}" alt="" class="header-btn-close-hover">
        </div>
    </div>
    <div class="container-menu w-container">
        <a href="{{ route('index', [], false) }}" aria-current="page" class="header-logo w-inline-block w--current">
            <img src="{{ $header->getMedia('site_logo') ? $header->getMedia('site_logo')->path : '' }}" alt="" class="logo">
        </a>
        <nav role="navigation" class="nav-menu w-clearfix w-nav-menu">
            <div class="nav-b">
                <div class="nav-link-b">
                    <div class="nav-title">{{ $header->menu_a_title ?? 'ABOUT US' }}</div>
                    <div class="submenu-link-b">
                        <a href="{{ route('about.story', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_story'] ?? 'STORY' }}</div>
                        </a>
                        <a href="{{ route('about.values', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_values'] ?? 'VALUES' }}</div>
                        </a>
                        <a href="{{ route('about.process', [], false) }}" class="nav-submenu-link w-inline-block">
                            <div>{{ $page_title['about_process'] ?? 'PROCESS' }}</div>
                        </a>
                    </div>
                </div>
                <div class="nav-link-b">
                    <a href="{{ route('products', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['products'] ?? 'PRODUCTS' }}</div>
                    </a>
                </div>                
                <div class="nav-link-b">
                    <a href="{{ route('news', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['news'] ?? 'NEWS' }}</div>
                    </a>
                </div>
                <div class="nav-link-b">
                    <a href="{{ route('certificates', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['certificates'] ?? 'CERTIFICATES'}}</div>
                    </a>
                </div>
                <div class="nav-link-b">
                    <a href="{{ route('contact', [], false) }}" class="nav-link w-inline-block">
                        <div>{{ $page_title['contact'] ?? 'CONTACT'}}</div>
                    </a>
                </div> 

                <div class="header-lang">
                    @foreach($all_languages as $lang)
                        <a href="{{ $lang->url }}" class="lang-link chi nav-link">{{ $lang->display_name }}</a>
                        @if(!$loop->last)
                        <span class="nav-link"> | </span>
                        @endif                        
                    @endforeach
                </div>

            </div>
        </nav>
    </div>
</div>