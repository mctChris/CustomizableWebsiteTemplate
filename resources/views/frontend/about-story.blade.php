@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="company-row w-clearfix" data-ix="fade-in-from-bottom">
                <div class="company-img-col">
                    @if($image = $page->getMedia('image'))
                    <img src="{{ $image->path }}" class="img">
                    @endif
                    <div class="company-img-title">{{ $page->title }}</div>
                    <div>{{ $page->subtitle }}<br></div>
                </div>
                <div class="company-txt-col">
                    {!! editor($page->description) !!}
                </div>
            </div>
        </div>
    </div>

    @include('frontend.common.sidebar', ['sidebar' => $sidebar])
</div>
@endsection