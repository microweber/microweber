<?php must_have_access();
exit;
//depricated

if (!isset($params['popup'])) {
return;
}

if(isset($_COOKIE['mw-update-popup-closed'])){
    return;
}

?>

<script>
    $( document ).ready(function() {
        mw.dialogIframe({
            url:'<?php print $params["popup"] ?>',
            onremove:function(){

                mw.cookie.set('mw-update-popup-closed',true,1);
            }
        });
    });

</script>
