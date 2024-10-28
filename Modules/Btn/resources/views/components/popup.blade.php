<?php

if ($action == 'popup') { ?>

<script type="text/microweber" id="area<?php print $btn_id; ?>">
        <?php print $action_content; ?>
</script>

<script>
    function <?php print $popup_function_id ?>() {
        mw.dialog({
            name: 'frame<?php print $btn_id; ?>',
            content: $(document.getElementById('area<?php print $btn_id; ?>')).html(),
            template: 'basic',
            title: "<?php print addslashes($text); ?>"
        });
    }
</script>
<?php }
