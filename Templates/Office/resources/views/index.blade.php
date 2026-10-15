<?php

/*
  type: layout
  content_type: static
  name: Home
  position: 1
  description: Home
*/


?>

@extends('templates.base::layouts.master')

@section('content')

    <div class="edit" data-layout-container field="content" rel="content">

        <module type="layouts" template="videos/skin-6"/>
        <module type="layouts" template="features/skin-4"/>
        <module type="layouts" template="content/skin-35"/>
        <module type="layouts" template="content/skin-35"/>
        <module type="layouts" template="price_lists/skin-7"/>
        <module type="layouts" template="content/skin-35"/>
        <module type="layouts" template="call-to-action/skin-19"/>

     </div>

@endsection



