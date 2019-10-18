<div class="mwembed mw-audio" id="<?php print $id; ?>">
    <?php if( $audio != '' ){ ?>
    <audio controls src="<?php print $audio; ?>"></audio>
    <?php } else{ ?>
            <?php print lnotif(_e('Upload Audio File or paste URL.', true));   ?>
        <?php } ?>
</div>