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
