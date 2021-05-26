@foreach($categories as $category)
    <ul>
        <li>
            <a href="?category={{$category->id}}" @if(\Request::get('category', false) == $category->id) class="active" @endif > {{$category->title}}</a>
        </li>
        @if($category->children()->count() > 0)
            @include('blog::partials.categories_children', ['categories' => $category->children])
        @endif
    </ul>
@endforeach
