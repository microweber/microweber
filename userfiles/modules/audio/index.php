
<?php
    $id = "mwaudio-" . uniqid();
    $prior =  get_option('prior', $params['id']);

    if($prior == '1'){
      $audio =  get_option('data-audio-upload', $params['id']);
    }
    else{
      $audio =  get_option('data-audio-url', $params['id']);
    }
?>

<div class="mwembed mw-audio" id="<?php print $id; ?>">
        <?php if( $audio != '' ){ ?>
            <audio controls src="<?php print $audio; ?>"></audio>
        <?php } else{ ?>
            <?php print lnotif(_e('Upload Audio File or paste URL.', true));   ?>
        <?php } ?>
</div>






