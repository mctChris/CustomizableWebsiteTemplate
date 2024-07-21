<b-tabs>
    @foreach ($languages as $key => $language)
        @php 
            if(isset($field_base) && $field_base){
                $name = isset($field) ? $field_base . '[' . $language->id . '][' . $field . ']' : '';
                $value = $record->languages[$language->id]->$field_base[$field] ?? '';
            }else{
                $name = isset($field) ? 'languages' . '[' . $language->id . '][' . $field . ']' : '';
                $value = $record->languages[$language->id]->$field ?? '';

                if($repeater){
                    $name = "'repeater[$main_field][' + r_index + '][languages][$language->id][$field]'";
                    $value = 'record.' . $main_field . "[r_index]['languages'][$language->id]['" . ($sub_field['field'] ?? '') . "']";
                }
            }

            if(isset($relation) && !empty($relation)){
                $name = 'relation' . '[' . $relation . ']' . '[languages]' . '[' . $language->id . ']' . ((isset($field) && !empty($field)) ? '[' . $field . ']' : '');

                if($record->$relation){
                    $record->$relation->languages = $record->$relation->languages ?: $record->$relation->descriptions->keyBy('language_id');
                    $value = (isset($field) && isset($record->$relation->languages[$language->id]->$field)) ? $record->$relation->languages[$language->id]->$field : '';
                }else{
                    $value = "";
                }
            }

            $dot_name = arrStringToDotString($name);
            $js_name = preg_replace("/\[([^\]]*)\]/", "['$1']", $name);
            $var_name = preg_replace("/(\[|\])/", '_', $name);

        @endphp
        <b-tab title="{{ $language->name }}">
            @include('admin.components.input.' . $type, [
                'repeater' => $repeater,
                'main_field' => $main_field ?? false,
                'field' => $field ?? false,
                'name' => $name,
                'value' => $value,
                'language_id' => $language->id,
                'remark' => $remark ?? '',
                'relation' => $relation ?? false,
                'dot_name' => $dot_name,
                'js_name' => $js_name,
                'var_name' => $var_name
            ])
            @include('admin.components.remark', [
                'remark' => $remark ?? '',
            ])
            @if (isset($field) && $errors->has($dot_name))
                @include('admin.components.error', [
                    'error' => $errors->get($dot_name)[0]
                ])
            @endif
        </b-tab>
    @endforeach
</b-tabs>