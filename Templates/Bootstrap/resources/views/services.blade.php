<?php

/*

type: layout
content_type: static
name: Services
position: 3
description: Services page

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="titles/skin-1"/>
        <module type="layouts" template="features/skin-1"/>
        <module type="layouts" template="content/skin-1"/>
        <module type="layouts" template="text-block/skin-1"/>
    </div>
@endsection
