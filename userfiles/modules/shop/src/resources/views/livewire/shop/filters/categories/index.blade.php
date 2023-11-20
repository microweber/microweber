<div class="mt-4 text-left">
    <div>Categories</div>
    <ul class="mt-2">
        @foreach($availableCategories as $category)
            <li wire:click="filterCategory({{$category->id}})">
                {{$category->title}}
            </li>
        @endforeach
    </ul>
</div>
