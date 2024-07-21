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
                'type' => 'image-upload',
                'field' => 'site_logo',
            ])

            @repeater([
                'field' => 'social_media',
                'show_title' => true,
                'sub_fields' =>
                [
                    [
                        'type' => 'image-upload',
                        'field' => 'image',
                        'options' => [
                            'width' => '20%'
                        ]
                    ],
                    [
                        'type' => 'textinput',
                        'field' => 'link',
                        'options' => [
                            'width' => '30%',
                            'custom_attrs' => [
                                'placeholder' => 'https://facebook.com/example'
                            ]
                        ]
                    ]
                ]
            ])                                           


        </div>
        @include('admin.base.footer') 
    </div>
</form>
@endsection
@section('js')
@endsection