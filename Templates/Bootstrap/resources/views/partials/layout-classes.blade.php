{{--
    Reusable partial: compute padding/layout classes for layout skins.

    DO NOT @include this to obtain $layout_classes. @include renders in its OWN
    variable scope, so the $layout_classes it assigns here does NOT propagate back
    to the including view (padding defaults are silently lost and the parent sees
    an undefined variable). Compute padding INLINE instead — see
    partials/layout-section.blade.php and footers/skin-1.blade.php. This file is
    kept only as the documented reference for that computation.

    Expects $classes array and optionally $layout_classes, $defaultPaddingTop, $defaultPaddingBottom.
--}}
@php
    if (empty($classes['padding_top'])) {
        $classes['padding_top'] = $defaultPaddingTop ?? '';
    }
    if (empty($classes['padding_bottom'])) {
        $classes['padding_bottom'] = $defaultPaddingBottom ?? '';
    }
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp