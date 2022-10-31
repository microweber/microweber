<?php must_have_access(); ?>
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

    .mw-ui-table .selectpicker {
        background-color: transparent;
        border-color: transparent;
        width: 300px;
        height: 36px;
        resize: none;
    }

    .mw-ui-table .selectpicker:hover, .mw-ui-table .selectpicker:focus {
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
$developer_mode = Config::get('microweber.developer_mode');

if ($system_cache_adapter == false) {
    $system_cache_adapter = 'file';
}


$enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFontsAsString();


?>


<div id="sysconfig-form-<?php print $params['id'] ?>" onSubmit="return save_sysconf_form();" autocomplete="off">


    <?php
    /*   <div class="form-group">
        <label class="control-label"> <?php _e('Cache settings'); ?> </label>
        <?php if (!empty($cache_adapters)): ?>
        <select name="microweber[cache_adapter]" class="selectpicker" data-width="100%">
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
        <?php endif; ?>
    </div>*/

    ?>


    <div class="form-group">
        <label class="control-label"> <?php _e('Compile'); ?> api.js </label>
        <select name="microweber[compile_assets]" class="selectpicker" data-width="100%">
            <option value="0" <?php if ($compile_assets == 0): ?> selected <?php endif; ?> > <?php _e('No'); ?></option>
            <option value="1" <?php if ($compile_assets == 1): ?> selected <?php endif; ?> > <?php _e('Yes'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"> <?php _e('Force HTTPS'); ?>  </label>
        <select name="microweber[force_https]" class="selectpicker" data-width="100%">
            <option value="0" <?php if ($force_https == 0): ?> selected <?php endif; ?> > <?php _e('No'); ?></option>
            <option value="1" <?php if ($force_https == 1): ?> selected <?php endif; ?> > <?php _e('Yes'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"> <?php _e('Update Channel'); ?> </label>
        <select name="microweber[update_channel]" class="selectpicker" data-width="100%">
            <option value="stable" <?php if ($update_channel == 'stable'): ?> selected <?php endif; ?> > <?php _e('Stable'); ?></option>
            <option value="beta" <?php if ($update_channel == 'beta'): ?> selected <?php endif; ?> > <?php _e('Beta'); ?></option>
            <option value="dev" <?php if ($update_channel == 'dev'): ?> selected <?php endif; ?> > <?php _e('Dev'); ?></option>
            <option value="disabled" <?php if ($update_channel == 'disabled'): ?> selected <?php endif; ?> > <?php _e('Disable'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"> <?php _e('Developer Mode'); ?> </label>
        <select name="microweber[developer_mode]" class="selectpicker" data-width="100%">
            <option value="0" <?php if ($developer_mode == '0'): ?> selected <?php endif; ?> > <?php _e('Disabled'); ?></option>
            <option value="1" <?php if ($developer_mode == '1'): ?> selected <?php endif; ?> > <?php _e('Enabled'); ?></option>
        </select>

        <?php event_trigger('mw_admin_internal_settings', $params); ?>
    </div>

    <div class="form-group">
        <input type="button" value="Save" class="btn btn-success btn-sm" onclick="save_sysconf_form()"/>
    </div>
</div>
