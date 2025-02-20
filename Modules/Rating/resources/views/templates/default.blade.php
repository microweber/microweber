<?php
/*
type: layout
name: Default
description: Default Rating Layout
*/
?>

<div class="module-rating module-rating-template-default">
    <div class="starrr" data-rel-type="{{ $rel_type }}" data-rel-id="{{ $rel_id }}"
         data-require-comment="{{ $require_comment ? 'true' : 'false' }}"></div>
    @if($rating != '_')
        <div class="rating-value">Current Rating: {{ $rating }}/5</div>
    @endif
</div>

<script>mw.moduleCSS("<?php print asset('modules/rating/css/rating.css'); ?>", true);</script>
<script>mw.moduleJS("<?php print asset('modules/rating/js/lib.js'); ?>", true);</script>
<script>mw.moduleJS("<?php print asset('modules/rating/js/rating.js'); ?>", true);</script>


