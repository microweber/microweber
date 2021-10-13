<?php if (!isset($params['live_edit'])): ?>
    <?php // include($config['path_to_module'] . 'admin_backend.php'); ?>
<?php else: ?>
    <?php // include($config['path_to_module'] . 'admin_live_edit.php'); ?>
<?php endif; ?>

<script>
        mw.lib.require('multilanguage');

        $(document).ready(function () {
        $('#js-field-4').mlInput({
            name: 'bojkata',
            currentLocale: 'ar_SA',
            locales: ['bg_BG', 'en_US', 'ar_SA'],
            translations: <?php echo json_encode([
                'bg_BG' => 'Текст на български',
                'ar_SA' => 'ARABSKI BRAT',
                'en_US' => 'Text on English'
            ]);
            ?>,
        });
    });
</script>

<input type="text" class="form-control" id="js-field-4" />
