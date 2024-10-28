<?php

if (isset($action) and $action == 'popup') { ?>

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
