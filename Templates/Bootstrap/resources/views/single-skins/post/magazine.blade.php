<?php

/*

type: layout
content_type: post
name: Post - Magazine
position: 2
description: Editorial full-bleed hero with overlaid title, author/date meta, drop-cap body and share.

*/

// Self-contained single-post layout (rendered directly by the resolver).
$post = get_content_by_id(content_id());
$picture = get_picture(content_id());
if (!$picture) {
    $picture = '';
}
$itemData = content_data(content_id());
$itemTags = content_tags(content_id());

$__magAuthor = '';
if (isset($post['created_by']) && $post['created_by'] && function_exists('user_name')) {
    $__magAuthor = (string) user_name($post['created_by']);
}
?>
@extends('templates.bootstrap::layouts.master')

@section('content')
<article class="mw-skin-mag" id="blog-content-{{ content_id() }}">

    @if($picture != '' && $picture != false)
        <header class="mw-mag-hero" style="background-image: linear-gradient(to bottom, rgba(10,16,26,.15), rgba(10,16,26,.78)), url('{{ $picture }}');">
            <div class="container">
                <div class="mw-mag-hero-inner">
                    @if(!empty($itemTags) && is_array($itemTags))
                        <span class="mw-mag-kicker">{{ is_array($itemTags) ? implode(' · ', array_slice($itemTags, 0, 2)) : $itemTags }}</span>
                    @endif
                    <h1 class="mw-mag-title">{{ $post['title'] }}</h1>
                    <div class="mw-mag-meta">
                        @if($__magAuthor !== '')<span>{{ $__magAuthor }}</span><span class="mw-mag-dot">•</span>@endif
                        <span>{{ date('d M Y', strtotime($post['created_at'])) }}</span>
                    </div>
                </div>
            </div>
        </header>
    @else
        <header class="mw-mag-hero mw-mag-hero--plain">
            <div class="container">
                <div class="mw-mag-hero-inner">
                    <h1 class="mw-mag-title">{{ $post['title'] }}</h1>
                    <div class="mw-mag-meta">
                        @if($__magAuthor !== '')<span>{{ $__magAuthor }}</span><span class="mw-mag-dot">•</span>@endif
                        <span>{{ date('d M Y', strtotime($post['created_at'])) }}</span>
                    </div>
                </div>
            </div>
        </header>
    @endif

    <div class="container">
        <div class="mw-mag-body col-lg-8 mx-auto">
            <div class="description edit dropcap typography-area" field="content" rel="content" data-mwplaceholder="Your text here">


            </div>
            <div class="mw-mag-share">
                <module type="sharer" id="post-bottom-sharer" class="py-3" />
            </div>
        </div>
    </div>
</article>

<style>
/* Magazine post skin. Scoped to .mw-skin-mag. */
.mw-skin-mag .mw-mag-hero { background-size: cover; background-position: center; color: #fff; display: flex; align-items: flex-end; min-height: 56vh; padding: 3.5rem 0; }
.mw-skin-mag .mw-mag-hero--plain { background: #182433; min-height: auto; padding: 4rem 0 2.5rem; }
.mw-skin-mag .mw-mag-hero-inner { max-width: 820px; }
.mw-skin-mag .mw-mag-kicker { display: inline-block; text-transform: uppercase; letter-spacing: .12em; font-size: .72rem; font-weight: 700; background: var(--mw-primary-color, #0d6efd); color: #fff; padding: .25rem .7rem; border-radius: 3px; margin-bottom: 1rem; }
.mw-skin-mag .mw-mag-title { font-size: clamp(1.9rem, 4.5vw, 3.1rem); line-height: 1.1; font-weight: 800; margin: 0 0 1rem; text-shadow: 0 2px 20px rgba(0,0,0,.25); }
.mw-skin-mag .mw-mag-meta { font-size: .92rem; opacity: .92; display: flex; gap: .6rem; align-items: center; }
.mw-skin-mag .mw-mag-dot { opacity: .6; }
.mw-skin-mag .mw-mag-body { padding: 3rem 0 4rem; font-size: 1.08rem; line-height: 1.85; color: #273341; }
.mw-skin-mag .mw-mag-share { margin-top: 2rem; border-top: 1px solid #e6e9ef; padding-top: 1rem; }
</style>

@endsection
