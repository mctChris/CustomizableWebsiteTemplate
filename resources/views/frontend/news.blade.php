@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="news-row w-clearfix" data-ix="fade-in-from-bottom">
                @foreach($records as $record)
                    <div class="news-col">
                        <div class="news-link">
                            @if($image = $record->getMedia('feature_image'))
                            <img src="{{ $image->path }}" alt="" class="news-img">
                            @endif
                            <div class="listing-hover-b">
                                <a href="{{ route('news', [], false) }}/{{ $record->url_slug }}" class="listing-hover bg-pink w-inline-block" data-ix="project-hover" style="background-color: {{ $record->overlay_color }} !important; transition: transform 500ms ease 0s;">
                                    <div>{{ $record->title }}<br></div>
                                    <div class="listing-line"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="submenu">
        <div class="submenu-b">
            <div class="submenu-title">
                <h1>{{ $page_title['news'] }}</h1>
            </div>
        </div>
    </div>
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