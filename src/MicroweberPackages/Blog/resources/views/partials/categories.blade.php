<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="collapse-element d-flex">
        <h4 class="title"><?php _e('Categories') ?></h4>
        <i class="mdi mdi-plus ml-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1">
        @foreach($categories as $category)
            <ul class="js-filter-category-tree">
                <li class="pb-4">
                    <a href="?category={{$category->id}}" class="js-filter-category-link @if($request->get('category', false) == $category->id) active @endif ">{{$category->title}}</a>
                </li>
                @if($category->children()->count() > 0)
                    @include('blog::partials.categories_children', ['categories' => $category->children])
                @endif
            </ul>
        @endforeach

    </div>
</div>
