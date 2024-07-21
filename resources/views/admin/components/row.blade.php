@php
    $title = $title ?? ucwords(str_replace('_', ' ', ($field ?? '')));

    if(isset($field_base) && $field_base){
        $name = isset($field) ? $field_base . '[' . $field . ']' : '';
        $value = $record->$field_base[$field] ?? '';
    }else{
        $name = $field ?? '';
        $value = (isset($field) && isset($record->$field)) ? $record->$field : '';
    }

    if(isset($relation) && !empty($relation)){
        $name = 'relation' . '[' . $relation . ']' . (isset($field) ? '[' . $field . ']' : '') ;
        $value = (isset($field) && isset($record->$relation->$field)) ? $record->$relation->$field : '';
    }
    
    $dot_name = arrStringToDotString($name);
    $js_name = preg_replace("/\[([^\]]*)\]/", "['$1']", $name);
    $var_name = preg_replace("/(\[|\])/", '_', $name);

@endphp
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="text-input">{{ $title }}</label>
    <div class="col-md-10">
        @if (isset($has_language) && $has_language)
            @include('admin.components.language-tabs', [
                'type' => $type,
                'repeater' => false,
                'languages' => $languages,
                'relation' => $relation ?? '',
                'field' => $field ?? '',
                'field_base' => $field_base ?? false,
                'remark' => $remark ?? '',
                'options' => $options ?? [],
            ])

        @else
            @include('admin.components.input.' . $type, [
                'repeater' => false,
                'name' => $name,
                'value' => $value,
                'options' => $options ?? [],
                'relation' => $relation ?? false,
                'dot_name' => $dot_name,
                'js_name' => $js_name,
                'var_name' => $var_name
            ])
            @include('admin.components.remark', [
                'remark' => $remark ?? '',
            ])
            @if (isset($field) && $errors->has($field))
                @include('admin.components.error', [
                    'error' => $errors->get($field)[0]
                ])
            @endif
        @endif      
        
        @includeIf('admin.components.' . $type, [
            'repeater' => false,
            'name' => $name,
            'field' => $field ?? '',
            'value' => $value,
            'options' => $options ?? [],
            'relation' => $relation ?? false,
            'dot_name' => $dot_name
        ])
      
        @if (isset($relation) && !empty($relation))  
        <input type="hidden" name="relation[{{ $relation }}][id]" value="{{ isset($record->$relation->id) ? $record->$relation->id : '' }}">
        @endif

    </div>
</div>