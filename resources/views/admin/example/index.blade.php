@extends('layouts.admin')

@section('content')
<form id="mainForm" action="" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">Example</div>
        <div class="card-body">

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'title',
            ])

            @row([
                'type' => 'textarea',
                'has_language' => true,
                'field' => 'description',
            ])

            @row([
                'type' => 'editor',
                'field' => 'editor',
                'has_language' => true,
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'image',
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'image_list',
                'options' => [
                    'is_single' => false
                ]
            ])

            @row([
                'type' => 'radio',
                'field' => 'my_radio',
                'options' => [
                    'default' => 0,
                    'list' => [
                        [
                            'title' => 'Yes',
                            'value' => 1,
                        ],
                        [
                            'title' => 'No',
                            'value' => 0,
                        ],
                    ]
                ]
            ])

            @row([
                'type' => 'select',
                'field' => 'my_select',
                'options' => [
                    'placeholder' => [
                        'title' => 'Please Select'
                    ],
                    'list' => [
                        [
                            'title' => 'Yes',
                            'value' => 1,
                        ],
                        [
                            'title' => 'No',
                            'value' => 0,
                        ],
                    ]
                ]
            ])

            <div class="form-group row">
                <label for="text-input" class="col-md-2 col-form-label">Title</label>
                <div class="col-md-10">
                    <select name="value_id" class="form-control">
                        <option value="0">Please Select</option>
                        @foreach([] as $value)
                        <option value="{{ $value->id }}" {{ ($record->value_id == $value->id) ? 'selected' : '' }}>{{ $value->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('admin.base.seo')
        </div>
    </div>
    <div class="card">
        <div class="card-header">Repeater</div>
        <div class="card-body">

            @repeater([
                'field' => 'logos',
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
                        'type' => 'textarea',
                        'field' => 'title',
                        'has_language' => true,
                        'options' => [
                            'width' => '50%'
                        ]
                    ],
                    [
                        'type' => 'textinput',
                        'field' => 'url',
                        'options' => [
                            'width' => '30%'
                        ]
                    ],
                    [
                        'type' => 'radio',
                        'field' => 'my_radio',
                        'options' => [
                            'list' => [
                                [
                                    'title' => 'Yes',
                                    'value' => 1,
                                ],
                                [
                                    'title' => 'No',
                                    'value' => 0,
                                ],
                            ]
                        ]

                    ]
                ]
            ])
        </div>
    </div>
</form>
@endsection
