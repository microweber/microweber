@foreach($categories as $category)
    <ul style="margin-left:6px;margin-top:15px;">
        <li>
            <a href=""> <i class="fa fa-arrow-right"></i> {{$category->title}}</a>
        </li>
        @if($category->children()->count() > 0)
            @include('blog::partials.categories_children', ['categories' => $category->children])
        @endif
    </ul>
@endforeach
