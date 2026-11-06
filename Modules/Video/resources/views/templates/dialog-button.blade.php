{{--
type: layout
name: Button dialog with button
description: Opens video in a popup
--}}

@php
    if(!isset($params['width'])) {
        $params['width'] = 1200;
    }
@endphp

<script>
    $(document).ready(function () {
        $('#video-dialog-button-{{ $params['id'] ?? '' }}').on('click', function (){
            $('#video-dialog-template-{{ $params['id'] ?? '' }}').mwDialog({
                header: false,
                skin: 'video',
                closeButtonAppendTo: '.mw-dialog-holder',
                width: {{ $params['width'] ?? 1200 }},
                height: 800,
            });
            var dialog = mw.dialog.get()

            mw.spinner(({element: dialog.dialogContainer, size: 30})).show();
            $('iframe,img', dialog.dialogContainer).on('load', function (){
                mw.spinner(({element: dialog.dialogContainer, size: 30})).remove();
            })
        })
    });
</script>

@if (mw_asset_once('video-dialog-button'))
    {{-- Skin CSS moved to Modules/Video/resources/assets/css/dialog-button.css (served from
         public/modules/video/css/dialog-button.css); #id scope -> .mw-video-dialog-button-play. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/video/css/dialog-button.css') }}">
@endif

<template id="video-dialog-template-{{ $params['id'] ?? '' }}" style="display: none">{!! $code ?? '' !!}</template>
<span id="video-dialog-button-{{ $params['id'] ?? '' }}" class="mw-video-dialog-button-play"> <module type="btn" button_style="btn-primary" button_text="Play Video"/></span>
