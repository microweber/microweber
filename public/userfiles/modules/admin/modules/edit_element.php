<?php
$rand = uniqid();
$id = false;
if (isset($params["data-module-id"])) {
    $id = $params["data-module-id"];

}
$data = array();
if ($id != false) {

    $data = mw()->layouts_manager->get('limit=1&id=' . $id);
    if (isset($data[0])) {
        $data = $data[0];
    }
}

?>
<?php if (!empty($data)): ?>

    <script type="text/javascript">

        mw.require('forms.js');

        $(document).ready(function () {
            mw.$('#module_admin_settings_form_<?php echo $rand;?>').submit(function () {
                mw.form.post(mw.$('#module_admin_settings_form_<?php echo $rand;?>'), '<?php print site_url('api') ?>/layouts/save', function () {
                    // mw.reload_module('[data-type="categories"]');
                    // mw.reload_module('[data-type="pages"]');
                });

                return false;
            });
        });
    </script>

    <form id="module_admin_settings_form_<?php echo $rand;?>">
        <?php if (isset($data['icon'])): ?>
            <img class="w-100 border border-dark" src="<?php print $data['icon'] ?>">
        <?php endif; ?>

        <div>
            <div class="d-block my-1">
                <?php if (isset($data['name'])): ?>
                    <?php _e('Name: '); ?><?php print $data['name'] ?>
                <?php endif; ?>
            </div>

            <div class="d-block my-1">
                <?php if (isset($data['description'])): ?>
                    <?php _e('Description: '); ?><?php print $data['description'] ?>
                <?php endif; ?>
            </div>

            <div class="d-block my-1">
                <?php if (isset($data['author'])): ?>
                    <?php _e('Author: '); ?><?php print $data['author'] ?>
                <?php endif; ?>
            </div>

            <div class="d-block my-1">
                <?php if (isset($data['website'])): ?>
                    <?php _e('Website: '); ?><?php print $data['website'] ?>
                <?php endif; ?>
            </div>

            <div class="d-block my-1">
                <?php if (isset($data['help'])): ?>
                    <?php _e('Help: '); ?><?php print $data['help'] ?>
                <?php endif; ?>
            </div>
        </div>
        <?php

        /*<input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
        <?php _e('installed'); ?> <input type="text" name="installed" value="<?php print $data['installed'] ?>"/>
        <?php _e('ui'); ?> <input type="text" name="ui" value="<?php print $data['ui'] ?>"/>
        <?php _e('position'); ?>
        <input type="text" name="position" value="<?php print $data['position'] ?>"/>

        <module type="categories/selector" rel="elements" rel_id="<?php print $data['id'] ?>">
            <input name="save" type="submit" value="save">*/
        ?>
    </form>
<?php endif; ?>
