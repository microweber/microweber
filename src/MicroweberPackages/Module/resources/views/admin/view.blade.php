@extends('admin::layouts.app')

@section('content')


   <div type="{{$type}}" view="admin" class="mw-lazy-load-module" id="id-module-@php echo md5($type); @endphp">
   </div>

@endsection
