@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="collections-row w-clearfix" data-ix="fade-in-from-bottom">
                @foreach($products as $product)
                    <div class="collections-col">
                        <div class="project-link">
                            @if($image = $product->getMedia('featured_image'))
                            <img src="{{ $image['path'] }}" alt="" class="collections-img">
                            @endif
                            <div class="listing-hover-b">
                                <a href="{{ route('products', [], false) }}/{{ $product_category_slug }}/{{ $product->url_slug }}" class="listing-hover w-inline-block bg-green" data-ix="project-hover" style="background-color: {{ color($product->overlay_color) }}; transition: transform 500ms ease 0s">
                                    <div>{{ $product->title }}<br></div>
                                    <div class="listing-line"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    @include('frontend.common.sidebar', ['sidebar' => $sidebar]);
</div>
@endsection

@section('js')
<script type="text/javascript">
var images = document.querySelectorAll('.listing-hover-b');

images.forEach(image => {
    image.addEventListener('mouseover', function(){
        var overlay = image.querySelector('.listing-hover');
        overlay.style.transform = 'translateX(0%) translateY(0px) translateZ(0px)';
    })

    image.addEventListener('mouseout', function(){
        var overlay = image.querySelector('.listing-hover');
        overlay.style.transform = 'translateX(-102%) translateY(0px) translateZ(0px)';
    })    
})
</script>
@endsection