<?php

function template_field_size_options()
{
	$css_framework = template_framework();

	if ($css_framework == 'mw-ui') {
		for ($i = 1; $i <= 12; $i ++) {
			$options[$i] = 'mw-flex-col-md-' . $i;
		}
	}

	if ($css_framework == 'bootstrap3') {
		for ($i = 2; $i <= 12; $i ++) {
			$options[$i] = 'col-md-' . $i;
		}
	}

	if ($css_framework == 'bootstrap4') {
		for ($i = 2; $i <= 12; $i ++) {
			$options[$i] = 'col-' . $i;
		}
	}

	return $options;
}

