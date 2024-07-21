@if( isset($record) && !empty($id))
<div class="card-footer">
    <table class="common-tb card-footer-tb">
        <tr>
            <th>Created </th>
            <td>{{ $record->created_at }} {{ $record->created_user ? '(' . $record->created_user->username . ')' : '' }}</td>
        </tr>
        <tr>
            <th>Updated </th>
            <td>{{ $record->updated_at }} {{ $record->updated_user ? '(' . $record->updated_user->username . ')' : '' }}</td>
        </tr>
    </table>
</div>
@endif