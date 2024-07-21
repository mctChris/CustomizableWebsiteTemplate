@php
    $value_key = $options['value_key'] ?? 'value';
    $title_key = $options['title_key'] ?? 'title';
    if(!isset($options['selected'])){
        $options['selected'] = $value;
    }
    foreach ($options['list'] as $key => $item) {
        $options['list'][$key]['value'] = $item[$value_key];
        $options['list'][$key]['text'] = $item[$title_key];
    }
    $custom_attrs = $options['custom_attrs'] ?? [];
@endphp
<searchselect 
    :options='{{ $var_name }}_options'
    v-model="{{ $var_name }}_selected"
    placeholder="{{ $options['placeholder'] ?? 'Search and Select...' }}"
    @foreach($custom_attrs as $attr => $attr_value)
        {!! $attr !!}="{!! $attr_value !!}"
    @endforeach
>
</searchselect>
<input type="hidden" name="{{ $name }}" :value="{{ $var_name }}_selected.id">

@section('js-before')
    @parent
    <script>
        mixins.push({
            data: {
                {{ $var_name }}_selected: {
                    id: null,
                    text: null,
                    value: null,
                },
                {{ $var_name }}_options: {!! json_encode($options['list'] ?? []) !!}
            },
            created: function(){
                self = this;
                var selected = _.filter(this.{{ $var_name }}_options, function(item){
                    return self.record.{!! $js_name !!} == item.id;
                });
                if(selected.length > 0){
                    this.{{ $var_name }}_selected = selected[0];
                }
            }
        })
    </script>
@endsection