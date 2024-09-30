<?php

/*

type: layout
content_type: dynamic
name: Blog
position: 3
description: Blog

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')

    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="blog/skin-2"/>

    </div>
@endsection
