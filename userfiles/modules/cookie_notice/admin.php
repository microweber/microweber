<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <script>mw.lib.require('colorpicker');</script>


        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
                });
            });
        </script>

        <?php /* Options:

cookiePolicyURL:		'/cookie=-policy'
showLiveChatMessage: 	'true', 'false'
panelTogglePosition: 	'left', 'center', 'right'
unsetDefault:			'allowed', 'blocked'
backgroundColor:

Google_Analytics
enabled: 'true', 'false'
label:	'Google Analytics'
code:	'UA-xxxxxxxxx-1'

Facebook_Pixel
enabled: 'true', 'false'
label:	'Facebook Pixel'
code:	'123456'

Mautic_Tracking
enabled: 		'true', 'false'
label:			'Mautic Tracking'
code:			'https://yourmautic.com/mtc.js'

Tawk_to
enabled: 		'true', 'false'
label:			'Tawk.to - Live chat'
code:			'12345a6b789cdef01g234567'

Smartsupp
enabled: 		'true', 'false'
label:		'Smartsupp - Live chat'
code:			'ab12c34defghi5j6k789l0m1234n567890o12345'

Hotjar
enabled: 		'true', 'false'
label:			'Hotjar - Website heatmaps'
code:			'123456'

*/

        $mod_id = 'init_scwCookiedefault';
        $settings = get_option('settings', $mod_id);
        $defaults = array(
            'cookiePolicyURL' => 'privacy-policy',
            'showLiveChatMessage' => 'false',
            'panelTogglePosition' => 'right',
            'unsetDefault' => 'blocked',
            'cookies_policy' => 'n',
            'Google_Analytics' => array('enabled' => 'false',
                'label' => 'Google Analytics',
                'code' => 'UA-xxxxxxxxx-1'),
			'Facebook_Pixel' => array('enabled' => 'false',
				'label' => 'Facebook Pixel',
				'code' => ''),
			'Mautic_Tracking' => array('enabled' => 'false',
				'label' => 'Mautic Tracking',
				'code' => ''),
            'Tawk.to' => array('enabled' => 'false',
                'label' => 'Tawk.to - Live chat',
                'code' => ''),
            'Smartsupp' => array('enabled' => 'false',
                'label' => 'Smartsupp - Live chat',
                'code' => ''),
            'Hotjar' => array('enabled' => 'false',
                'label' => 'Hotjar - Website heatmaps',
                'code' => '')
        );

        $json = array_wrap(json_decode($settings, true));
        $json = array_merge($defaults, $json);
        if (isset($json) == false or count($json) == 0) {
            $json = $defaults;
        }

        $settings = $json;
        ?>

        <div class="module-live-edit-settings">
            <input type="hidden" class="mw_option_field" name="settings" id="settingsfield" option-group="<?php print $mod_id; ?>"/>

            <div class="setting-item" id="setting-item">
                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Turn On Cookies Policy'); ?>:</label>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="cookies_policy1" name="cookies_policy" class="custom-control-input cookies_policy" value="y" <?php if ('y' == $settings['cookies_policy']): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="cookies_policy1"><?php _e("Yes"); ?></label>
                    </div>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="cookies_policy2" name="cookies_policy" class="custom-control-input cookies_policy" value="n" <?php if ('n' == $settings['cookies_policy']): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="cookies_policy2"><?php _e("No"); ?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e('Cookie Policy URL'); ?>:</label>
                    <input type="text" class="form-control w100 cookiePolicyURL" value="<?php print $settings['cookiePolicyURL']; ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Panel Background Color'); ?>:</label>
                            <input type="text" id="bg-color" class="form-control backgroundColor" readonly="readonly" style="width:150px;<?php print (isset($settings['backgroundColor']) ? ' background:' . $settings['backgroundColor'] : ''); ?>" value="<?php print (isset($settings['backgroundColor']) ? $settings['backgroundColor'] : ''); ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Panel Text Color'); ?>:</label>
                            <input type="text" id="text-color" class="form-control textColor" readonly="readonly" style="width:150px;<?php print (isset($settings['textColor']) ? ' background:' . $settings['textColor'] : ''); ?>" value="<?php print (isset($settings['textColor']) ? $settings['textColor'] : ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label d-block"><?php _e('Show Live Chat Message'); ?>:</label>

                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="showLiveChatMessage1" name="showLiveChatMessage" class="custom-control-input showLiveChatMessage" value="true" <?php if ('true' == trim($settings['showLiveChatMessage'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="showLiveChatMessage1"><?php _e("Yes"); ?></label>
                            </div>

                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="showLiveChatMessage2" name="showLiveChatMessage" class="custom-control-input showLiveChatMessage" value="false" <?php if ('' == trim($settings['showLiveChatMessage']) or 'false' == trim($settings['showLiveChatMessage'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="showLiveChatMessage2"><?php _e("no"); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label d-block"><?php _e('Panel Toggle Position'); ?>:</label>

                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="panelTogglePosition1" name="panelTogglePosition" class="custom-control-input panelTogglePosition" value="left" <?php if ('left' == trim($settings['panelTogglePosition'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="panelTogglePosition1"><?php _e("left"); ?></label>
                            </div>
                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="panelTogglePosition2" name="panelTogglePosition" class="custom-control-input panelTogglePosition" value="center" <?php if ('center' == trim($settings['panelTogglePosition'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="panelTogglePosition2"><?php _e("center"); ?></label>
                            </div>
                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="panelTogglePosition3" name="panelTogglePosition" class="custom-control-input panelTogglePosition" value="right" <?php if ('' == trim($settings['panelTogglePosition']) or 'right' == trim($settings['panelTogglePosition'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="panelTogglePosition3"><?php _e("right"); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label d-block"><?php _e('Unset Default'); ?>:</label>

                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="unsetDefault1" name="unsetDefault" class="custom-control-input unsetDefault" value="allowed" <?php if ('allowed' == trim($settings['unsetDefault'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="unsetDefault1"><?php _e("allowed"); ?></label>
                            </div>

                            <div class="custom-control custom-radio d-inline-block mr-2">
                                <input type="radio" id="unsetDefault2" name="unsetDefault" class="custom-control-input unsetDefault" value="blocked" <?php if ('' == trim($settings['unsetDefault']) or 'blocked' == trim($settings['unsetDefault'])): ?>checked<?php endif; ?>>
                                <label class="custom-control-label" for="unsetDefault2"><?php _e("blocked"); ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin">

                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Google Analytics enabled'); ?>:</label>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Google_Analytics_enabled1" name="Google_Analytics_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Google_Analytics']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Google_Analytics_enabled1"><?php _e("Yes"); ?></label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Google_Analytics_enabled2" name="Google_Analytics_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Google_Analytics']['enabled']) or 'false' == trim($settings['Google_Analytics']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Google_Analytics_enabled2"><?php _e("no"); ?></label>
                    </div>
                </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Google_Analytics']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Google Analytics label'); ?>:</label>
                                <input type="text" class="form-control Google_Analytics_label" value="<?php print $settings['Google_Analytics']['label']; ?>">
                            </div>
                        </div>

                        <a href="<?php echo admin_url('view:settings#option_group=advanced');?>" target="_blank">Check Google Analytics Code</a>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Google Analytics code'); ?>:</label>
                                <input type="text" class="form-control Google_Analytics_code" value="<?php print $settings['Google_Analytics']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

               <hr class="thin">

               <div class="form-group">
                    <label class="control-label d-block"><?php _e('Facebook Pixel enabled'); ?>:</label>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Facebook_Pixel_enabled1" name="Facebook_Pixel_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Facebook_Pixel']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Facebook_Pixel_enabled1"><?php _e("Yes"); ?></label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Facebook_Pixel_enabled2" name="Facebook_Pixel_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Facebook_Pixel']['enabled']) or 'false' == trim($settings['Facebook_Pixel']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Facebook_Pixel_enabled2"><?php _e("No"); ?></label>
                    </div>
               </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Facebook_Pixel']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Facebook Pixel label'); ?>:</label>
                                <input type="text" class="form-control Facebook_Pixel_label" value="<?php print $settings['Facebook_Pixel']['label']; ?>">
                            </div>
                        </div>

                        <a href="<?php echo admin_url('view:settings#option_group=advanced');?>" target="_blank">Check Facebook Pixel Code</a>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Facebook Pixel code'); ?>:</label>
                                <input type="text" class="form-control Facebook_Pixel_code" value="<?php print $settings['Facebook_Pixel']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

               <hr class="thin">

               <div class="form-group">
                    <label class="control-label d-block"><?php _e('Mautic Tracking enabled'); ?>:</label>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Mautic_Tracking_enabled1" name="Mautic_Tracking_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Mautic_Tracking']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Mautic_Tracking_enabled1"><?php _e("Yes"); ?></label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Mautic_Tracking_enabled2" name="Mautic_Tracking_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Mautic_Tracking']['enabled']) or 'false' == trim($settings['Mautic_Tracking']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Mautic_Tracking_enabled2"><?php _e("no"); ?></label>
                    </div>
               </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Mautic_Tracking']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Mautic Tracking label'); ?>:</label>
                                <input type="text" class="form-control Mautic_Tracking_label" value="<?php print $settings['Mautic_Tracking']['label']; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Mautic Tracking code'); ?>:</label>
                                <input type="text" class="form-control Mautic_Tracking_code" value="<?php print $settings['Mautic_Tracking']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

               <hr class="thin">

                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Tawk.to enabled'); ?>:</label>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Tawk_to_enabled1" name="Tawk_to_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Tawk.to']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Tawk_to_enabled1"><?php _e("Yes"); ?></label>
                    </div>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Tawk_to_enabled2" name="Tawk_to_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Tawk.to']['enabled']) or 'false' == trim($settings['Tawk.to']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Tawk_to_enabled2"><?php _e("No"); ?></label>
                    </div>
                </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Tawk.to']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Tawk.to label'); ?>:</label>
                                <input type="text" class="form-control Tawk_to_label" value="<?php print $settings['Tawk.to']['label']; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Tawk.to code'); ?>:</label>
                                <input type="text" class="form-control Tawk_to_code" value="<?php print $settings['Tawk.to']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin">

                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Smartsupp enabled'); ?>:</label>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Smartsupp_enabled1" name="Smartsupp_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Smartsupp']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Smartsupp_enabled1"><?php _e("Yes"); ?></label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Smartsupp_enabled2" name="Smartsupp_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Smartsupp']['enabled']) or 'false' == trim($settings['Smartsupp']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Smartsupp_enabled2"><?php _e("No"); ?></label>
                    </div>
                </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Smartsupp']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Smartsupp label'); ?>:</label>
                                <input type="text" class="form-control Smartsupp_label" value="<?php print $settings['Smartsupp']['label']; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Smartsupp code'); ?>:</label>
                                <input type="text" class="form-control Smartsupp_code" value="<?php print $settings['Smartsupp']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin">

                <div class="form-group">
                    <label class="control-label d-block"><?php _e('Hotjar enabled'); ?>:</label>

                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Hotjar_enabled1" name="Hotjar_enabled" class="custom-control-input enable" value="true" <?php if ('true' == trim($settings['Hotjar']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Hotjar_enabled1"><?php _e("Yes"); ?></label>
                    </div>
                    <div class="custom-control custom-radio d-inline-block mr-2">
                        <input type="radio" id="Hotjar_enabled2" name="Hotjar_enabled" class="custom-control-input enable" value="false" <?php if ('' == trim($settings['Hotjar']['enabled']) or 'false' == trim($settings['Hotjar']['enabled'])): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="Hotjar_enabled2"><?php _e("No"); ?></label>
                    </div>
                </div>

                <div class="setting-fields" style="display:<?php if ('true' == trim($settings['Hotjar']['enabled'])): ?>block<?php else: ?>none<?php endif; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Hotjar label'); ?>:</label>
                                <input type="text" class="form-control Hotjar_label" value="<?php print $settings['Hotjar']['label']; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Hotjar code'); ?>:</label>
                                <input type="text" class="form-control Hotjar_code" value="<?php print $settings['Hotjar']['code']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            cookies_settings = {
                init: function (item) {
                    $(item.querySelectorAll('input[type="text"]')).on('keyup', function () {
                        mw.on.stopWriting(this, function () {
                            cookies_settings.save();
                        });
                    });
                    $(item.querySelectorAll('input[type="radio"]')).on('change', function () {
                        cookies_settings.save();
                        if ($(this).hasClass('enable')) {
                            var display = 'none';
                            if (this.value == 'true') {
                                display = 'block';
                            }
                            $(this).parent().parent().nextAll('.setting-fields').first().css('display', display);
                        }
                    });
                },
                collect: function () {
                    var data = {}, all = document.querySelectorAll('.setting-item'), l = all.length, i = 0;
                    for (; i < l; i++) {
                        var item = all[i];
                        data = {};

                        data['Google_Analytics'] = {};
						data['Facebook_Pixel'] = {};
						data['Mautic_Tracking'] = {};
                        data['Tawk.to'] = {};
                        data['Smartsupp'] = {};
                        data['Hotjar'] = {};

                        data['backgroundColor'] = item.querySelector('.backgroundColor').value;
                        data['textColor'] = item.querySelector('.textColor').value;
                        data['cookiePolicyURL'] = item.querySelector('.cookiePolicyURL').value;
                        data['showLiveChatMessage'] = item.querySelector('input[name=showLiveChatMessage]:checked').value;
                        data['panelTogglePosition'] = item.querySelector('input[name=panelTogglePosition]:checked').value;
                        data['cookies_policy'] = item.querySelector('input[name=cookies_policy]:checked').value;
                        data['unsetDefault'] = item.querySelector('input[name=unsetDefault]:checked').value;

                        data['Google_Analytics']['enabled'] = item.querySelector('input[name=Google_Analytics_enabled]:checked').value;
                        data['Google_Analytics']['label'] = item.querySelector('.Google_Analytics_label').value;
                        data['Google_Analytics']['code'] = item.querySelector('.Google_Analytics_code').value;

						data['Facebook_Pixel']['enabled'] = item.querySelector('input[name=Facebook_Pixel_enabled]:checked').value;
						data['Facebook_Pixel']['label'] = item.querySelector('.Facebook_Pixel_label').value;
						data['Facebook_Pixel']['code'] = item.querySelector('.Facebook_Pixel_code').value;

						data['Mautic_Tracking']['enabled'] = item.querySelector('input[name=Mautic_Tracking_enabled]:checked').value;
						data['Mautic_Tracking']['label'] = item.querySelector('.Mautic_Tracking_label').value;
						data['Mautic_Tracking']['code'] = item.querySelector('.Mautic_Tracking_code').value;

                        data['Tawk.to']['enabled'] = item.querySelector('input[name=Tawk_to_enabled]:checked').value;
                        data['Tawk.to']['label'] = item.querySelector('.Tawk_to_label').value;
                        data['Tawk.to']['code'] = item.querySelector('.Tawk_to_code').value;

                        data['Smartsupp']['enabled'] = item.querySelector('input[name=Smartsupp_enabled]:checked').value;
                        data['Smartsupp']['label'] = item.querySelector('.Smartsupp_label').value;
                        data['Smartsupp']['code'] = item.querySelector('.Smartsupp_code').value;

                        data['Hotjar']['enabled'] = item.querySelector('input[name=Hotjar_enabled]:checked').value;
                        data['Hotjar']['label'] = item.querySelector('.Hotjar_label').value;
                        data['Hotjar']['code'] = item.querySelector('.Hotjar_code').value;
                    }
                    return data;
                },
                save: function () {
                    mw.$('#settingsfield').val(JSON.stringify(cookies_settings.collect())).trigger('change');
                },
            }

            $(document).ready(function () {
                var all = document.querySelectorAll('.setting-item'), l = all.length, i = 0;
                for (; i < l; i++) {
                    if (!!all[i].prepared) continue;
                    var item = all[i];
                    item.prepared = true;
                    cookies_settings.init(item);
                }

                bg = mw.colorPicker({
                    element: '#bg-color',
                    position: 'bottom-left',
                    onchange: function (color) {
                        $("#bg-color").val(color).css('background', color);
                        cookies_settings.save();
                    }
                });
                text = mw.colorPicker({
                    element: '#text-color',
                    position: 'bottom-left',
                    onchange: function (color) {
                        $("#text-color").val(color).css('background', color);
                        cookies_settings.save();
                    }
                });
            });

        </script>

    </div>
</div>
