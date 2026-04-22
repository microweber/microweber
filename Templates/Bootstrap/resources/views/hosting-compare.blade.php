<?php

/*

type: layout
content_type: static
name: Hosting Compare
position: 6
description: Hosting plans comparison page with pricing tiers, feature matrix and advantages

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="titles/skin-1"/>
        <module type="layouts" template="pricing/skin-2"/>
        <module type="layouts" template="features/skin-2"/>
        <module type="layouts" template="pricing/skin-3"/>
        <module type="layouts" template="text-block/skin-1"/>
    </div>
@endsection
