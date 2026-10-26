<?php

/*

type: layout
content_type: static
name: Plain page
position: 1
description: A blank page on the minimal Plain template — build it with the AI agent or Live Edit.

*/

?>
@extends('templates.plain::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content">
        <div class="container my-3 allow-drop">
            {{-- Intentionally empty: the page content (built by the AI agent /
                 Live Edit and styled by global apply_css) renders here. --}}
        </div>
    </div>
@endsection
