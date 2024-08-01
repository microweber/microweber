<div class="flex flex-col gap-2 w-full">

    <div class="font-medium">
        {{$content->title}}
    </div>

    @php
        $parentPages = app()->content_manager->get_parents($content->id);
    @endphp

    @if(!empty($parentPages))
        <div class="text-muted">
            @foreach ($parentPages as $parentPageId)
                <a onclick="Livewire.dispatch('selectPageFromTableList', {{$parentPageId}});return false;" href="#"
                   class="my-1 d-block text-muted mw-products-breadcrumb">
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

                        <div>
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
            <div>
                <small>
                    {{ _e("Updated") }}: {{$content->updated_at->format('M d, Y, h:i A')}}
                </small>
            </div>
        @endif

    </div>
</div>
