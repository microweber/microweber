<?php
$randomId = uniqid();
?>

<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title"><?php _e('Categories') ?></h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">

        @foreach($categories as $category)
            <ul class="filter-tree">
                <li>
                    <a href="?category={{$category->id}}" @if(\Request::get('category', false) == $category->id) class="active" @endif >{{$category->title}}</a>
                </li>
                @if($category->children()->count() > 0)
                    @include('blog::partials.categories_children', ['categories' => $category->children])
                @endif
            </ul>
        @endforeach

    </div>
</div>
