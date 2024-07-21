@php 
    $main_field = $field;
    $main_title = $title ?? ucwords(str_replace('_', ' ', ($main_field ?? '')));

    $template_obj = [];
@endphp
<div class="form-group row">
    @if(isset($show_title) && $show_title)
    <label class="col-md-2 col-form-label" for="text-input">{{ $main_title }}</label>
    @endif
    <div class="col-md-{{ (isset($show_title) && $show_title) ? 10 : 12 }}">
        <input type="hidden" name="repeater[{{ $main_field }}]" v-if="!record.{{ $main_field }} || record.{{ $main_field }}.length == 0">
        <div class="repeater">
            <div class="repeater-header-row">
                @foreach($sub_fields as $key => $sub_field)
                    @php 
                        $title = $sub_field['title'] ?? ucwords(str_replace('_', ' ', ($sub_field['field'] ?? '')));
                    @endphp
                    <div class="repeater-header-cell" style="width: {{ $sub_field['options']['width'] ?? '50%' }}">{{ $title }}</div>
                @endforeach
                <div class="repeater-header-cell remove-col"></div>
            </div>
            <draggable class="mb-3">
                <div v-if="record.{{ $main_field }}" v-for="(row, r_index) in record.{{ $main_field }}" class="repeater-row">
                    <div class="repeater-content-row">
                        @foreach($sub_fields as $key => $sub_field)
                            @php
                                $title = $sub_field['title'] ?? ucwords(str_replace('_', ' ', ($sub_field['field'] ?? '')));

                                $_name = isset($sub_field['field']) ? ('repeater[' . $main_field . "][' + r_index + '][" . $sub_field['field'] . "]") : '';

                                $name = !empty($_name) ? "'$_name'"  : '';

                                $value = 'record.' . $main_field . "[r_index]['" . ($sub_field['field'] ?? '') . "']";

                                if(isset($relation) && !empty($relation)){
                                    $name = 'relation' . '[' . $relation . ']' . (isset($field) ? '[' . $field . ']' : '') ;
                                    $value = (isset($field) && isset($record->$relation->$field)) ? $record->$relation->$field : '';
                                }
                                
                                $dot_name = arrStringToDotString($name);

                                if (isset($sub_field['has_language']) && $sub_field['has_language']){
                                    if(!isset($template_obj['languages'])){
                                        $template_obj['languages'] = [];
                                        foreach ($languages as $key => $language){
                                            $template_obj['languages'][$language->id] = [];
                                        }
                                    }
                                    foreach ($languages as $key => $language){
                                        $template_obj['languages'][$language->id][$sub_field['field']] = '';
                                    }
                                }else if($sub_field['type'] == 'image-upload'){

                                }else{
                                    $template_obj[$sub_field['field']] = '';
                                }


                            @endphp
                            <div class="repeater-cell" style="width: {{ $sub_field['options']['width'] ?? '50%' }}">
                                @if (isset($sub_field['has_language']) && $sub_field['has_language'])
                                    @include('admin.components.language-tabs', [
                                        'repeater' => true,
                                        'main_field' => $main_field,
                                        'type' => $sub_field['type'],
                                        'languages' => $languages,
                                        'relation' => $sub_field['relation'] ?? '',
                                        'title' => $title,
                                        'field' => $sub_field['field'] ?? '',
                                        'field_base' => $sub_field['field_base'] ?? false,
                                        'remark' => $sub_field['remark'] ?? '',
                                        'options' => $sub_field['options'] ?? []
                                    ])

                                @else
                                    @include('admin.components.input.' . $sub_field['type'], [
                                        'repeater' => true,
                                        'main_field' => $main_field,
                                        'title' => $title,
                                        'name' => $name,
                                        'value' => $value,
                                        'field' => $sub_field['field'] ?? '',
                                        'options' => $sub_field['options'] ?? [],
                                        'relation' => $sub_field['relation'] ?? false,
                                        'dot_name' => $dot_name
                                    ])
                                    @include('admin.components.remark', [
                                        'remark' => $sub_field['remark'] ?? '',
                                    ])
                                    @if (isset($sub_field['field']) && $errors->has($sub_field['field']))
                                        @include('admin.components.error', [
                                            'error' => $errors->get($sub_field['field'])[0]
                                        ])
                                    @endif
                                @endif      
                                
                                @includeIf('admin.components.' . $sub_field['type'], [
                                    'repeater' => true,
                                    'main_field' => $main_field,
                                    'title' => $title,
                                    'name' => $name,
                                    'field' => $sub_field['field'] ?? '',
                                    'value' => $value,
                                    'options' => $sub_field['options'] ?? [],
                                    'relation' => $sub_field['relation'] ?? false,
                                    'dot_name' => $dot_name
                                ])
                              
                                @if (isset($sub_field['relation']) && !empty($sub_field['relation']))  
                                <input type="hidden" name="relation[{{ $sub_field['relation'] }}][id]" value="{{ isset($record->$relation->id) ? $record->$relation->id : '' }}">
                                @endif
                            </div>
                        @endforeach
                        <div class="repeater-cell remove-col">
                            <a href="javascript:void(0);" @click="remove_row_{{ $main_field }}(r_index)" class="btn-remove-row">
                                <i class="fa fa-close"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </draggable>
            <a href="javascript:void(0);" class="btn btn-primary" @click="add_row_{{ $main_field }}">Add Row</a>
        </div>
    </div>
</div>
@section('js-before')
    @parent
    <script>
        mixins.push({
            data: {

            },
            created: function(){
                if(!this.record.{{ $main_field }}){
                    this.record.{{ $main_field }} = [];
                }
            },
            methods: {
                add_row_{{ $main_field }}: function(){
                    this.record.{{ $main_field }}.push({!! json_encode($template_obj) !!});
                },
                remove_row_{{ $main_field }}: function(index){
                    this.record.{{ $main_field }}.splice(index, 1);
                }
            }
        })
    </script>
@endsection