@extends('layouts.admin')
@section('buttons')

    @can('update', $config['model'])
    <a class="btn btn-primary" href="{{ $config['links']['listing']['add'] }}">+ Add</a>
    <a class="btn btn-primary" href="{{ $config['links']['listing']['arrangement'] }}">Arrangement</a>
    @endcan

    @can('delete', $config['model'])
    <button class="btn btn-danger btn-save-delete-form" type="button">Delete</button>
    @endcan

@endsection
@section('content')
<form action="{{ route('admin.' . $config['section'] . '.listing') }}" method="get" enctype="multipart/form-data" class="mb-2">
    <input type="text" name="q" class="form-control d-inline-block align-middle" value="{{ request()->get('q') }}">
    <button class="btn btn-primary" type="submit" class="d-inline-block">Search</button>
    <a class="btn btn-secondary" href="{{ route('admin.' . $config['section'] . '.listing') }}">Reset</a>
</form>
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
                                <th>String Key</th>
                                <th>Progress</th>
                                <th class="col-time">Created</th>
                                <th class="col-time">Updated</th>
                                <th class="col-status">Status</th>
                                <th class="col-edit"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $key => $record)
                            <tr>
                                <td class="col-checkbox"><input type="checkbox" name="id[]" value="{{ $record->id }}" {{ !auth()->user()->can('delete', $record) ? 'disabled' : '' }}></td>
                                <td><a href="{{ $record->link }}">{{ $record->string_key }}</a></td>
                                <td><a href="{{ $record->link }}">{{ $record->getProgress() }}</a></td>
                                <td>@include('admin.base.created-at')</td>
                                <td>@include('admin.base.updated-at')</td>
                                <td>@include('admin.base.status-badge')</td>
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