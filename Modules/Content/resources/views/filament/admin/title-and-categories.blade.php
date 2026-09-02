<div class="flex flex-col w-full gap-0.5">

    {{-- Line 1: title (+ home indicator, inline so the svg stays constrained) --}}
    <div class="font-medium w-full overflow-hidden">
        <a href="{{$content->editLink()}}" class="truncate align-middle">{{$content->title}}</a>@if($content->is_home)<span class="mx-1 align-middle">{{ svg('heroicon-o-home', 'inline-block w-4 h-4 text-gray-400') }}</span>@endif
    </div>

    {{-- Line 2: a single dense meta row — breadcrumb · categories · date --}}
    @php
        $parentPages = app()->content_manager->get_parents($content->id);
    @endphp
    <div class="mw-content-meta flex flex-wrap items-center gap-x-2 gap-y-0 text-[0.7rem] leading-tight text-gray-500 dark:text-gray-400">

        @if(!empty($parentPages))
            <span class="truncate">
                @foreach ($parentPages as $parentPageId){{ content_title($parentPageId) }}@if(!$loop->last) / @endif @endforeach
            </span>
            <span class="text-gray-300 dark:text-gray-600">&middot;</span>
        @endif

        @if($content->categories->count() > 0)
            @php $iCategory = 0; @endphp
            @foreach($content->categories as $category)
                @if($category->parent)
                    <button
                        type="button"
                        class="text-[#0d6efd] hover:text-[#0a58ca] hover:underline cursor-pointer transition-colors duration-200"
                        x-on:click="$wire.set('tableFilters.category_id.value', {{ $category->parent->id }}); $wire.$refresh()"
                        title="Filter by category: {{ $category->parent->title }}"
                    >{{$category->parent->title}}</button>@php $iCategory++; @endphp @if($content->categories->count() !== $iCategory), @endif
                @endif
            @endforeach
            <span class="text-gray-300 dark:text-gray-600">&middot;</span>
        @endif

        @if($content->content_type === 'post' && $content->created_by)
            <span class="mw-content-author inline-flex items-center">
                {{ svg('heroicon-m-user', 'inline-block w-3 h-3 mr-0.5') }}{{ user_name($content->created_by) }}
            </span>
            <span class="text-gray-300 dark:text-gray-600">&middot;</span>
        @endif

        @if($content->content_type === 'post' && $content->posted_at)
            <span class="mw-content-date">{{ _e("Published") }}: {{ $content->posted_at->format('M d, Y') }}</span>
        @elseif($content->updated_at)
            <span class="mw-content-date">{{ _e("Updated") }}: {{ $content->updated_at->format('M d, Y') }}</span>
        @endif

    </div>
</div>
