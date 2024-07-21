@if($repeater)
    <textarea class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" :name="{{ $name }}" rows="5" v-model="{{ $value }}"></textarea>
@else
    <textarea class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" name="{{ $name }}" rows="5">{{ old($dot_name) ?? $value }}</textarea>
@endif

