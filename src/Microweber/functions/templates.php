<?php
function get_template_option_group()
{
	return 'mw-template-'. mw()->template->folder_name();
}

function get_template_stylesheet()
{
  $template_settings = mw()->template->get_config();
  $stylesheet_settings = false;
  if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['settings'])) {
      $stylesheet_settings = $template_settings['stylesheet_compiler']['settings'];
  }

  if (!$stylesheet_settings) {
      return;
  }

  return '<link href="' . mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], $template_settings['stylesheet_compiler']['css_file'], true) . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
}
