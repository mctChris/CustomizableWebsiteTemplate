@extends('layouts.admin')
@section('buttons')

    @can('update', $record)
    <button class="btn btn-success btn-save-main-form" type="button">Save</button>
    @endcan

    @can('delete', $record)
    <button class="btn btn-danger btn-save-delete-form" type="button">Delete</button>
    @endcan

    <a class="btn btn-secondary" href="{{ $config['links']['detail']['back'] }}">Back</a>
    
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
                'field' => 'title'
            ])

            @if($record->is_super_admin)
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="text-input">Super Admin</label>
                    <div class="col-md-10">
                        Yes
                    </div>
                </div>

            @else
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="text-input">Permissions</label>
                <div class="col-md-10">
                    
                    <table class="permissions-tb common-tb">
                        <tr>
                            <th></th>
                            @foreach($permissions_type as $pType)
                            <th><div class="permissions-type">{{ $pType }}</div></th>
                            @endforeach
                        </tr>
                        @foreach($config['route_sections'] as $section => $sectionName)
                        <tr>
                            <td><div class="permissions-section">{{ $sectionName }}</div></td>
                            @foreach($permissions_type as $pType)
                            <td align="center">
                                <input type="hidden" name="permissions[{{ $section }}][{{ $pType }}]" value="0">
                                <input type="checkbox" name="permissions[{{ $section }}][{{ $pType }}]" value="1" {{ (isset($record->permissions[$section][$pType]) && $record->permissions[$section][$pType] === '1') ? 'checked="checked"' : '' }}>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
        @include('admin.base.footer') 
    </div>
</form>
@endsection