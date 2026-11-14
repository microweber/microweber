<?php

/*

type: layout
content_type: post
name: Post - Minimal
position: 3
description: Narrow, distraction-free reading column - no hero, just title, date and body.

*/

// Self-contained single-post layout (rendered directly by the resolver).
$post = get_content_by_id(content_id());
$picture = get_picture(content_id());
if (!$picture) {
    $picture = '';
}
$itemData = content_data(content_id());
$itemTags = content_tags(content_id());
?>
@extends('templates.base::layouts.master')

@section('content')
<div class="mw-skin-pmin" id="blog-content-{{ content_id() }}">
    <div class="container">
        <div class="mw-pmin-col mx-auto">
            <h1 class="mw-pmin-title">{{ $post['title'] }}</h1>
            <p class="mw-pmin-date">{{ date('d M Y', strtotime($post['created_at'])) }}</p>

            <div class="description edit typography-area" field="content" rel="content" data-mwplaceholder="Your text here">


            </div>
            <div class="mw-pmin-share">
                <module type="sharer" id="post-bottom-sharer" class="py-3" />
            </div>
        </div>
    </div>
</div>

<style>
/* Minimal post skin. Scoped to .mw-skin-pmin. */
.mw-skin-pmin { padding: 3.5rem 0 4.5rem; }
.mw-skin-pmin .mw-pmin-col { max-width: 680px; }
.mw-skin-pmin .mw-pmin-title { font-size: clamp(1.7rem, 3.5vw, 2.4rem); font-weight: 700; color: #182433; line-height: 1.2; margin-bottom: .4rem; }
.mw-skin-pmin .mw-pmin-date { color: #8a93a2; font-size: .9rem; margin-bottom: 2rem; }
.mw-skin-pmin .description { font-size: 1.08rem; line-height: 1.85; color: #273341; }
.mw-skin-pmin .mw-pmin-share { margin-top: 2.5rem; border-top: 1px solid #e6e9ef; padding-top: 1rem; }
</style>

@endsection
