<textarea class="form-control address mb-2 {{ $errors->has($name) ? 'is-invalid' : ''  }}" name="{{ $name }}" autocomplete="off">{{ old($name) ?? $value }}</textarea>

<a href="javascript:void(0);" class="btn btn-primary find_location">Find Location</a>