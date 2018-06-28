<?php only_admin_access(); ?>

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

$settings = get_option('settings', $params['id']);

/*
if ($settings == false) {
    if (isset($params['settings'])) {
        $settings = $params['settings'];
    }
}
*/

$defaults = array(
    'cookiePolicyURL' => 'privacy-policy',
    'showLiveChatMessage' => 'false',
    'panelTogglePosition' => 'right',
    'unsetDefault' => 'blocked',
    'Google_Analytics' => array('enabled' => 'false',
    							'label' => 'Google Analytics',
    							'code' => 'UA-xxxxxxxxx-1'),
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

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = $defaults;
}

$settings = $json;
?>

<div class="module-live-edit-settings">
  <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>

  <div class="mw-ui-box">
    <div class="mw-ui-box-content">

	  <div class="setting-item" id="setting-item">

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Panel Background Color'); ?>:</label>
			<input type="text" id="bg-color" class="mw-ui-field backgroundColor" readonly="readonly" style="width:80px;<?php print (isset($settings['backgroundColor'])? ' background:' . $settings['backgroundColor'] : '');?>" value="<?php print (isset($settings['backgroundColor'])? $settings['backgroundColor'] : ''); ?>">
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Cookie Policy URL'); ?>:</label>
			<input type="text" class="mw-ui-field w100 cookiePolicyURL" value="<?php print $settings['cookiePolicyURL']; ?>">
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Show Live Chat Message'); ?>:</label>
            <ul class="mw-ui-inline-list">
              <li>
				<label class="mw-ui-check">
					<input name="showLiveChatMessage" type="radio"
						   class="mw-ui-field showLiveChatMessage"
						   value="true" <?php if ('true' == trim($settings['showLiveChatMessage'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("yes"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="showLiveChatMessage" type="radio"
						   class="mw-ui-field showLiveChatMessage"
						   value="false" <?php if ('' == trim($settings['showLiveChatMessage']) or 'false' == trim($settings['showLiveChatMessage'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("no"); ?>
				</span></label>
			  </li>
			</ul>
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Panel Toggle Position'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="panelTogglePosition" type="radio"
						   class="mw-ui-field panelTogglePosition"
						   value="left" <?php if ('left' == trim($settings['panelTogglePosition'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("left"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="panelTogglePosition" type="radio"
						   class="mw-ui-field panelTogglePosition"
						   value="center" <?php if ('center' == trim($settings['panelTogglePosition'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("center"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="panelTogglePosition" type="radio"
						   class="mw-ui-field panelTogglePosition"
						   value="right" <?php if ('' == trim($settings['panelTogglePosition']) or 'right' == trim($settings['panelTogglePosition'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("right"); ?>
				</span></label>
			  </li>
			</ul>
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Unset Default'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="unsetDefault" type="radio"
						   class="mw-ui-field unsetDefault"
						   value="allowed" <?php if ('allowed' == trim($settings['unsetDefault'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("allowed"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="unsetDefault" type="radio"
						   class="mw-ui-field unsetDefault"
						   value="blocked" <?php if ('' == trim($settings['unsetDefault']) or 'blocked' == trim($settings['unsetDefault'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("blocked"); ?>
				</span></label>
			  </li>
			</ul>
		</div>
		<hr>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Google Analytics enabled'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="Google_Analytics_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="true" <?php if ('true' == trim($settings['Google_Analytics']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("yes"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="Google_Analytics_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="false" <?php if ('' == trim($settings['Google_Analytics']['enabled']) or 'false' == trim($settings['Google_Analytics']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("no"); ?>
				</span></label>
			  </li>
			</ul>
		</div>
		<div class="mw-ui-box setting-fields" style="display:<?php if ('true' == trim($settings['Google_Analytics']['enabled'])):?>block<?php else:?>none<?php endif; ?>">
			<div class="mw-ui-box-content">
				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Google Analytics label'); ?>:</label>
					<input type="text" class="mw-ui-field Google_Analytics_label" value="<?php print $settings['Google_Analytics']['label']; ?>">
				</div>

				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Google Analytics code'); ?>:</label>
					<input type="text" class="mw-ui-field Google_Analytics_code" value="<?php print $settings['Google_Analytics']['code']; ?>">
				</div>
			</div>
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Tawk.to enabled'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="Tawk_to_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="true" <?php if ('true' == trim($settings['Tawk.to']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("yes"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="Tawk_to_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="false" <?php if ('' == trim($settings['Tawk.to']['enabled']) or 'false' == trim($settings['Tawk.to']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("no"); ?>
				</span></label>
			  </li>
			</ul>
		</div>
		<div class="mw-ui-box setting-fields" style="display:<?php if ('true' == trim($settings['Tawk.to']['enabled'])):?>block<?php else:?>none<?php endif; ?>">
			<div class="mw-ui-box-content">
				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Tawk.to label'); ?>:</label>
					<input type="text" class="mw-ui-field Tawk_to_label" value="<?php print $settings['Tawk.to']['label']; ?>">
				</div>

				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Tawk.to code'); ?>:</label>
					<input type="text" class="mw-ui-field Tawk_to_code" value="<?php print $settings['Tawk.to']['code']; ?>">
				</div>
			</div>
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Smartsupp enabled'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="Smartsupp_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="true" <?php if ('true' == trim($settings['Smartsupp']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("yes"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="Smartsupp_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="false" <?php if ('' == trim($settings['Smartsupp']['enabled']) or 'false' == trim($settings['Smartsupp']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("no"); ?>
				</span></label>
			  </li>
			</ul>
		</div>
		<div class="mw-ui-box setting-fields" style="display:<?php if ('true' == trim($settings['Smartsupp']['enabled'])):?>block<?php else:?>none<?php endif; ?>">
			<div class="mw-ui-box-content">
				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Smartsupp label'); ?>:</label>
					<input type="text" class="mw-ui-field Smartsupp_label" value="<?php print $settings['Smartsupp']['label']; ?>">
				</div>

				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Smartsupp code'); ?>:</label>
					<input type="text" class="mw-ui-field Smartsupp_code" value="<?php print $settings['Smartsupp']['code']; ?>">
				</div>
			</div>
		</div>

		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"><?php _e('Hotjar enabled'); ?>:</label>
            <ul class="mw-ui-inline-list">
			  <li>
				<label class="mw-ui-check">
					<input name="Hotjar_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="true" <?php if ('true' == trim($settings['Hotjar']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("yes"); ?>
				</span></label>
			  </li>
			  <li>
				<label class="mw-ui-check">
					<input name="Hotjar_enabled" type="radio"
						   class="mw-ui-field enable"
						   value="false" <?php if ('' == trim($settings['Hotjar']['enabled']) or 'false' == trim($settings['Hotjar']['enabled'])): ?> checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("no"); ?>
				</span></label>
			  </li>
			</ul>
		</div>
		<div class="mw-ui-box setting-fields" style="display:<?php if ('true' == trim($settings['Hotjar']['enabled'])):?>block<?php else:?>none<?php endif; ?>">
			<div class="mw-ui-box-content">
				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Hotjar label'); ?>:</label>
					<input type="text" class="mw-ui-field Hotjar_label" value="<?php print $settings['Hotjar']['label']; ?>">
				</div>

				<div class="mw-ui-field-holder">
					<label class="mw-ui-label"><?php _e('Hotjar code'); ?>:</label>
					<input type="text" class="mw-ui-field Hotjar_code" value="<?php print $settings['Hotjar']['code']; ?>">
				</div>
			</div>
		</div>

	  </div>
    </div>
  </div>
</div>

<script>

	settings = {
		init: function (item) {
			$(item.querySelectorAll('input[type="text"]')).bind('keyup', function () {
				mw.on.stopWriting(this, function () {
					settings.save();
				});
			});
			$(item.querySelectorAll('input[type="radio"]')).bind('change', function () {
					settings.save();
					if($(this).hasClass('enable')) {
						var display = 'none';
						if(this.value == 'true'){
							display = 'block';
						}
						$(this).closest('div').nextAll('.setting-fields').first().css('display',display);
					}
			});
		},
		collect: function () {
			var data = {}, all = mwd.querySelectorAll('.setting-item'), l = all.length, i = 0;
			for (; i < l; i++) {
				var item = all[i];
				data = {};

				data['Google_Analytics'] = {};
				data['Tawk.to'] = {};
				data['Smartsupp'] = {};
				data['Hotjar'] = {};

				data['backgroundColor'] = item.querySelector('.backgroundColor').value;
				data['cookiePolicyURL'] = item.querySelector('.cookiePolicyURL').value;
				data['showLiveChatMessage'] = item.querySelector('input[name=showLiveChatMessage]:checked').value;
				data['panelTogglePosition'] = item.querySelector('input[name=panelTogglePosition]:checked').value;
				data['unsetDefault'] = item.querySelector('input[name=unsetDefault]:checked').value;

				data['Google_Analytics']['enabled'] = item.querySelector('input[name=Google_Analytics_enabled]:checked').value;
				data['Google_Analytics']['label'] = item.querySelector('.Google_Analytics_label').value;
				data['Google_Analytics']['code'] = item.querySelector('.Google_Analytics_code').value;

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
			mw.$('#settingsfield').val(JSON.stringify(settings.collect())).trigger('change');
		},
	}

	$(document).ready(function () {
		var all = mwd.querySelectorAll('.setting-item'), l = all.length, i = 0;
		for (; i < l; i++) {
			if (!!all[i].prepared) continue;
			var item = all[i];
			item.prepared = true;
			settings.init(item);
		}

		bg = mw.colorPicker({
			element: '#bg-color',
			position: 'bottom-left',
			onchange: function (color) {
				$("#bg-color").val(color).css('background',color);
				settings.save();
			}
		});
	});

</script>