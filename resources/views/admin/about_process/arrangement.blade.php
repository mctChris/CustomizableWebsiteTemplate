@extends('layouts.admin')
@section('buttons')

    @can('update', $config['model'])
    <button class="btn btn-success btn-save-main-form" type="button">Save</button>
    @endcan

    <a class="btn btn-secondary" href="{{ $config['links']['arrangement']['back'] }}">Back</a>
    
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Drag & Drop to change arrangement.
            </div>

            <div class="card-body">
                <form id="mainForm" action="{{ route('admin.' . $config['section'] . '.save_arrangement') }}" method="post">
                    @csrf
                    <a v-if="records.length > 0" href="javascript:void(0);" class="btn btn-primary mb-2" type="button" @click="records.reverse()">Reserve Order</a>
                    <draggable v-model="records">
                        <div v-for="element in records" :key="element.id" class="list-group-item">
                            <div><i class="fa mr fa-arrows-alt"></i><span v-text="element.process_heading"></span></div>
                            <input type="hidden" name="id[]" :value="element.id">
                        </div>
                    </draggable>
               </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection