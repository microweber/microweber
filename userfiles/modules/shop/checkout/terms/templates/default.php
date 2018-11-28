<?php

/*

type: layout

name: Default

description: Default terms template

*/

?>

<?php if ($terms): ?>
    <script>
        $(document).ready(function () {
            $('#i_agree_with_terms_row').click(function () {
                var el = $('#i_agree_with_terms');
                if (el.is(':checked')) {
                    $('#complete_order_button').removeAttr('disabled');
                } else {
                    $('#complete_order_button').attr('disabled', 'disabled');

                }
            });
        });
    </script>

    <div class="mw-ui-row" id="i_agree_with_terms_row">
        <label class="mw-ui-check">
            <input type="checkbox" name="terms" id="i_agree_with_terms" value="1" autocomplete="off"/>
            <span></span>
            <span>
                <?php _e('I agree with the'); ?>
                <a href="<?php print site_url('terms-and-conditions') ?>" target="_blank"><?php _e('Terms and Conditions'); ?></a>
            </span>
        </label>
    </div>
<?php endif; ?>