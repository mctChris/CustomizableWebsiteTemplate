@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="company-row w-clearfix" data-ix="fade-in-from-bottom">
                <div class="contact-txt-col">
                    @foreach($page->locations as $location)
                        <div class="contact-b" data-ix="fade-in-from-bottom">
                            <div class="address-title">
                                <div class="address-line"></div>
                                <div class="address-title-txt">{{ $location['title'] }}</div>
                                <div class="address-line"></div>
                            </div>
                            <div class="address-info">
                                <div class="address-info-b">
                                    <div class="address-info-txt"><img src="{{ asset_frontend('images/contact-icon-location.svg') }}" alt="" class="address-info-icon">
                                        <div>{{ $location['address'] }}</div>
                                    </div>
                                    <div class="address-info-txt"><img src="{{ asset_frontend('images/contact-icon-phone.svg') }}" alt="" class="address-info-icon">
                                        <div><a href="tel:{{ str_replace(' ', '', $location['phone'] )}}" class="txt-link">{{ $location['phone'] }}</a></div>
                                    </div>
                                    <div class="address-info-txt"><img src="{{ asset_frontend('images/contact-icon-time.svg') }}" alt="" class="address-info-icon">
                                        <div>{!! $location['opening_hours'] !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="contact-img-col">
                    @if($image = $page->getMedia('image_on_page_left'))
                    <img src="{{ $image->path }}" class="contact-img">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="submenu">
        <div class="submenu-b">
            <div class="submenu-title">
                <h1>{{ $page_title['contact'] }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection