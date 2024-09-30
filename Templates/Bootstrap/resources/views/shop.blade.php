<?php

/*

type: layout
content_type: dynamic
name: Shop
is_shop: y
description: Showcase shop items in a sylish grid arrangement.
position: 4
*/

?>

@extends('templates.bootstrap::layouts.master')

@section('content')
<div class="edit" rel="content" field="content">
    <module type="layouts" template="content/skin-6"/>
</div>


<section class="py-5 container-fluid">

    <module type="shop" />

</section>

<div class="edit" rel="content" field="shop-after-content">
    <p class="element"></p>
</div>

@endsection
