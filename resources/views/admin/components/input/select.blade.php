@php
    $value_key = $options['value_key'] ?? 'value';
    $title_key = $options['title_key'] ?? 'title';
    if(!isset($options['selected'])){
        $options['selected'] = $value;
    }
@endphp
@if($repeater)
    <select class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" v-model="{{ $value }}" :name="{{ $name }}">
        @if(isset($options['placeholder']))
            <option value="{{ $options['placeholder']['value'] ?? '' }}" disabled selected>{{ $options['placeholder']['title'] }}</option>
        @endif
        @foreach($options['list'] as $option)
        <option value="{{ $option[$value_key] }}">{{ $option[$title_key] }}</option>
        @endforeach
    </select>
@else
    @if(isset($options['vue']) && $options['vue'])
        <select class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" v-model="{{ $name }}_selected" name="{{ $name }}" @change="{{ $name }}_change">
            <option v-for="item in {{ $name }}_options" :value="item.{{ $value_key }}" v-text="item.{{ $title_key }}"></option>
        </select>
        @section('js-before')
            @parent
            <script>
                mixins.push({
                    data: {
                        {{ $name }}_model: null,
                        {{ $name }}_selected: null,
                        {{ $name }}_options: {!! json_encode($options['list']) !!}
                    },
                    mounted: function(){
                        this.{{ $name }}_set_model(this.record.{{ $name }});
                    },
                    methods: {
                        {{ $name }}_change: function(e){
                            var self = this;
                            var id = e.target.value;
                            this.{{ $name }}_set_model(id);
                        },
                        {{ $name }}_set_model: function(id){
                            var self = this;
                            var selected = _.filter(this.{{ $name }}_options, function(item){
                                return id == item.{{ $value_key }};
                            });
                            if(selected.length > 0){
                                this.{{ $name }}_model = selected[0];
                                this.{{ $name }}_selected = selected[0].{{ $value_key }};

                                @if(isset($options['change']))
                                    Vue.nextTick(function () {
                                        self.{{ $options['change'] }};
                                    });
                                @endif
                            }
                        }
                    }
                })
            </script>
        @endsection
    @else
    <select class="form-control {{ $errors->has($dot_name) ? 'is-invalid' : ''  }}" name="{{ $name }}">
        @if(isset($options['placeholder']))
            <option value="{{ $options['placeholder']['value'] ?? '' }}">{{ $options['placeholder']['title'] }}</option>
        @endif
        @foreach($options['list'] as $option)
        <option value="{{ $option[$value_key] }}" {{ (string)$options['selected'] === (string)$option[$value_key] ? 'selected' : '' }}>{{ $option[$title_key] }}</option>
        @endforeach
    </select>
    @endif
@endif

