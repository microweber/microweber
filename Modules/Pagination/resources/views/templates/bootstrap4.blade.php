{{--
type: layout
name: Bootstrap 4
description: Bootstrap 4 pagination
--}}

<ul class="pagination justify-content-center">
    @foreach ($pagination_links as $pagination_link)
        @if ($pagination_link['attributes']['current'])
            <li class="page-item active">
                <span class="page-link">
                    {{ $pagination_link['title'] }}
                    <span class="sr-only">@lang('Current')</span>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" data-page-number-dont-copy="{{ $pagination_link['attributes']['data-page-number'] }}" href="{{ $pagination_link['attributes']['href'] }}">
                    {{ $pagination_link['title'] }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
