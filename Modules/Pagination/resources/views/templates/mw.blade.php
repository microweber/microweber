{{--
type: layout
name: Microweber
description: Microweber pagination
--}}

<div class="mw-paging d-flex justify-content-center">
    @foreach ($pagination_links as $pagination_link)
        @if ($pagination_link['attributes']['current'])
            <a class="active">
                {{ $pagination_link['title'] }}
            </a>
        @else
            <a href="{{ $pagination_link['attributes']['href'] }}">
                {{ $pagination_link['title'] }}
            </a>
        @endif
    @endforeach
</div>
