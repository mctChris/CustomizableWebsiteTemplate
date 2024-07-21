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
        <div class="card-body">

            <div class="form-group row hide">
                <label class="col-md-2 col-form-label" for="text-input">Category</label>
                <div class="col-md-10">
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="0" data-level="0" {{ $record->parent_id === 0 ? "selected='selected'" : "" }}>Main Category</option>
                        {!! $table_tree_html !!}
                    </select>
                </div>
            </div>

            @row([
                'type' => 'textinput',
                'has_language' => true,
                'field' => 'title',
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'featured_image',
            ])

            @row([
                'type' => 'image-upload',
                'field' => 'gallery',
                'options' => [
                    'is_single' => false
                ]
            ])  

            @row([
                'type' => 'colorpicker',
                'field' => 'overlay_color'
            ])        

            @include('admin.base.status')
        </div>
        @include('admin.base.footer')
    </div>
    @include('admin.base.seo')
</form>
@endsection
@section('js')
@endsection