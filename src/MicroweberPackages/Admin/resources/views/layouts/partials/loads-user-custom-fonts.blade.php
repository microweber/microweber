<?php

$liv_ed_css = app()->template->get_custom_fonts_css_url();
$liv_ed_css_get_custom_css_content_fonts = app()->template->get_custom_fonts_css_content();
if ($liv_ed_css_get_custom_css_content_fonts) {
    $liv_ed_css = '<link rel="stylesheet" href="' . $liv_ed_css . '" id="mw-custom-user-fonts" type="text/css"  crossorigin="anonymous" referrerpolicy="no-referrer" />';
    print $liv_ed_css;
} else {
    $liv_ed_css = '<link rel="stylesheet"  crossorigin="anonymous" referrerpolicy="no-referrer"  id="mw-custom-user-fonts" type="text/css" />';
    print $liv_ed_css;
}




?>
