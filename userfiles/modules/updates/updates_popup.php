<?php only_admin_access();
 
if (!isset($params['popup'])) {
return;
}



?>

<script>
    $( document ).ready(function() {
        mw.modalFrame({url:'<?php print $params["popup"] ?>'});
    });

</script>
