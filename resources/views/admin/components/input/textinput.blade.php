@php
    $custom_attrs = $options['custom_attrs'] ?? [];
@endphp

@if($repeater)
    <input type="{{ $options['input_type'] ?? 'text' }}" class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" :name="{{ $name }}" v-model="{{ $value }}" autocomplete="off"
    @foreach($custom_attrs as $attr => $attr_value)
        {!! $attr !!}="{!! $attr_value !!}"
    @endforeach
    >
@else
    <input type="{{ $options['input_type'] ?? 'text' }}" class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" name="{{ $name }}" value="{{ old($dot_name) ?? $value }}" autocomplete="off"
    @foreach($custom_attrs as $attr => $attr_value)
        {!! $attr !!}="{!! $attr_value !!}"
    @endforeach
    >
@endif