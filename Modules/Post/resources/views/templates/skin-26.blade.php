@php
/*

type: layout

name: Post Cards

description: Posts using x-post-card component

*/
@endphp

<x-row class="g-4" id="posts-{{ $params['id'] }}">
    @if(empty($data))
        <p class="mw-pictures-clean">No posts added. Please add posts to the module.</p>
    @else
        @foreach($data as $item)
            <x-col size="12" size-md="6" size-lg="4">
                <x-post-card
                    :title="$item['title'] ?? ''"
                    :description="\Illuminate\Support\Str::limit($item['description'] ?? '', 150)"
                    :image="$item['image'] ?? ''"
                    :link="$item['link'] ?? ''"
                    :date="isset($item['created_at']) ? date_system_format($item['created_at']) : ''"
                    :read-more-text="$read_more_text ?? __('Read more')"
                    class="shadow-sm"
                    itemscope
                    itemtype="{{ $schema_org_item_type_tag }}"
                />
            </x-col>
        @endforeach
    @endif
</x-row>

@if(isset($pages_count) && $pages_count > 1 && isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif