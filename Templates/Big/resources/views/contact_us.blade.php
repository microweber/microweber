<?php

/*

type: layout
content_type: static
name: Contact Us
position: 6
description: Contact Us

*/


?>
@extends('templates.big::layouts.master')

@section('content')

    <div class="edit main-content" data-layout-container rel="content" field="content">
        <module type="layouts" template="titles/skin-2"/>
        <module type="layouts" template="contacts/skin-3"/>
    </div>
@endsection
