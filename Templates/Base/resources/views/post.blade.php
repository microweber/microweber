<?php
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
    <div class="blog-inner-page py-5" id="blog-content-{{ content_id() }}">
        <div class="container mw-m-t-30 mw-m-b-50">
            <div class="row">
                @if($picture != '' && $picture != false)
                <div class="post-featured-image-holder mb-4">
                    {!! responsive_thumbnail($picture, 1200, 650, [
                        'class' => 'img-fluid w-100',
                        'alt' => $post['title'] ?? '',
                    ]) !!}
                </div>
                @endif

                <h1 class="mt-5 text-center text-dark">{{ $post['title'] }}</h1>
                <p class="text-dark text-center">{{ date('d M Y', strtotime($post['created_at'])) }}</p>

                <div class="col-md-10 col-12 mx-auto">
                    <div class="description edit dropcap typography-area" field="content" rel="content" data-mwplaceholder="Your text here">
                    </div>
                    <module type="sharer" id="post-bottom-sharer" class="py-3 float-start" />
                </div>
            </div>
        </div>
    </div>
@endsection