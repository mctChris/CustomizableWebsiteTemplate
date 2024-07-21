@if($repeater)
    <editor 
        :init="{{ $options['editor_params'] ?? 'editorParams' }}"
        :name="{{ $name }}"
        :initial-value="{{ old($dot_name) ? old($dot_name) : $value }}"
    >
    </editor>
@else
    <editor 
        :init="{{ $options['editor_params'] ?? 'editorParams' }}"
        name="{{ $name }}"
        :initial-value="{{ json_encode(old($dot_name) ?? $value) }}"
    >
    </editor>
@endif


        {{-- v-model="record.{{ $js_name ?? $name }}" --}}