@php
    $breacrumb_params = [];

    if (isset($params['current-page-as-root'])) {
        $breacrumb_params['current-page-as-root'] = $params['current-page-as-root'];
    }

    $selected_start_depth = get_option('data-start-from', $params['id']);
    if ($selected_start_depth) {
        $breacrumb_params['start_from'] = $selected_start_depth;
    }

    $data = breadcrumb($breacrumb_params);
@endphp

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ $homepage['url'] }}">{{ $homepage['title'] }}</a>
        </li>
        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
