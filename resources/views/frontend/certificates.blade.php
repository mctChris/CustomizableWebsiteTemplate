@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="clients-content w-clearfix" data-ix="fade-in-from-bottom">
                <div class="cert-b">
                    <div class="cert-title">
                        <div>{{ $heading }}</div>
                    </div>
                    <div>
                        @foreach($certificates as $cert)
                            <ul role="list" class="list">
                                <li class="list-item">{{ $cert->title }}<br></li>
                            </ul>
                            @if($image = $cert->getMedia('image'))
                            <img src="{{ $image->path }}" width="390" alt="" class="cert-img">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="submenu">
        <div class="submenu-b">
            <div class="submenu-title">
                <h1>{{ $page_title['certificates'] }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection