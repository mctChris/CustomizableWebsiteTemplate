@extends('layouts.admin')
@section('buttons')

    @if($url = $config['model']->getListingUrl(['preview' => true]))
    <a class="btn btn-primary" href="{{ $url }}" target="_blank">Preview</a>
    @endif

    @can('update', $config['model'])
    <a class="btn btn-primary" href="{{ $config['links']['listing']['add'] }}">+ Add</a>
    {{-- <a class="btn btn-primary" href="{{ $config['links']['listing']['arrangement'] }}">Arrangement</a> --}}
    @endcan

    @can('delete', $config['model'])
    <button class="btn btn-danger btn-save-delete-form" type="button">Delete</button>
    @endcan

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                List {{ $config['page_name'] }}
            </div>
            <div class="card-body">
                <form id="deleteForm" action="{{ route('admin.' . $config['section'] . '.delete') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-responsive-sm table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="col-checkbox"><input type="checkbox" class="checkbox-select-all"></th>
                                <th>Title</th>
                                <th class="col-time">Created</th>
                                <th class="col-time">Updated</th>
                                <th class="col-edit"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $key => $record)
                            <tr>
                                <td class="col-checkbox"><input type="checkbox" name="id[]" value="{{ $record->id }}" {{ !auth()->user()->can('delete', $record) ? 'disabled' : '' }} {{ $record->default_language ? 'disabled' : '' }}></td>

                                {{-- @php
                                    dd($record);
                                @endphp --}}
                                <td><a href="{{ $record->link }}">{{ $record->name }}</a></td>
                                <td>@include('admin.base.created-at')</td>
                                <td>@include('admin.base.updated-at')</td>
                                <td><a href="{{ $record->detail_link }}"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('admin.base.pagination')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection