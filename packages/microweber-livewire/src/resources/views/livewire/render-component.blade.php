@extends('admin::layouts.iframe')

@section('content')
<div>
    @livewire($componentName, $componentAttributes, $livewireId)
</div>
@endsection
