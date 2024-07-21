<div class="card">
    <div class="card-header">Seo Setting</div>
    <div class="card-body">

        <input type="hidden" name="relation[seo][post_id]" value="{{ $id }}">
        <input type="hidden" name="relation[seo][id]" value="{{ isset($record->seo->id) ? $record->seo->id : '' }}">

        @row([
            'type' => 'textinput',
            'has_language' => true,
            'field' => 'title',
            'relation' => 'seo',
        ])

        @row([
            'type' => 'textarea',
            'has_language' => true,
            'field' => 'description',
            'relation' => 'seo',
        ])

    </div>
</div>