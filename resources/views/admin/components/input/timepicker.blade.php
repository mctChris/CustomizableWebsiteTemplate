@php
    if(!empty($value) && preg_match("/[0-9]{2}:[0-9]{2}\s[a-z]{2}/", $value)){
        $time_array = explode(" ", $value);
        list($h, $m) = explode(":", $time_array[0]);
        $a = $time_array[1];
    }
@endphp
@if($repeater)
    <vue-timepicker class="{{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" :minute-interval="5" :value="{{ $value }}" :name="{{ $name }}" autocomplete="off" format="hh:mm a"></vue-timepicker>
@else
    <vue-timepicker class="{{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" :minute-interval="5" :value="{ hh: '{{ $h ?? '12' }}', mm: '{{ $m ?? '00' }}',  a: '{{ $a ?? 'am' }}' }" name="{{ $name }}" autocomplete="off" format="hh:mm a"></vue-timepicker>
@endif