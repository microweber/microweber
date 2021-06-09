<?php
$randomId = uniqid();
?>

<div class="card-header bg-white">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h6 class="title"><?php _e('Tags') ?></h6>
        <i class="mdi mdi-plus ml-auto" style="font-size: 21px;"  ></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        <div class="filter-tags">
            @foreach($tags as $tag)
                <a href="#" class="js-filter-tag @if($tag->active) active @endif" data-slug="{{$tag->slug}}">{{$tag->name}}</a>
            @endforeach
        </div>
    </div>
</div>
