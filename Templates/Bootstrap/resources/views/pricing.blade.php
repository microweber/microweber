<?php

/*

type: layout
content_type: static
name: Pricing
position: 5
description: Pricing plans page

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="titles/skin-1"/>
        <module type="layouts" template="pricing/skin-1"/>
        <module type="layouts" template="text-block/skin-1"/>
    </div>
@endsection
