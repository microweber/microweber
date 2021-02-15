<?php must_have_access(); ?>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('.<?php print $config['module_class'] ?>', function(){
            mw.clear_cache();
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");

        });
    });
</script>
<?php
$default_vars = [
    'white' => '#fff',
    'black' => '#000',
    'silver' => '#bcbfc2',

    'primary' => '#4592ff',
    'secondary' => '#eeefef',
    'success' => '#3dc47e',
    'info' => '#e1f1fd',
    'warning' => '#ffc107',
    'danger' => '#ff4f52',
    'light' => '#f8f9fa',
    'dark' => '#2b2b2b',

    'body-bg' => '#fff',
    'body-color' => '#212529'
];
$schemes = ['litera','cosmo'];
?>
<?php $selected_admin_scheme =  get_option('admin_color_scheme_name', 'website');

 ?>
<div class="form-group">
    <label class="control-label" for="admin_color_scheme_name">Color Scheme</label>
    <select name="admin_color_scheme_name" class="form-control js-color-admin-change mw_option_field" id="admin_color_scheme_name" option-group="website">
        <option value="">none</option>
<?php foreach ($schemes as $scheme_name){ ?>

    <option value="<?php print $scheme_name ?>" <?php if($selected_admin_scheme == $scheme_name) {?> selected <?php } ?> ><?php print $scheme_name ?></option>


<?php } ?>

    </select>
</div>