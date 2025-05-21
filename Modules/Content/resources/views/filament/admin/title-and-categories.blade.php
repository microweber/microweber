<div class="flex flex-col w-full">

    <div class="font-medium w-full">
        <a href="{{$content->editLink()}}">
        {{$content->title}}
        </a>
    </div>

    @php
        $parentPages = app()->content_manager->get_parents($content->id);
    @endphp

    @if(!empty($parentPages))
        <div class="text-[0.8rem]">
            @foreach ($parentPages as $parentPageId)
                <a class="">
                    {{content_title($parentPageId)}}
                </a>
                @if(!$loop->last)
                    <span class="mx-1">/</span>
                @endif
            @endforeach
        </div>
    @endif

    <div>


        @if($content->categories->count() > 0)
            <span>
            @php
                $iCategory = 0;
            @endphp
                @foreach($content->categories as $category)
                    @if($category->parent)

                        <div class="text-blue-500 text-[0.8rem]">
                        {{$category->parent->title}}
                     </div>

                        @php
                            $iCategory++;
                            if ($content->categories->count() !== $iCategory) {
                                echo ", ";
                            }
                        @endphp

                    @endif
                @endforeach
         </span>
        @endif

        @if($content->updated_at)
            <div class="text-[0.6rem]">
                {{ _e("Updated") }}: {{$content->updated_at->format('M d, Y, h:i A')}}
            </div>
        @endif

    </div>
</div>
