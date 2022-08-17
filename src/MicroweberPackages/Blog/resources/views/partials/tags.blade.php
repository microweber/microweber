<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-bs-toggle="collapse" data-bs-target="#collapse_{{$randomId}}"  aria-expanded="true" class="d-flex">
        <h6><?php _e('Tags') ?></h6>
        <i class="mdi mdi-plus ms-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1">
        <div class="filter-tags">
            @foreach($tags as $tag)
                    <button class="btn btn-outline-primary btn-sm js-filter-tag m-1 @if($tag->active) active @endif" data-slug="{{$tag->slug}}">{{$tag->name}}</button>
            @endforeach
        </div>
    </div>
</div>
