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
                'field' => 'banner_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'TASTE THE DELICACY TODAY'
                    ]
                ]
            ])

            @repeater([
                'field' => 'banners',
                'show_title' => true,
                'sub_fields' => 
                [
                    [
                        'type' => 'image-upload',
                        'field' => 'banner'
                    ]
                ]
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_a_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'About Us'
                    ]
                ]
            ])

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'column_a_description',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'column_a_background_image'
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_a_button_text',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'LEARN MORE'
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'column_a_button_link',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => $routeSelect
                ]
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_b_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Products'
                    ]
                ]
            ])          

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'column_b_description',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'column_b_background_image'
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_b_button_text',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'LEARN MORE'
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'column_b_button_link',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => $routeSelect
                ]
            ])  

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_c_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'News'
                    ]
                ]
            ])

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'column_c_description',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'column_c_background_image'
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_c_button_text',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'LEARN MORE'
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'column_c_button_link',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => $routeSelect
                ]
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_d_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Certificates'
                    ]
                ]
            ])

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'column_d_description',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'column_d_background_image'
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_d_button_text',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'LEARN MORE'
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'column_d_button_link',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => $routeSelect
                ]
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_e_title',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Contact'
                    ]
                ]
            ])

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'column_e_description',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.'
                    ]
                ]
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'column_e_background_image'
            ])

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'column_e_button_text',
                'options' => [
                    'custom_attrs' => [
                        'placeholder' => 'LEARN MORE'
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'column_e_button_link',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => $routeSelect
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