@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="collections-row w-clearfix" data-ix="fade-in-from-bottom">
                @foreach($product_categories->chunk(3) as $chunk)
                    @foreach($chunk as $category)
                        <div class="collections-col">
                            <div class="project-link" data-ix="project-link">
                                @if($image = $category->getMedia('image'))
                                <img src="{{ $image['path'] }}" alt="" class="collections-img">
                                @endif
                                <div class="listing-hover-b">
                                    <a href="{{ route('products', [], false) }}/{{ $category->url_slug }}" class="listing-hover w-inline-block {{ $loop->first ? 'bg-green' : ($loop->last ? 'bg-yellow' : 'bg-pink') }}" data-ix="project-hover">
                                        <div>{{ $category->title }}<br></div>
                                        <div class="listing-line"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
    <div class="submenu">
        <div class="submenu-b">
            <div class="submenu-title">
                <h1>{{ $page->page_title }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection