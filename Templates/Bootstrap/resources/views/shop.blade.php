<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: Showcase shop items in a stylish grid arrangement.
position: 4
*/

?>

@extends('templates.bootstrap::layouts.master')

@section('content')
<div class="edit" rel="content" field="content">
    <module type="layouts" template="titles/skin-1"/>
    <module type="layouts" template="ecommerce/skin-1"/>
</div>
@endsection
