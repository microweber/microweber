<?php
$randomId = uniqid();
?>


<h6><?php _e('Tags') ?></h6>


<div class="card-body px-1">
    <div class="filter-tags">
        @foreach($tags as $tag)
                <button class="btn btn-outline-primary btn-sm js-filter-tag m-1 @if($tag->active) active @endif" data-slug="{{$tag->slug}}">{{$tag->name}}</button>
        @endforeach
    </div>
</div>

