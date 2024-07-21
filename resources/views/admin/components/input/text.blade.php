@php
    $value = $options['value'] ?? $value;
@endphp

<div class="form-control">{!! nl2br(e($value)) !!}</div>