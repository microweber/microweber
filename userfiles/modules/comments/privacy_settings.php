<?php only_admin_access(); ?>


<script type="text/javascript">
    mw.require('options.js', true);
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>

<div class="mw-ui-row">
    <div class="mw-ui-col">
        <div class="mw-ui-col-container">
            <div class="comments-main-settings">


                <div class="mw-ui-field-holder">
                    <label class="mw-ui-check">
                        <input
                                type="checkbox"
                                parent-reload="true"
                                name="require_terms"

                                value="y"
                                class="mw_option_field"
                                option-group="comments"
                            <?php if (get_option('require_terms', 'comments') == 'y'): ?>   checked="checked"  <?php endif; ?>
                        />
                        <span></span><span><?php _e("Users must agree to Terms and Conditions"); ?></span> </label>
                </div>
            </div>

        </div>
    </div>
</div>
