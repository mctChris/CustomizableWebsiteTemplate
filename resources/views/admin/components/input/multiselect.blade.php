<multiselect
  v-model="{{ $name }}_selected"
  :multiple="true"
  :options="{{ $name }}_options"
  label="{{ $options['label'] ?? 'title' }}"
  track-by="{{ $options['key'] ?? 'id' }}"
>
</multiselect>

<input v-for="item in {{ $name }}_selected" type="hidden" name="{{ $name }}[]" :value="item.id">

@section('js-before')
    @parent
    <script>
        mixins.push({
            data: {
                {{ $name }}_selected: [],
                {{ $name }}_options: {!! json_encode($options['items'] ?? []) !!}
            },
            created: function(){
                this.{{ $name }}_selected = _.filter(this.{{ $name }}_options, function(item){
                    return {!! json_encode($options['selected'] ?? []) !!}.indexOf(item.id) >= 0;
                })
            },
            computed: {
                selectValues: function(){
                    return _.map(this.{{ $name }}_selected, function(item){
                        return item.id;
                    }).join(',');
                }
            }
        })
    </script>
@endsection