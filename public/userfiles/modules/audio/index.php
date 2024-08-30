<?php
$id = "mwaudio-" . uniqid();
//$prior =  get_module_option('prior', $params['id']);
$audio = false;
if (isset($params['data-audio-url'])) {
    $audio = $params['data-audio-url'];
}
//   if($prior == '1'){
$audio_upload = get_module_option('data-audio-upload', $params['id']);
//  }
//else{
$audio_url = get_module_option('data-audio-url', $params['id']);
if($audio_url and trim($audio_url) == ''){
    $audio_url = false;
}

// }

if ($audio_upload) {
    $audio = $audio_upload;
} else if ($audio_url) {
    $audio = trim($audio_url);
}

?>

<div class="mwembed mw-audio" id="<?php print $id; ?>">
    <?php if ($audio != '') { ?>
        <audio controls src="<?php print $audio; ?>"></audio>
    <?php } else { ?>
        <?php print lnotif(_lang('Upload Audio File or paste URL.', "modules/audio", true)); ?>
    <?php } ?>
</div>






