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

@extends('templates.base::layouts.master')
@section('content')

    <section class="py-5 container">
        <module type="shop" />
    </section>

    <div class="edit" rel="content" field="shop-after-content">
        <p class="element"></p>
    </div>

@endsection