<div class="submenu">
    <div class="submenu-b">
        <div class="submenu-title">
            <h1>{!! $sidebar['heading'] !!}</h1>
        </div>

        @foreach($sidebar['items'] as $item)
            <a href="{{ $item['route'] }}" aria-current="page" class="submenu-link w-inline-block 
            @if($loop->iteration == 1)
                w--current
            @endif
            ">
                <div>{{ $item['title'] }}</div>
            </a>
        @endforeach

    </div>
</div>