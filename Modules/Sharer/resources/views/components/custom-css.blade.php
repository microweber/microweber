@php

    if (isset($iconSize)  and $iconSize) {
        $iconSize = (intval($iconSize)) . 'px!important;';
    }

    if (isset($iconColor) and $iconColor) {
        $iconColor = $iconColor . '!important;';
    }

    if (isset($iconHoverColor) and $iconHoverColor) {
            $iconHoverColor = $iconHoverColor . '!important;';
        }

    if (isset($iconSpacing) and $iconSpacing) {
        $iconSpacing = $iconSpacing . 'px!important;';
    }

@endphp

<style>
    #{{ $params['id'] }} .mw-social-share-links a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
        margin-inline: {{$iconSpacing}};

        &:hover {
            color: {{$iconHoverColor}};
        }
    }

    /* Copy-link is a plain icon button (no pill/outline) matching the social
       icons: same size/color/spacing, link icon that swaps to a checkmark on copy. */
    #{{ $params['id'] }} .mw-share-copy-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 0;
        padding: 0;
        line-height: 0;
        cursor: pointer;
        text-decoration: none;
        /* no `color: inherit` — let it keep the <a> link colour so the icon
           matches the social icons; the svg rule below still honours a
           configured iconColor when one is set. */
    }
    #{{ $params['id'] }} .mw-share-copy-link svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
        margin-inline: {{$iconSpacing}};
    }
    #{{ $params['id'] }} .mw-share-copy-link:hover svg {
        color: {{$iconHoverColor}};
    }
    #{{ $params['id'] }} .mw-share-copy-link-icon,
    #{{ $params['id'] }} .mw-share-copy-link-check {
        display: inline-flex;
    }
    #{{ $params['id'] }} .mw-share-copy-link-check {
        display: none;
    }
    #{{ $params['id'] }} .mw-share-copy-link--copied .mw-share-copy-link-icon {
        display: none;
    }
    #{{ $params['id'] }} .mw-share-copy-link--copied .mw-share-copy-link-check {
        display: inline-flex;
    }
    #{{ $params['id'] }} .mw-share-copy-link--copied .mw-share-copy-link-check svg {
        color: #16a34a;
    }

</style>
