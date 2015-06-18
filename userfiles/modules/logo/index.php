<?php
$logotype = get_option('logotype', $params['id']);
$logoimage = get_option('logoimage', $params['id']);
$text = get_option('text', $params['id']);
$font_family = get_option('font_family', $params['id']);
$font_size = get_option('font_size', $params['id']);
if ($font_size == false) {
    $font_size = 30;
}

$size = get_option('size', $params['id']);
if ($size == false or $size == '') {
    $size = 60;
}
$size = $size . 'px';

$default = '';
if (isset($params['data-defaultlogo'])) {
    $default = $params['data-defaultlogo'];
}
if ($logoimage == false or $logoimage == '') {
    $logoimage = $default;
}


$font_family_safe = str_replace("+", " ", $font_family);
if($font_family_safe == ''){
  $font_family_safe = 'inherit';
}

?>
<?php  if($font_family_safe != 'inherit'){ ?>

    <script>mw.require('//fonts.googleapis.com/css?family=<?php print $font_family; ?>&filetype=.css', true);</script>

<?php } ?>

<a href="<?php if (!in_live_edit()) {
    print site_url();
} else {
	if ($logoimage == '' and $text == '') {
		  print 'javascript:mw.drag.module_settings();void(0);';
	} else {
		  print site_url();
	}
   
}; ?>" class="mw-ui-row-nodrop module-logo navbar-brand" style="width: auto;">
    <?php if ($logoimage == '' and $text == '') {
        if (is_live_edit()) { ?><span class="mw-logo-no-values">Click to add logo</span><?php }
    } else { ?>
        <?php if ($logotype == 'image' or $logotype == false or $logotype == 'both') { ?><span class="mw-ui-col" style="width: <?php print $size; ?>">
            <img src="<?php print $logoimage; ?>" alt="" style="max-width: 100%;width: <?php print $size; ?>;"/>
            </span><?php } ?>
        <?php if ($logotype == 'text' or $logotype == false or $logotype == 'both') { ?><span class="mw-ui-col"><span
                class="module-logo-text"
                style="font-family: '<?php print $font_family_safe; ?>';font-size:<?php print $font_size; ?>px"><?php print $text; ?></span>
            </span><?php } ?>

    <?php } ?>
</a>





