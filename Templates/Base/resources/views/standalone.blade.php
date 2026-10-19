<?php
/*
  type: layout
  content_type: static
  name: Standalone design
  position: 2
  description: Full-bleed self-contained design (JSON import) — no MW header/footer
*/
?>
@php($disable_header = true)
@php($disable_footer = true)
@extends('templates.base::layouts.master')

@section('content')
    <div class="edit main-content" data-layout-container rel="content" field="content"></div>
@endsection
