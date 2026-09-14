<div class="mwembed mw-audio" id="audio-<?php print $id; ?>">
    <?php if ($audio != '') { ?>
    <audio src="<?php print $audio; ?>"<?php
        if (!empty($controls)) { print ' controls'; }
        if (!empty($autoplay)) { print ' autoplay muted'; }
        if (!empty($loop)) { print ' loop'; }
    ?>></audio>
    <?php } else { ?>
        <?php print lnotif(_lang('Upload Audio File or paste URL.', "modules/audio", true)); ?>
    <?php } ?>
</div>




