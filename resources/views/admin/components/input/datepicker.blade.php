@if($repeater)
    <date-picker class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" :value="{{ old($dot_name) ?? $value }}" :name="{{ $name }}" :config="datepickerOptions" autocomplete="off"></date-picker>
@else
    <date-picker class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" value="{{ old($dot_name) ?? $value }}" name="{{ $name }}" :config="datepickerOptions" autocomplete="off"></date-picker>
@endif