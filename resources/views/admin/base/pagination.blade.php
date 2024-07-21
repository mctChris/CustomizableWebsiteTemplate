<div>
    @if($records->total())
        {{ $records->appends(request()->query())->links() }}
        <div>Showing {{ $records->firstItem() }} to  {{ $records->lastItem() }} of {{ $records->total() }} result(s)</div>
    @else
        No Record Found
    @endif
</div>
