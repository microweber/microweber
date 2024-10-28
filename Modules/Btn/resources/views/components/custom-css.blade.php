
<?php

$cssWrapper = '';
$cssButton = '';
$cssHoverButton = '';


if ($backgroundColor) {
    $cssButton .= 'background-color:' . $backgroundColor . '!important;';
}
if ($color) {
    $cssButton .= 'color:' . $color . '!important;';
}

if ($borderColor) {
    $cssButton .= 'border-color:' . $borderColor . '!important;';
}

if ($borderWidth) {
    $cssButton .= 'border-width:' . $borderWidth . 'px!important;';
}

if ($borderRadius) {
    $cssButton .= 'border-radius:' . $borderRadius . 'px!important;';
}

if ($customSize) {
    $cssButton .= 'font-size: ' . (intval($customSize)) . 'px!important;padding: .9em 2em!important;';
}

if ($shadow) {
    $cssButton .= 'box-shadow:' . $shadow . '!important;';
}

if ($align) {
    if (_lang_is_rtl()) {
        if ($align == 'left') {
            $align = 'right';
        } elseif ($align == 'right') {
            $align = 'left';
        }
    }
    $cssWrapper .= 'text-align:' . $align . ' !important;';
}

if ($hoverbackgroundColor) {
    $cssHoverButton .= 'background-color:' . $hoverbackgroundColor . ' !important;';
}

if ($hovercolor) {
    $cssHoverButton .= 'color:' . $hovercolor . ' !important;';
}

if ($hoverborderColor) {
    $cssHoverButton .= 'border-color:' . $hoverborderColor . ' !important;';
}


?>
<style>
    #<?php print $params['id']; ?> {
        <?php print $cssWrapper; ?>
        }

        #<?php print $params['id']; ?> > #<?php print $btn_id; ?>, #<?php print $params['id']; ?> > a, #<?php print $params['id']; ?> > button {
                                                                                                            <?php print $cssButton; ?>
}

    #<?php print $params['id']; ?> > #<?php print $btn_id; ?>:hover, #<?php print $params['id']; ?> > a:hover, #<?php print $params['id']; ?> > button:hover {
                                                                                                                <?php print $cssHoverButton; ?>
}

</style>

