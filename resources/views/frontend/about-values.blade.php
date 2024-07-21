@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="w-clearfix" data-ix="fade-in-from-bottom">
                @foreach($page->company_values as $value)
                    <div class="services-col">
                        <div class="services-txt">{{ $value['company_value'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('frontend.common.sidebar', ['sidebar' => $sidebar]);
</div>
@endsection