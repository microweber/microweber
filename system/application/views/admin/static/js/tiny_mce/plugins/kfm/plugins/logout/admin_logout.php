<?php
require_once('../../initialise.php');
//if($kfm->user_status!=1)die ('error("No authorization aquired")');
//require_once('functions.php');
if($use_kfm_security==true && $uid == false) {
    die('Please log in!!');
}

$kfm_session->logout();
echo '<br><a href="'.$kfm->setting('kfm_url').'">To the login page</a>';
?>
<script type="text/javascript">
window.location='<?php echo $kfm->setting('kfm_url')?>';
</script>
