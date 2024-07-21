@php
    if(is_array($value)){
        $value  = json_encode($value);
    }
    $color = json_decode($value);
@endphp

@if($repeater)
    Not supported for color picker
   {{--  <sketch-picker v-model="{{ $value }}" v-if="{{ $value }}.rgba"></sketch-picker>
    <input type="hidden" :name="{{ $name }}" :value="JsonStringify({{ $value }}.rgba)">
    @section('js-before')
        @parent
        <script>
            mixins.push({
                created: function(){
                    if(!{!! $value !!}){
                        {!! $value !!} = {
                            r: {{ $color->r ?? '255' }}, g: {{ $color->g ?? '0' }}, b: {{ $color->b ?? '0' }}, a: {{ $color->a ?? '1' }},
                            rgba: {!! $value ? $value : '{ r: 255, g: 0, b: 0, a: 1}' !!}
                        }
                    }
                }
            })
        </script>
    @endsection --}}
@else
    <sketch-picker v-model="{{ $name }}"></sketch-picker>
    <input type="hidden" name="{{ $name }}" :value="JsonStringify({{ $name }}.rgba)">
    @section('js-before')
        @parent
        <script>
            mixins.push({
                data: {
                    {{ $name }}: {
                        r: {{ $color->r ?? '255' }}, g: {{ $color->g ?? '0' }}, b: {{ $color->b ?? '0' }}, a: {{ $color->a ?? '1' }},
                        rgba: {!! $value ? $value : '{ r: 255, g: 0, b: 0, a: 1}' !!}
                    }
                }
            })
        </script>
    @endsection
@endif