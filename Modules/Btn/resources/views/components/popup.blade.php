<?php

if (isset($action) and $action == 'popup') {
    // AI-56 / TICKET-CW (cycle-63 2026-05-08): defence-in-depth.
    // BtnModule::getViewData() already sanitises popupFunctionId to
    // [A-Za-z0-9_]+, but this view is also reachable via direct
    // include / cached compiled views, so we re-apply the sanitiser
    // at the render layer. btnId follows the same pattern (it gets
    // interpolated into element ids and JS string literals below).
    $popupFunctionId = preg_replace('/[^A-Za-z0-9_]/', '', (string) $popupFunctionId);
    $btnId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $btnId);
?>

<script type="text/microweber" id="popupContent<?php print $btnId; ?>">
        <?php print $popupContent; ?>
</script>

<script>
    function <?php print $popupFunctionId ?>() {
        mw.dialog({
            name: 'frame<?php print $btnId; ?>',
            content: $(document.getElementById('popupContent<?php print $btnId; ?>')).html(),
            template: 'basic',
            title: "<?php print addslashes($text); ?>"
        });
    }
</script>
<?php }
