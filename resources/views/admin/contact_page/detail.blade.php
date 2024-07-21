@extends('layouts.admin')
@section('buttons')

    @if($id && $url = $record->getDetailUrl(['preview' => true]))
    <a class="btn btn-primary" href="{{ $url }}" target="_blank">Preview</a>
    @endif

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

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'page_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Contact'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'image_on_page_left',
            ])

            @repeater([
                'field' => 'locations',
                'show_title' => true,
                'sub_fields' => 
                [
                    [
                        'type' => 'textinput',
                        'field' => 'title',
                        'has_language' => true,
                        'options' => [
                            'custom_attrs' => [
                                'placeholder' => 'Hong Kong'
                            ]
                        ]                        
                    ],                    
                    [
                        'type' => 'textinput',
                        'field' => 'address',
                        'has_language' => true,
                        'options' => [
                            'custom_attrs' => [
                                'placeholder' => '123 Demo St, Wanchai, Hong Kong'
                            ]
                        ]                        
                    ],
                    [
                        'type' => 'textinput',
                        'field' => 'phone',
                        'options' => [
                            'custom_attrs' => [
                                'placeholder' => '+85223456788'
                            ]
                        ]                        
                    ],
                    [
                        'type' => 'editor',
                        'field' => 'opening_hours',
                        'has_language' => true,
                        'options' => [
                            'custom_attrs' => [
                                'placeholder' => '10:00 am - 9:00 pm. Monday to Sunday.'
                            ]
                        ]                        
                    ]                                        
                ]

            ])

        </div>
        @include('admin.base.footer') 
    </div>
    @include('admin.base.seo')
</form>
@endsection
@section('js')
@endsection