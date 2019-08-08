<?php

function get_field_size_options()
{
	
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		for ($i = 1; $i <= 12; $i++) {
			$options['mw-flex-col-md-' . $i . ' mw-flex-col-sm-12 mw-flex-col-xs-12'] = 'mw-flex-col-md-' . $i;
		}
	}
	
	if ($css_framework == 'bootstrap3') {
		$options['col'] = 'col';
		for ($i = 2; $i <= 12; $i++) {
			$options['col-md-' . $i] = 'col-md-' . $i;
		}
	}
	
	if ($css_framework == 'bootstrap4') {
		$options['col'] = 'col';
		for ($i = 2; $i <= 12; $i++) {
			$options['col-' . $i] = 'col-' . $i;
		}
	}
	
	return $options;
}
