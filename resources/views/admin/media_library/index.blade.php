@extends('layouts.admin')
@section('title')
Home
@endsection
@section('content')
<media-manager id="media-manager" ref="manager" watermark="{{ (config('appcustom.watermark')) }}"></media-manager> 
@endsection
@section('js')
<style>

    @media(min-width: 992px){
        .modal{
            left: 100px;
        }
/*        .breadcrumb-button-row{
            z-index: 0;
        }*/
    }
</style>
@endsection

