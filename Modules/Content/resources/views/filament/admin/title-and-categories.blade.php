<div class="flex flex-col w-full">

    <div class="font-medium w-full overflow-hidden">
        <a href="{{$content->editLink()}}" class="block truncate">
            {{$content->title}}
        </a>

        @if($content->is_home)

            <span class="mx-1">
           {{ svg('heroicon-o-home', 'inline-block w-4 h-4 text-gray-500') }}
        </span>

        @endif


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

                        <button 
                            type="button"
                            class="text-blue-500 text-[0.8rem] hover:text-blue-700 hover:underline cursor-pointer transition-colors duration-200"
                            x-on:click="$wire.set('tableFilters.category_id.value', {{ $category->parent->id }}); $wire.$refresh()"
                            title="Filter by category: {{ $category->parent->title }}"
                        >
                            {{$category->parent->title}}
                        </button>

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

        <div class="mw-content-meta flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
            @if($content->content_type === 'post' && $content->created_by)
                <span class="mw-content-author text-[0.7rem]">
                    {{ svg('heroicon-m-user', 'inline-block w-3 h-3 mr-0.5 align-[-2px]') }}
                    {{ user_name($content->created_by) }}
                </span>
            @endif

            @if($content->content_type === 'post' && $content->posted_at)
                <span class="mw-content-date text-[0.6rem]">
                    {{ _e("Published") }}: {{ $content->posted_at->format('M d, Y') }}
                </span>
            @elseif($content->updated_at)
                <span class="mw-content-date text-[0.6rem]">
                    {{ _e("Updated") }}: {{ $content->updated_at->format('M d, Y') }}
                </span>
            @endif
        </div>

    </div>
</div>
