<?php

/*

type: layout
content_type: static
name: Clean page
position: 1
description: Clean page

*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')

<div class="section edit main-content" data-layout-container rel="content" field="content">
    <div class="my-md-5 my-3 container allow-drop">
        <div class="row">
            <div class="col-12 mx-auto">
                <h2 class="my-md-5 my-3">My title</h2>
                <p>
                    My text content.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
