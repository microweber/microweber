{{--
type: layout
name: Default
description: Default
--}}

<ul class="pagination d-flex justify-content-center align-items-center">
    @foreach ($pagination_links as $pagination_link)
        @if ($pagination_link['attributes']['current'])
            <li class="page-item active">
                <a class="page-link">
                    {{ $pagination_link['title'] }}
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $pagination_link['attributes']['href'] }}">
                    {{ $pagination_link['title'] }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
