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

@extends('templates.big::layouts.master')
@section('content')

    <section class="py-5 container">
        <module type="shop" />
    </section>

    <div class="edit" rel="content" field="shop-after-content">
        <p class="element"></p>
    </div>

@endsection
