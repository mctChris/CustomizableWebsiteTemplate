@php
    $value_key = $options['value_key'] ?? 'value';
    $title_key = $options['title_key'] ?? 'title';
    if(!isset($options['checked'])){
        $options['checked'] = $value;
    }
    if(isset($options['default']) && ($options['checked'] === null || $options['checked'] === '')){
        $options['checked'] = $options['default'];
    }
@endphp
@if($repeater)
    @foreach($options['list'] as $key => $option)
    <div class="form-check form-check-inline mr-3">
        <input class="form-check-input" :id="'checkbox-' + '{{ $main_field }}' + r_index + '{{ $field . $option[$value_key] }}'" type="checkbox" v-model="{{ $value }}" value="{{ $option[$value_key] }}" :name="{{ $name }}" {{ in_array($option[$value_key], $options['checked']) ? 'checked' : '' }}>
        <label class="form-check-label" :for="'checkbox-' + '{{ $main_field }}' + r_index + '{{ $field . $option[$value_key] }}'">{{ $option[$title_key] }}</label>
    </div>
    @endforeach
@else
    <div class="form-check form-check-inline mr-3">
        <input class="form-check-input" id="checkbox-{{ $name }}_all" type="checkbox" @change="{{ $name }}_checkAll" v-model="{{ $name }}_input_checkAll">
        <label class="form-check-label" for="checkbox-{{ $name }}_all">Check All</label>
    </div>
    <div class="form-check form-check-inline mr-3" v-for="(option, key) in {{ $name }}_options">
        <input class="form-check-input" :id="'checkbox-{{ $name }}' + key" type="checkbox" :value="option.{{ $value_key }}" name="{{ $name }}[]" :checked="{{ $name }}_isChecked(option)" @change="{{ $name }}_check(option)">
        <label class="form-check-label" :for="'checkbox-{{ $name }}' + key" v-text="option.{{ $title_key }}"></label>
    </div>

    @section('js-before')
        @parent
        <script>
            mixins.push({
                data: {
                    {{ $name }}_options: {!! json_encode($options['list']) !!},
                    {{ $name }}_checked: {!! json_encode($options['checked']) !!},
                    {{ $name }}_input_checkAll: false
                },
                methods: {
                    {{ $name }}_check: function(option){
                        var index = this.{{ $name }}_checked.indexOf(option.id);
                        if(index == -1){
                            this.{{ $name }}_checked.push(option.id);
                        }else{
                            this.{{ $name }}_input_checkAll = false;
                            this.{{ $name }}_checked.splice(index, 1);
                        }
                    },
                    {{ $name }}_checkAll: function(e){
                        if(e.target.checked){
                            this.{{ $name }}_checked = _.map(this.{{ $name }}_options, 'id');
                        }else{
                            this.{{ $name }}_checked = [];
                        }
                    },
                    {{ $name }}_isChecked: function(option){
                        return this.{{ $name }}_checked.indexOf(option.id) != -1;
                    }
                }
            })
        </script>
    @endsection
@endif