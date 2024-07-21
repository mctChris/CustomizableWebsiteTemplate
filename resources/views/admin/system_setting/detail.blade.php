@extends('layouts.admin')
@section('buttons')

    @can('update', $record)
    <button class="btn btn-success btn-save-main-form" type="button">Save</button>
    @endcan

    @can('delete', $record)
    <button class="btn btn-danger btn-save-delete-form" type="button">Delete</button>
    @endcan

    @can('back', $record)
    <a class="btn btn-secondary" href="{{ $config['links']['detail']['back'] }}">Back</a>
    @endcan  
@endsection
@section('content')
<form id="deleteForm" action="{{ route('admin.' . $config['section'] . '.delete') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
</form>

<form id="mainForm" action="{{ route('admin.' . $config['section'] . '.save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
    <div class="card">
        <div class="card-header">{{ $config['page_name'] }}</div>
        <div class="card-body" id="app-main">


            <div class="form-group row">
                <h6 class="col-md-12 col-form-label" for="text-input uppercase bold">General Setting</h6>
            </div>

            @include('admin.components.row', [
                'type' => 'textinput',
                'field' => 'page_title',
                'title' => 'Base Page Title',
                'has_language' => true
            ])

            @include('admin.components.row', [
                'type' => 'textarea',
                'field' => 'page_description',
                'title' => 'Base Page Description',
                'has_language' => true
            ])

            <div class="form-group row">
                <h6 class="col-md-12 col-form-label" for="text-input uppercase bold">Default Language</h6>
            </div>

            @include('admin.components.row', [
                'type' => 'radio',
                'field_base' => 'details',
                'field' => 'default_language',
                'title' => 'Default language (Only active languages are shown)',
                'options' => [
                    'list' => $default_language_list
                ]

            ])


            <div class="form-group row">
                <h6 class="col-md-12 col-form-label" for="text-input uppercase bold">Image Upload</h6>
            </div>

            @include('admin.components.row', [
                'type' => 'radio',
                'field_base' => 'details',
                'field' => 'enable_max_img_size',
                'title' => 'Enable Max Image Size',
                'options' => [
                    'list' => [
                        [
                            'title' => 'Yes',
                            'value' => 1,
                            'checked' => $record->details['enable_max_img_size'] ?? false
                        ],
                        [
                            'title' => 'No',
                            'value' => 0,
                            'checked' => !($record->details['enable_max_img_size'] ?? false)
                        ]
                    ]
                ]

            ])

            @include('admin.components.row', [
                'type' => 'textinput',
                'field_base' => 'details',
                'field' => 'img_max_width',
                'title' => 'Max Width',
                'options' => [
                    'input_type' => 'number'
                ]
            ])

            @include('admin.components.row', [
                'type' => 'textinput',
                'field_base' => 'details',
                'field' => 'img_max_height',
                'title' => 'Max Height',
                'options' => [
                    'input_type' => 'number'
                ]
            ])

            @include('admin.components.row', [
                'type' => 'textinput',
                'field_base' => 'details',
                'field' => 'jpg_quality',
                'title' => 'JPG Quality',
                'remark' => 'Type in %, only for jpg',
                'options' => [
                    'input_type' => 'number'
                ]
            ])

        </div>
        @include('admin.base.footer') 
    </div>
</form>
@endsection
@section('js')
@endsection