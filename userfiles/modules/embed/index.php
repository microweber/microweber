<?php

$source_code = get_option('source_code', $params['id']);
$hide_in_live_edit = get_option('hide_in_live_edit', $params['id']);
$is_live_edit = is_live_edit();
if ($hide_in_live_edit != '' OR $hide_in_live_edit != false) {
    $hide_in_live_edit == true;
}

print lnotif(_e('Click to edit Embed Code', true));

if ($source_code != false and $source_code != '') {
    if ($hide_in_live_edit == true AND $is_live_edit == true) {

    }else{
        print "<div class='mwembed'>" . $source_code . '</div>';
    }
}