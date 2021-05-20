@foreach($categories as $category)
    <ul>
        <li><a href="">{{$category->title}}</a></li>

        @if($category->children()->count() > 0)
            @include('blog::partials.categories', ['categories' => $category->children])
        @endif

    </ul>
@endforeach
