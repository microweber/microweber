<?php
/*
type: layout
name: Clean
description: Clean
*/
?>
<?php if (!empty($supported_languages)): ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-switch-language-dropdown').on('change', function () {

            var selected = $(this).val();
            var is_admin = <?php if (defined('MW_FRONTEND')) { echo 0; } else { echo 1; } ?>;

            $.post(mw.settings.api_url + "multilanguage/change_language", { locale: selected, is_admin: is_admin })
                .done(function(data) {
                    if (data.refresh) {
                        if (data.location) {
                            window.location.href = data.location;
                        } else {
                            location.reload();
                        }
                    }
                });
        });
    });
</script>

<select name="switch-language" class="form-control js-switch-language-dropdown">


    <option value="<?php echo $current_language['locale']; ?>">
    <?php if (!empty($current_language['display_name'])): ?>
        <?php echo $current_language['display_name']; ?>
    <?php else: ?>
        <?php echo $current_language['language']; ?>
    <?php endif; ?>
    </option>


    <?php foreach($supported_languages as $language): ?>
        <option value="<?php echo $language['locale']; ?>">

            <?php if (!empty($language['display_name'])): ?>
                <?php echo $language['display_name']; ?>
            <?php else: ?>
                <?php echo $language['language']; ?>
            <?php endif; ?>

        </option>
    <?php endforeach; ?>
</select>
<?php endif; ?>
