<?php
$randomId = uniqid();
?>

<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title"><?php _e('Tags') ?></h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        <div class="tags">
        @foreach($tags as $tag)
            <a href="{{$tag->url}}" @if(\Request::get('tags', false) == $tag->slug) style="border:3px solid #43a90c;" @endif>{{$tag->name}}</a>
        @endforeach
        </div>
    </div>
</div>
