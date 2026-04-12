<?php

/*
  type: layout
  content_type: static
  name: Home
  position: 1
  description: Home
*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="jumbotron/skin-1"/>
        <module type="layouts" template="features/skin-1"/>
        <module type="layouts" template="content/skin-1"/>
        <module type="layouts" template="blog/skin-1"/>
        <module type="layouts" template="text-block/skin-1"/>
    </div>
@endsection
