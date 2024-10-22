<div class="mwembed mw-audio" id="<?php print $id; ?>">
    <?php if ($audio != '') { ?>
    <audio controls src="<?php print $audio; ?>"></audio>
    <?php } else { ?>
        <?php print lnotif(_lang('Upload Audio File or paste URL.', "modules/audio", true)); ?>
    <?php } ?>
</div>




