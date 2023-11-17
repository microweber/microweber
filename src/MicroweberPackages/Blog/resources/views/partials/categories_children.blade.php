@foreach($categories as $category)
    <ul class="mw-shop-attributes-li list-unstyled" style="margin-left:10px;">
        <li>
            <a href="?category={{$category->id}}" @if($request->get('category', false) == $category->id) class="active" @endif > {{$category->title}}</a>
        </li>
        @if($category->children()->count() > 0)
            @include('blog::partials.categories_children', ['categories' => $category->children])
        @endif
    </ul>
@endforeach
