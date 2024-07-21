@php
    $value_key = $options['value_key'] ?? 'id';
    $title_key = $options['title_key'] ?? 'title';
    // $var_name = preg_replace("/(\[|\])/", '_', $name);
@endphp
<vue-autosuggest
    :suggestions='{{ $var_name }}_filteredOptions'
    :input-props="{id: 'autosuggest_{{ $var_name }}', class: 'form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}', name: '{{ $name }}'}"
    v-model="{{ $var_name }}_query"
>
</vue-autosuggest>

<div :class="'mt-1 text-calc' + ({{ $var_name }}_textWidthLength > {{ $var_name }}_max_length ? ' exceed' : '')" v-if="{{ $var_name }}_max_length">
    <span v-text="{{ $var_name }}_textWidthLength" class="current"></span> / <span v-text="{{ $var_name }}_max_length" class="max"></span>
</div>

@section('js-before')
    @parent
    <script>
        mixins.push({
            data: {
                {{ $var_name }}_query: "{{ $value }}",
                {{ $var_name }}_suggestions: [{data: {!! json_encode($options['source']) !!}}],
                {{ $var_name }}_max_length: {{ $options['max_length'] ?? 'null' }}
            },
            computed: {
                {{ $var_name }}_filteredOptions: function(){
                    var self = this;
                    var data = this.{{ $var_name }}_suggestions[0].data.filter(function(option){
                        return option.toLowerCase().indexOf(self.{{ $var_name }}_query.toLowerCase()) > -1;
                    });
                    return [{data: data}];
                },
                {{ $var_name }}_textWidthLength: function(){
                    return this.stringLength(this.{{ $var_name }}_query);
                }
            }
        })
    </script>
@endsection