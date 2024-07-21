@extends('layouts.frontend')

@section('content')
<div class="all">
    <div class="section-content">
        <div class="container w-container">
            <div class="stage" data-ix="fade-in-from-bottom">
                @foreach($records as $record)
                    <div class="stage-b">
                            <div class="stage-heading">
                                <div>{{ $record->process_heading ?? '' }}</div>
                            </div>

                            <div class="stage-row">
                                @foreach($record->processes as $p)  
                                    <div class="stage-col">

                                        @if($loop->parent->odd)
                                        <div class="stage-txt-b {{ $loop->odd ? 'bg-lightpurple' : 'bg-purple' }}">
                                        @else
                                        <div class="stage-txt-b {{ $loop->odd ? 'bg-purple' : 'bg-lightpurple' }}">
                                        @endif
                                            <img src="{{ $p['medias']['image'][0]['path'] ?? '' }}" alt="" class="stage-icon">
                                            <div class="stage-title">{{ $p['title'] }}<br></div>
                                            <div>{{ $p['description'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('frontend.common.sidebar', ['sidebar' => $sidebar])
</div>
@endsection