<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require('forms.js', true);
</script>
<script type="text/javascript">


    function save_sysconf_form() {


        mw.form.post('#sysconfig-form-<?php print $params['id'] ?>', '<?php print api_link('mw_save_framework_config_file'); ?>',
            function (msg) {
                mw.notification.msg(this);
                return false;
            });
        return false;

    }


</script>

<?php _e('Internal settings'); ?>
<div class="mw_clear"></div>
<style>
    .send-your-sysconf {
        float: right;
        width: 190px;
        text-align: center;
        margin-top: -77px;
    }

    .send-your-sysconf label {
        text-align: center;
    }

    .send-your-sysconf a {
        width: 175px;
        margin: auto
    }

    .mw-ui-table .mw-ui-field {
        background-color: transparent;
        border-color: transparent;
        width: 300px;
        height: 36px;
        resize: none;
    }

    .mw-ui-table .mw-ui-field:hover, .mw-ui-table .mw-ui-field:focus {
        background-color: white;
        border-color: #C6C6C6 #E6E6E6 #E6E6E6;
        resize: vertical;
    }

</style>
<?php


$cache_adapters = array();
$cache_adapters[] = array('title' => 'Auto', 'adapter' => 'auto');
$cache_adapters[] = array('title' => 'Files', 'adapter' => 'file');
$cache_adapters[] = array('title' => 'Apc', 'adapter' => 'apc');
$cache_adapters[] = array('title' => 'Xcache', 'adapter' => 'xcache');
$cache_adapters[] = array('title' => 'Memcached', 'adapter' => 'memcached');

$system_cache_adapter = Config::get('microweber.cache_adapter');
$compile_assets = Config::get('microweber.compile_assets');
$force_https = Config::get('microweber.force_https');
$update_channel = Config::get('microweber.update_channel');

if ($system_cache_adapter == false) {
    $system_cache_adapter = 'file';
}


?>

<?php

$enabled_custom_fonts = get_option("enabled_custom_fonts", "template");


?>


<div id="sysconfig-form-<?php print $params['id'] ?>" onSubmit="return save_sysconf_form();" autocomplete="off">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"> <?php _e('Cache settings'); ?> </label>
        <?php if (!empty($cache_adapters)): ?>
        <select name="microweber[cache_adapter]" class="mw-ui-field">
            <?php foreach ($cache_adapters as $cache_adapter): ?>
                <?php if (isset($cache_adapter['title']) and isset($cache_adapter['adapter'])): ?>
                    <option value="<?php print $cache_adapter['adapter'] ?>"
                        <?php if (isset($system_cache_adapter) and is_string($system_cache_adapter) and
                            $cache_adapter['adapter'] == $system_cache_adapter
                        ): ?> selected <?php endif; ?>
                    >
                        <?php print  $cache_adapter['title'] ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

    </div>
    <div class="mw-ui-field-holder">


        <?php endif; ?>
        <label class="mw-ui-label"> <?php _e('Compile'); ?> api.js </label>
        <select name="microweber[compile_assets]" class="mw-ui-field">
            <option value="0" <?php if ($compile_assets == 0): ?> selected <?php endif; ?> > <?php _e('No'); ?></option>
            <option value="1" <?php if ($compile_assets == 1): ?> selected <?php endif; ?> > <?php _e('Yes'); ?></option>
        </select>

    </div>
    <div class="mw-ui-field-holder">

        <label class="mw-ui-label"> Force HTTPS </label>
        <select name="microweber[force_https]" class="mw-ui-field">
            <option value="0" <?php if ($force_https == 0): ?> selected <?php endif; ?> > <?php _e('No'); ?></option>
            <option value="1" <?php if ($force_https == 1): ?> selected <?php endif; ?> > <?php _e('Yes'); ?></option>
        </select>

    </div>
    <div class="mw-ui-field-holder">

        <label class="mw-ui-label"> Update Channel </label>
        <select name="microweber[update_channel]" class="mw-ui-field">
            <option value="stable" <?php if ($update_channel == 'stable'): ?> selected <?php endif; ?> > <?php _e('Stable'); ?></option>
            <option value="beta" <?php if ($update_channel == 'beta'): ?> selected <?php endif; ?> > <?php _e('Beta'); ?></option>
            <option value="disabled" <?php if ($update_channel == 'disabled'): ?> selected <?php endif; ?> > <?php _e('Disable'); ?></option>
        </select>

        <?php event_trigger('mw_admin_internal_settings', $params); ?>
        <br/>
        <br/>
        <input type="button" value="Save" class="mw-ui-btn" onclick="save_sysconf_form()"/>
    </div>
</div>
