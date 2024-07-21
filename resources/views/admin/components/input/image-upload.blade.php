@php
    
    if($relation){
        $medias = $record->$relation ? $record->$relation->getMedias() : array();
        if(isset($language_id)){
            $name = 'relation' . '[' . $relation . ']';
        }
    }else{
        if(isset($language_id)){
            unset($name);
        }
    }

    $is_repeater = isset($repeater) && $repeater;

    $field_name = 'medias';

    if(!isset($options['type'])){
        $options['type'] = $field;
    }

    $language_id = $language_id ?? 1;
    if($is_repeater){
        $_medias = 'record.' . $main_field . '[r_index].medias ? record.' . $main_field . "[r_index].medias[" . $language_id . "]['" . $options['type'] . "'] : []";
    }else{
        $_medias = $medias[$language_id][$options['type']] ?? array();
    }
@endphp

<gallery-list 
    :is-single="{{ (!isset($options['is_single']) || $options['is_single']) ? 'true' : 'false' }}"
    type="{{ $options['type'] }}"
    main-field="{{ $main_field ?? ''}}"
    :is-repeater="{{ $is_repeater ? 'true' : 'false' }}"
    language-id="{{ $language_id }}"
    :medias="{{ $is_repeater ? $_medias : json_encode($_medias) }}"
    :r-index="r_index"
    field="{{ $field_name }}"
    last-folder-id="{{ request()->session()->get('lastBrowseFolderId') }}"
    is-video="{{ $options['is_video'] ?? false }}"
    is-file="{{ $options['is_file'] ?? false }}"
    button-title="{{ $options['button_title'] ?? '' }}"
>
</gallery-list>