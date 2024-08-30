<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {

            mw.clear_cache();
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");

        });
    });
</script>

<div class="mw-ui-box mw-ui-box-content mw-ui-box-warn"><?php _e("Those settings are experimental and may lead to bugs. Please don't use them yet"); ?></div>
<div class="<?php print $config['module_class'] ?>">
    <div class="mw-ui-field-holder" style="display: none">
        <label class="form-label">
            <?php _e("Open module settings in sidebar on live edit"); ?>
        </label>
        <?php
        $open_module_settings_in_sidebar = get_option('open_module_settings_in_sidebar', 'live_edit');
        ?>
        <select name="open_module_settings_in_sidebar" class="form-select  mw_option_field" type="text" option-group="live_edit">
            <option value="0" <?php if (!$open_module_settings_in_sidebar): ?> selected="selected" <?php endif; ?>>
                <?php _e("No"); ?>

            </option>
            <option value="1" <?php if ($open_module_settings_in_sidebar): ?> selected="selected" <?php endif; ?>>
                <?php _e("Yes"); ?>
            </option>
        </select>
    </div>




    <div class="mw-ui-field-holder mt-3">
        <label class="form-label"><?php _e('Cache settings'); ?></label>
        <div class="mw-notification"><?php _e("Note: Those features are experimental and not fully tested. Check if your website is working normally after enabling cache settings."); ?></div>
    </div>

    <div class="form-check form-switch my-3" style="width: 100%;">
        <?php $optimize_asset_loading = get_option('optimize_asset_loading', 'website'); ?>

        <label class="form-label d-block">
            <input class="form-check-input mw_option_field me-2" type="checkbox" id="img_resize_choice1" name="optimize_asset_loading" data-value-checked="y" data-value-unchecked="n" <?php if ($optimize_asset_loading == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
            <span class="form-check-label"><?php _e("Optimize assets loading"); ?></span>
        </label>
    </div>


    <div class="form-check form-switch my-3" style="width: 100%;">
        <?php $enable_full_page_cache = get_option('enable_full_page_cache', 'website'); ?>

        <label class="form-label d-block">
            <input class="form-check-input mw_option_field me-2" type="checkbox"   name="enable_full_page_cache" data-value-checked="y" data-value-unchecked="n" <?php if ($enable_full_page_cache == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
            <span class="form-check-label"><?php _e("Enable full page cache"); ?></span>
        </label>
    </div>


  <div class="my-4">
      <label class="form-label d-flex"><?php _e("Static files delivery method"); ?></label>
      <?php $static_files_delivery_method = get_option('static_files_delivery_method', 'website'); ?>

      <select id="js-static_files_delivery_method_select" name="static_files_delivery_method" class="form-select mw_option_field w-100" type="text" option-group="website">
          <option value="" <?php if (!$static_files_delivery_method): ?> selected="selected" <?php endif; ?>><?php _e("Default"); ?></option>
          <option value="cdn_domain" <?php if ($static_files_delivery_method == 'cdn_domain'): ?> selected="selected" <?php endif; ?>><?php _e("CDN Domain"); ?></option>
          <option value="content_proxy" <?php if ($static_files_delivery_method == 'content_proxy'): ?> selected="selected" <?php endif; ?>><?php _e("Content proxy (experimental)"); ?></option>
      </select>

      <div class="js-toggle-content-proxy-settings" <?php if (!$static_files_delivery_method): ?> style="display: none" <?php endif; ?> >

          <div class="mw-ui-box mw-ui-box-content mw-ui-box m-b-20">
              <?php _e("Warning, this is advanced action and may break your site."); ?> <br />
              <?php _e("Make sure you setup you domain to resolve to your website."); ?> <br />
              <?php _e("After that you can enter your content delivery domain name for example cdn.mydomain.com"); ?>
          </div>

          <div class="mw-ui-field-holder">
              <label class="form-label"><?php _e("CDN Domain name"); ?></label>
              <?php $key_name = 'static_files_delivery_method_domain'; ?>
              <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
          </div>
      </div>

  </div>


    <div class="form-check form-switch my-3" style="width: 100%;">
        <?php $use_google_fonts_proxy = get_option('use_google_fonts_proxy', 'template'); ?>

        <label class="form-label d-block">
            <input class="form-check-input mw_option_field me-2" type="checkbox"   name="use_google_fonts_proxy" data-value-checked="y" data-value-unchecked="n" <?php if (!$use_google_fonts_proxy or $use_google_fonts_proxy != '1'): ?> checked <?php endif; ?> value="0" option-group="template">

            <span class="form-check-label"><?php _e("Use Google Fonts proxy?"); ?></span>
        </label>
    </div>

           <module type="settings/group/ui_colors_admin"/>
</div>



