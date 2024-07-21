@extends('layouts.frontend')

@section('content')

<div class="all">
    <div class="section-content">
        <div class="container-details w-container">
            <div class="details-title-mobile">
                <div>{{ $product->title }}<br></div>
                <a href="{{ $parent_route }}" class="btn-back w-inline-block" data-ix="btn-back">
                    <div>{{ lang('back') }}</div>
                </a>
            </div>
            <div class="collections-details-content-b">
                <div class="details-title">
                    <div>{{ $product->title }}</div>
                    <a href="{{ $parent_route }}" class="btn-back w-inline-block" data-ix="btn-back">
                        <div>{{ lang('back') }}</div>
                    </a>
                </div>
                <div data-animation="slide" data-duration="500" data-infinite="1" id="main-slider" class="main-slider w-slider">
                    <div class="mask w-slider-mask">
                        @if($gallery = $product->getMedia('gallery', true))
                            @foreach($gallery as $g)
                                <div class="borders-slide w-slide">
                                    <div class="slider-img _0{{ $loop->iteration }}" style="background-image: url({{ $g->path }})"></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="dropdown-submenu w-slider-arrow-left"></div>
                    <div class="dropdown-submenu w-slider-arrow-right"></div>
                    <div class="dropdown-submenu w-slider-nav w-round"></div>
                </div>
                <div data-animation="slide" data-duration="500" data-infinite="1" data-thumbs-for="#main-slider" class="thumbnail-slider w-slider">
                    <div class="thumbnail-mask w-slider-mask">
                        @if($gallery = $product->getMedia('gallery', true))
                            @foreach($gallery as $g)
                                <div class="thumbnail-slide w-slide">
                                    <div class="slider-img _0{{ $loop->iteration }}" style="background-image: url({{ $g->path }})"></div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <div class="arrow-l w-slider-arrow-left"></div>
                    <div class="arrow-r w-slider-arrow-right"></div>
                    <div class="dropdown-submenu hide w-slider-nav"></div>
                </div>
            </div>
            <div class="collections-details-content-mobile">
                @if($gallery = $product->getMedia('gallery', true))
                    @foreach($gallery as $g)
                        <div class="slider-img _0{{ $loop->iteration }}" style="background-image: url({{ $g->path }})"></div>
                    @endforeach
                @endif 
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
  Webflow.push(function() {
    // When a thumbnail is clicked, update target slider
    $('[data-thumbs-for]').on('click', '.w-slide', function() {
      // Find target slider, if not found exit
      var target = $($(this).parents('.w-slider').attr('data-thumbs-for'));
      if (target.length == 0) return;
      // Update target slider by triggering a "tap" event on the targetNav corresponding slide button
      target.find('.w-slider-nav').children().eq($(this).index()).trigger('tap');
    }); // End click function

    // $('.arrow-l').on('click', function () {
    //     var currentSlideDot = document.querySelector('.w-active');
    //     if (!currentSlideDot.previousElementSibling) return;
    //     currentSlideDot.previousElementSibling.click();
    // })

    // $('.arrow-r').on('click', function () {
    //     var currentSlideDot = document.querySelector('.w-active');
    //     if (!currentSlideDot.nextElementSibling) return;
    //     currentSlideDot.nextElementSibling.click();
    // })    
  }); // End ready function
</script>
@endsection