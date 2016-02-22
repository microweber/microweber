<?php only_admin_access(); ?>
<?php if (!have_license('modules/white_label')): ?>
<style>
.mw-lssssicense-key-activate {
	margin-top: 10%;
	margin-left: 30%;
	margin-right: 30%;
	width: auto;
}
</style>

<div class="module-live-edit-settings">
  <h2>Enter the license key to activate White Label</h2>
  <module type="admin/modules/activate" prefix="modules/white_label"/>
</div>
<?php return; ?>
<?php endif; ?>
<?php
$logo_admin = false;
$logo_live_edit = false;
$logo_login = false;
$powered_by_link = false;
$powered_by_link = false;
$brand_name = false;
$disable_marketplace = false;
$disable_powered_by_link = false;

$enable_service_links = true;
$admin_logo_login_link = false;

$settings = get_white_label_config();
if (isset($settings['logo_admin'])){
    $logo_admin = $settings['logo_admin'];
}
if (isset($settings['logo_live_edit'])){
    $logo_live_edit = $settings['logo_live_edit'];
}
if (isset($settings['logo_login'])){
    $logo_login = $settings['logo_login'];
}

if (isset($settings['admin_logo_login_link'])){
    $admin_logo_login_link = $settings['admin_logo_login_link'];
}


if (isset($settings['powered_by_link'])){
    $powered_by_link = $settings['powered_by_link'];
}
if (isset($settings['disable_marketplace']) and $settings['disable_marketplace']!=false){
    $disable_marketplace = $settings['disable_marketplace'];
}
if (isset($settings['disable_powered_by_link']) and $settings['disable_powered_by_link']!=false){
    $disable_powered_by_link = $settings['disable_powered_by_link'];
}

if (isset($settings['enable_service_links'])){
    $enable_service_links = $settings['enable_service_links'];
}

if (isset($settings['brand_name']) and $settings['brand_name']!=false){
    $brand_name = $settings['brand_name'];
}



?>
<script type="text/javascript">
    $(document).ready(function () {


        $("#white_label_settings_holder").submit(function () {

            var url = "<?php print api_url() ?>save_white_label_config"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                url: url,
                data: $("#white_label_settings_holder").serialize(), // serializes the form's elements.
                success: function (data) {
                    mw.notification.success("White label saved");
                }
            });

            return false; // avoid to execute the actual submit of the form.
        });


    });
</script> 
<script type="text/javascript">

    $(document).ready(function () {

        mw.$(".up").each(function () {

            var span = mwd.createElement('span');
            span.className = 'mw-ui-btn';
            span.innerHTML = 'Upload';
            $(this).after(span);
            var uploader = mw.uploader({
                filetypes: "images",
                multiple: false,
                element: span
            });

            uploader.field = this;

            $(uploader).bind("FileUploaded", function (obj, data) {

                uploader.field.value = data.src;
            });


        });


    });
</script>
<div class="module-live-edit-settings">
  <form id="white_label_settings_holder">
    <div class="mw-ui-box   ">
      <div class="mw-ui-box-header"> <span class="mw-icon-gear"></span><span>Branding</span> </div>
      <div class="mw-ui-box-content">
        <div class="mw-ui-row">
          <div class="mw-ui-col" style="width: 50%">
            <div class="mw-ui-col-container">
              <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Brand Name</label>
                <input
                name="brand_name"
                option-group="whitelabel"
                placeholder="Enter the name of your company"

                class="mw-ui-field w100"
                type="text"
                value="<?php print  $brand_name; ?>"
                />
              </div>
              <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Logo login link</label>
                <input
                name="admin_logo_login_link"
                option-group="whitelabel"
                placeholder="Enter website url of your company"

                class="mw-ui-field w100"
                type="text"
                value="<?php print  $admin_logo_login_link; ?>"
                />
              </div>
            </div>
          </div>
          <div class="mw-ui-col" style="width: 50%">
            <div class="mw-ui-col-container">
              <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Logo for Admin panel (36x36 px)</label>
                <input
                name="logo_admin"
                option-group="whitelabel"
                placeholder="Upload your logo"

                class="mw-ui-field up"
                type="text"
                value="<?php print  $logo_admin; ?>"
                />
              </div>
              <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Logo for Live-Edit toolbar</label>
                <input
                name="logo_live_edit"
                option-group="whitelabel"
                placeholder="Upload your logo"

                class="mw-ui-field up"
                type="text"
                value="<?php print  $logo_live_edit; ?>"
                />
              </div>
              <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Logo for Login screen (max width 290px)</label>
                <input
                name="logo_login"
                option-group="whitelabel"
                placeholder="Upload your logo"

                class="mw-ui-field up"
                type="text"
                value="<?php print  $logo_login; ?>"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mw-ui-row">
        <div class="mw-ui-col" style="width: 50%">
            <div class="mw-ui-col-container">
              <div class="mw-ui-field-holder"> 
                <script>
				$(document).ready(function () {
				
					mw.editor({
						element: '#powered_by_link_text',
						height: 'auto',
						hideControls: ['fontfamily','fontsize','image','format','alignment','ol','ul']
					});
				
				});
				</script>
                <label class="mw-ui-label">"Powered By" HTML</label>
                <textarea name="powered_by_link" id="powered_by_link_text" option-group="whitelabel"
                      placeholder="HTML code for template footer link"
                      class="mw-ui-field"
                      type="text"><?php print $powered_by_link; ?></textarea>
              </div>
            </div>
          </div>
          <div class="mw-ui-col" style="width: 50%">
            <div class="mw-ui-col-container">
              <div class="mw-ui-field-holder">
                <ul class="mw-ui-inline-list">
                  <li><span>Microweber Marketplace</span></li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if (!$disable_marketplace): ?> checked="" <?php endif; ?>
                            name="disable_marketplace" value="0">
                      <span></span><span>Enabled</span> </label>
                  </li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if ($disable_marketplace): ?> checked="" <?php endif; ?>
                            name="disable_marketplace" value="1">
                      <span></span><span>Disabled</span> </label>
                  </li>
                </ul>
              </div>
              <div class="mw-ui-field-holder">
                <ul class="mw-ui-inline-list">
                  <li><span>"Powered By"</span></li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if (!$disable_powered_by_link): ?> checked="" <?php endif; ?>
                            name="disable_powered_by_link" value="0">
                      <span></span><span>Enabled</span> </label>
                  </li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if ($disable_powered_by_link): ?> checked="" <?php endif; ?>
                            name="disable_powered_by_link" value="1">
                      <span></span><span>Disabled</span> </label>
                  </li>
                </ul>
              </div>
              <div class="mw-ui-field-holder">
                <ul class="mw-ui-inline-list">
                  <li><span>Enable service links <small data-help="such as 'suggest a feature' and 'support'" class="mw-help"> (?) </small> </span></li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if ($enable_service_links): ?> checked="" <?php endif; ?>
                            name="enable_service_links" value="1">
                      <span></span><span>Yes</span> </label>
                  </li>
                  <li>
                    <label class="mw-ui-check">
                      <input
                            type="radio" <?php if (!$enable_service_links): ?> checked="" <?php endif; ?>
                            name="enable_service_links" value="0">
                      <span></span><span>No</span> </label>
                  </li>
                </ul>
              </div>
              <div class="mw-ui-field-holder">
                <input type="submit" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification" value="Save settings"/>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
    </div>
  </form>
</div>
