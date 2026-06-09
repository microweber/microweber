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

<x-section class="edit main-content" data-layout-container rel="content" field="content">
    <x-container class="my-md-5 my-3 allow-drop">
        <x-row>
            <x-col size="12" class="mx-auto">
                <h2 class="my-md-5 my-3">My title</h2>
                <p>
                    My text content.
                </p>
            </x-col>
        </x-row>
    </x-container>
</x-section>
@endsection
