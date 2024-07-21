@extends('layouts.frontend')

@section('content')
<div class="all home">
    <div class="home-banner">
        <div data-delay="4000" data-animation="fade" data-autoplay="1" data-duration="800" data-infinite="1" class="home-banner-slider w-slider">
            <div class="w-slider-mask">
                @foreach($page->banners as $banner)
                    <div class="home-banner-slide home-banner01 w-slide" style="background-image: url({{ $banner['medias']['banner'][0]['path'] }})"></div> 
                @endforeach
            </div>
            <div class="dropdown-submenu w-slider-arrow-left" data-ix="slide-arrow">
                <div class="slide-arrow-b">
                    <img src="{{ asset_frontend('images/slider-arrow-l-grey.svg') }}" alt="" class="slide-arrow">
                    <img src="{{ asset_frontend('images/slider-arrow-l-purple.svg') }}" alt="" class="slide-arrow-hover">
                </div>
            </div>
            <div class="dropdown-submenu w-slider-arrow-right" data-ix="slide-arrow">
                <div class="slide-arrow-b">
                    <img src="{{ asset_frontend('images/slider-arrow-r-grey.svg') }}" alt="" class="slide-arrow">
                    <img src="{{ asset_frontend('images/slider-arrow-r-purple.svg') }}" alt="" class="slide-arrow-hover">
                </div>
            </div>
            <div class="home-slide-nav w-slider-nav w-round"></div>
        </div>
        <div class="home-banner-slide-txt">
            <div class="home-banner-txt">{!! $page->banner_title ?? 'TASTE THE DELICACY TODAY' !!}</div>
        </div>
        <div class="home-scroll-btn-b">
            <a href="#footer" class="home-scroll-btn w-inline-block" data-ix="home-scroll-btn">
                <img src="{{ asset_frontend('images/home-scroll-arrow.svg') }}" alt="" class="home-scroll">
                <img src="{{ asset_frontend('images/home-scroll-arrow-hover.svg') }}" alt="" class="home-scroll-hover">
            </a>
        </div>
    </div>
    <div class="home-content-b" data-ix="home-scroll">
        <div class="home-content">
            <div class="home-content-row">

                <a href="{{ $page->column_a_button_link }}" class="home-content-link img-about w-inline-block" data-ix="home-content-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_a_background_image')->path : '' }});">
                    <div class="home-content-link-bg bg-white"></div>
                    <div class="home-content-hover-b">
                        <div class="home-content-hover-txt-b">
                            <div class="home-content-link-title">
                                <div><span class="home-content-title-txt">{{ substr($page->column_a_title, 0, 1) }}</span>{{ substr($page->column_a_title, 1) }}</div>
                            </div>
                            <p>{{ $page->column_a_description }}</p>
                        </div>
                        <div class="home-content-hover-show">
                            <div class="home-content-hover-btn">
                                <div>{{ $page->column_a_button_text }}</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ $page->column_b_button_link }}" class="home-content-link img-collection w-inline-block" data-ix="home-content-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_b_background_image')->path : '' }});">
                    <div class="home-content-link-bg bg-purple"></div>
                    <div class="home-content-hover-b">
                        <div class="home-content-hover-txt-b">
                            <div class="home-content-link-title">
                                <div><span class="home-content-title-txt">{{ substr($page->column_b_title, 0, 1) }}</span>{{ substr($page->column_b_title, 1) }}</div>
                            </div>
                            <p>{{ $page->column_b_description }}</p>
                        </div>
                        <div class="home-content-hover-show">
                            <div class="home-content-hover-btn">
                                <div>{{ $page->column_b_button_text }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ $page->column_c_button_link }}" class="home-content-link img-news w-inline-block" data-ix="home-content-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_c_background_image')->path : '' }});">
                    <div class="home-content-link-bg bg-white"></div>
                    <div class="home-content-hover-b">
                        <div class="home-content-hover-txt-b">
                            <div class="home-content-link-title">
                                <div><span class="home-content-title-txt">{{ substr($page->column_c_title, 0, 1) }}</span>{{ substr($page->column_c_title, 1) }}</div>
                            </div>
                            <p>{{ $page->column_c_description }}</p>
                        </div>
                        <div class="home-content-hover-show">
                            <div class="home-content-hover-btn">
                                <div>{{ $page->column_c_button_text }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ $page->column_d_button_link }}" class="home-content-link img-cert w-inline-block" data-ix="home-content-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_d_background_image')->path : '' }});">
                    <div class="home-content-link-bg bg-purple"></div>
                    <div class="home-content-hover-b">
                        <div class="home-content-hover-txt-b">
                            <div class="home-content-link-title">
                                <div><span class="home-content-title-txt">{{ substr($page->column_d_title, 0, 1) }}</span>{{ substr($page->column_d_title, 1) }}<br></div>
                            </div>
                            <p>{{ $page->column_d_description }}</p>
                        </div>
                        <div class="home-content-hover-show">
                            <div class="home-content-hover-btn">
                                <div>{{ $page->column_d_button_text }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ $page->column_e_button_link }}" class="home-content-link img-contact w-inline-block" data-ix="home-content-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_e_background_image')->path : '' }});">
                    <div class="home-content-link-bg bg-white"></div>
                    <div class="home-content-hover-b">
                        <div class="home-content-hover-txt-b">
                            <div class="home-content-link-title">
                                <div><span class="home-content-title-txt">{{ substr($page->column_e_title, 0, 1) }}</span>{{ substr($page->column_e_title, 1) }}</div>
                            </div>
                            <p>{{ $page->column_e_description }}</p>
                        </div>
                        <div class="home-content-hover-show">
                            <div class="home-content-hover-btn">
                                <div>{{ $page->column_e_button_text }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="home-content-mobile">
        <div class="home-mobile-link-b about" data-ix="home-link" style="background-image: url({{ $page->getMedia('column_a_background_image') ? $page->getMedia('column_a_background_image')->path : '' }});">
            <div class="home-link-txt-b bg-white">
                <div class="home-link-txt">
                    <div>{{ $page->column_a_title }}</div>
                    <div class="home-link-expand" data-ix="home-link-expand">
                        <div class="home-sublink-all">
                            <div class="home-sublink-b"><a href="{{ route('about.story', [], false) }}" class="home-sublink">{{ $page_title['about_story'] }}</a></div>
                            <div class="home-sublink-b"><a href="{{ route('about.values', [], false) }}" class="home-sublink">{{ $page_title['about_values'] }}</a></div>
                            <div class="home-sublink-b"><a href="{{ route('about.process', [], false) }}" class="home-sublink">{{ $page_title['about_process'] }}</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-mobile-link-b outdrinks" data-ix="home-link" style="background-image: url({{ $page->getMedia('column_b_background_image') ? $page->getMedia('column_b_background_image')->path : '' }});">
            <div class="home-link-txt-b bg-white">
                <a href="{{ route('products', [], false) }}" class="home-link w-inline-block">
                    <div class="home-link-txt">
                        <div>{{ $page->column_b_title }}</div>
                    </div>
                </a>
            </div>
        </div>        
        <div class="home-mobile-link-b news" data-ix="home-link" style="background-image: url({{ $page->getMedia('column_c_background_image') ? $page->getMedia('column_c_background_image')->path : '' }});">
            <div class="home-link-txt-b bg-white">
                <a href="{{ route('news', [], false) }}" class="home-link w-inline-block">
                    <div class="home-link-txt">
                        <div>{{ $page->column_c_title }}</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="home-mobile-link-b cert" data-ix="home-link" style="background-image: url({{ $page->getMedia('column_d_background_image') ? $page->getMedia('column_d_background_image')->path : '' }});">
            <div class="home-link-txt-b bg-purple">
                <a href="{{ route('certificates', [], false) }}" class="home-link w-inline-block">
                    <div class="home-link-txt">
                        <div>{{ $page->column_d_title }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="home-mobile-link-b cert" data-ix="home-link" style="background-image: url({{ $page->getMedia('column_e_background_image') ? $page->getMedia('column_e_background_image')->path : '' }});">
            <div class="home-link-txt-b bg-purple">
                <a href="{{ route('contact', [], false) }}" class="home-link w-inline-block">
                    <div class="home-link-txt">
                        <div>{{ $page->column_e_title }}</div>
                    </div>
                </a>
            </div>
        </div>        

    </div>
    {{-- <div class="inner-bg"></div> --}}
</div>
@endsection