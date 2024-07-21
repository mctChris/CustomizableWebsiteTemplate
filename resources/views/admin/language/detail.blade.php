@extends('layouts.admin')
@section('buttons')

    @if($id && $url = $record->getDetailUrl(['preview' => true]))
    <a class="btn btn-primary" href="{{ $url }}" target="_blank">Preview</a>
    @endif

    @can('update', $record)
    <button class="btn btn-success btn-save-main-form" type="button">Save</button>
    @endcan

    @if(!$record->default_language)
    @can('delete', $record)
    <button class="btn btn-danger btn-save-delete-form" type="button">Delete</button>
    @endcan
    @endif

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
                'field' => 'name',
                'title' => 'Language Name (Only you can see this)'
            ])                    

            @row([
                'type' => 'textinput',
                'field' => 'display_name',
                'title' => 'Display Name (Website viewers can see this)'
            ])            

            @row([
                'type' => 'textinput',
                'field' => 'code',
                'title' => 'Language Code'
            ])            

            @row([
                'type' => 'radio',
                'field' => 'active',
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

        </div>
        @include('admin.base.footer') 
    </div>
</form>
@endsection
@section('js')
@endsection