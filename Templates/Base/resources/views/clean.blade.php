<?php

/*

type: layout
content_type: static
name: Clean page
position: 1
description: Clean page

*/

?>
@extends('templates.base::layouts.master')

@section('content')
<div class="edit main-content" data-layout-container rel="content" field="content">
    <module type="layouts" template="clean"/>
</div>
@endsection