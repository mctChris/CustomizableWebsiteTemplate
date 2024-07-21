@php
    $value_key = $options['value_key'] ?? 'value';
    $title_key = $options['title_key'] ?? 'title';
    if(!isset($options['checked'])){
        $options['checked'] = $value;
    }
    if(isset($options['default']) && ($options['checked'] === null || $options['checked'] === '')){
        $options['checked'] = $options['default'];
    }
    $required = isset($options['required']) && $options['required'];
@endphp
@if($repeater)
    @foreach($options['list'] as $key => $option)
    <div class="form-check form-check-inline mr-3">
        <input class="form-check-input" :id="'radio-' + '{{ $main_field }}' + r_index + '{{ $field . $option[$value_key] }}'" type="radio" v-model="{{ $value }}" value="{{ $option[$value_key] }}" :name="{{ $name }}" {{ (string)$options['checked'] === (string)$option[$value_key] ? 'checked' : '' }}>
        <label class="form-check-label" :for="'radio-' + '{{ $main_field }}' + r_index + '{{ $field . $option[$value_key] }}'">{{ $option[$title_key] }}</label>
    </div>
    @endforeach
@else
    @if(isset($options['show_na']) && $options['show_na'])
    <div class="form-check form-check-inline mr-3">
        <input class="form-check-input" id="radio-{{ $name }}00" type="radio" value="" name="{{ $name }}" {{ !$options['checked'] ? 'checked' : '' }} {{ $required ? 'required' : '' }}>
        <label class="form-check-label" for="radio-{{ $name }}00">N/A</label>
    </div>
    @endif
    @foreach($options['list'] as $key => $option)
    <div class="form-check form-check-inline mr-3">
        <input class="form-check-input" id="radio-{{ $name }}{{ $key }}" type="radio" value="{{ $option[$value_key] }}" name="{{ $name }}" {{ (string)$options['checked'] === (string)$option[$value_key] ? 'checked' : '' }} {{ $required ? 'required' : '' }}>
        <label class="form-check-label" for="radio-{{ $name }}{{ $key }}">{{ $option[$title_key] }}</label>
    </div>
    @endforeach
@endif