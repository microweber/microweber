<?php

/*
  type: layout
  content_type: static
  name: Home
  position: 1
  description: Home
*/

?>
@extends('bootstrap::layouts.master')

@section('content')

    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="jumbotron/skin-1"/>
        <module type="layouts" template="features/skin-4"/>
        <module type="layouts" template="content/skin-2"/>
        <module type="layouts" template="features/skin-3"/>
        <module type="layouts" template="blog/skin-1"/>
        <module type="layouts" template="content/skin-6"/>
        <module type="layouts" template="content/skin-4"/>
        <module type="layouts" template="text-block/skin-4"/>
    </div>
@endsection
