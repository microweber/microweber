@foreach($categories as $category)
    <ul style="margin-top:15px;">
        <li>
            <a href="?category={{$category->id}}">{{$category->title}}</a>
        </li>
        @if($category->children()->count() > 0)
            @include('blog::partials.categories_children', ['categories' => $category->children])
        @endif
    </ul>
@endforeach
