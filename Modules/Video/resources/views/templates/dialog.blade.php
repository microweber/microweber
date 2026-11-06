{{--
type: layout
name: Button dialog
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
                height: '100%',
                top: '50%'
            });
            var dialog = mw.dialog.get()

            mw.spinner(({element: dialog.dialogContainer, size: 30})).show();
            $('iframe,img', dialog.dialogContainer).on('load', function (){
                mw.spinner(({element: dialog.dialogContainer, size: 30})).remove();
            })
        })
    });
</script>

@if (mw_asset_once('video-dialog'))
    {{-- Skin CSS moved to Modules/Video/resources/assets/css/dialog.css (served from
         public/modules/video/css/dialog.css); #id scope -> .mw-video-dialog-play. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/video/css/dialog.css') }}">
@endif
<div class="video-dialog-container">
<template id="video-dialog-template-{{ $params['id'] ?? '' }}" style="display: none">{!! $code ?? '' !!}</template>
<span id="video-dialog-button-{{ $params['id'] ?? '' }}" class="mw-video-dialog-play"></span>
</div>
