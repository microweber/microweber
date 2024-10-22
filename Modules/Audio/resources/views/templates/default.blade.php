@php
$id = "mwaudio-".$params['id'];

$audio = false;
if (isset($params['data-audio-url'])) {
    $audio = $params['data-audio-url'];
}
$audioUpload = get_module_option('data-audio-upload', $params['id']);

$audioUrl = get_module_option('data-audio-url', $params['id']);
if ($audioUrl and trim($audioUrl) == '') {
    $audioUrl = false;
}


if ($audioUpload) {
    $audio = $audioUpload;
} else {
    if ($audioUrl) {
        $audio = trim($audioUrl);
    }
}
@endphp

<div class="mwembed mw-audio" id="<?php print $id; ?>">
    <?php if ($audio != '') { ?>
    <audio controls src="<?php print $audio; ?>"></audio>
    <?php } else { ?>
        <?php print lnotif(_lang('Upload Audio File or paste URL.', "modules/audio", true)); ?>
    <?php } ?>
</div>




