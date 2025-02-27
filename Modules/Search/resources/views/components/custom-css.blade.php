@php

    if (isset($searchWidth)  and $searchWidth) {
        $searchWidth = (intval($searchWidth)) . 'px!important;';
    }

     if (isset($searchHeight)  and $searchHeight) {
        $searchHeight = (intval($searchHeight)) . 'px!important;';
    }

    if (isset($searchPosition) and $searchPosition) {
        $searchPosition = $searchPosition . '!important;';
    }


@endphp

<style>
    #{{ $params['id'] }} .module-search-wrapper .input-group {
        width: {{$searchWidth}};
        height: {{$searchHeight}};
    }

    #{{ $params['id'] }} .module-search-wrapper .search-form {
        display: flex;
        justify-content: {{$searchPosition}};
    }

    #{{ $params['id'] }} .module-search-wrapper input {
        text-indent: 20px;
    }
</style>
