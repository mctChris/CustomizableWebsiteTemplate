@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container-details w-container">
            <div class="news-details-title-mobile w-clearfix">
                <div><strong>{{ $news->title }}</strong><br></div>
                <a href="{{ route('news', [], false ) }}" class="btn-back news-details mobile w-inline-block" data-ix="btn-back">
                    <div>{{ lang('back') }}</div>
                </a>
            </div>
            <div class="collections-details-content-b-copy w-clearfix">
                <div class="news-details-col-l">
                    <div data-animation="slide" data-duration="500" data-infinite="1" id="main-slider" class="news-details-slider w-slider">
                        <div class="mask w-slider-mask">
                            @foreach($news->getMedia('gallery', true) as $image)
                                <div class="borders-slide w-slide">
                                    <div class="news-slider-img _0{{ $loop->iteration }}" style="background-image: url({{ str_replace(' ', '%20', $image->path) ?? '' }}) !important;"></div>
                                </div>
                            @endforeach 
                        </div>
                        @if(count($news->getMedia('gallery', true)) > 1)
                        <div class="arrow-l news-details w-slider-arrow-left"></div>
                        <div class="arrow-r news-details w-slider-arrow-right"></div>
                        <div class="slide-nav w-slider-nav w-round"></div>
                        @endif
                    </div>
                </div>
                <div class="news-details-col-r w-clearfix">
                    <div class="details-title">
                        <div>{{ $news->title }}</div>
                    </div>
                    <div>{!! $news->description !!}<br></div>
                    <a href="{{ route('news', [], false ) }}" class="btn-back news-details w-inline-block" data-ix="btn-back">
                        <div>{{ lang('back') }}</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection