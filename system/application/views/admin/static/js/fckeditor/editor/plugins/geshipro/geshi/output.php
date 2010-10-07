<?php
if (isset($_POST)) {
	include('geshi.php');

	$source = stripslashes( $_POST['source'] );
	$lang = $_POST['lang'];
	$path = 'geshi/';

	$geshi = new GeSHi($source, $lang, $path);
	
	if ($_POST['css_classes'] == 1) {
		$geshi->enable_classes();
		$styles = '<style type="text/css"><!--';
		$styles .= $geshi->get_stylesheet();
		$styles .= '	--></style>';
	} else {
		$style = NULL;
	}
	
	switch ($_POST['code_container']) {
		case 1:
			$geshi->set_header_type(GESHI_HEADER_NONE);
			break;
			
		case 2:
			$geshi->set_header_type(GESHI_HEADER_PRE);
			break;
			
		case 3:
			$geshi->set_header_type(GESHI_HEADER_DIV);
			break;
	}

	switch ($_POST['linenum']) {
		case 1:
			$geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
			break;
			
		case 2:
			$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
			break;
			
		case 3:
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
			break;
	}
	
	if (is_numeric($_POST['tabwidth'])) {
		$geshi->set_tab_width($_POST['tabwidth']);
	}

	//$geshi->set_overall_style('font-size:12px;',true);
	
	if ($styles) echo $styles;
	
	echo $geshi->parse_code();
	
	
}

?>