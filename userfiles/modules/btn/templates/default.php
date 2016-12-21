<?php

/*

type: layout

name: Default

description: Default

*/
?>
<?php if($action=='url' or $action==''){ ?>

<a href="<?php print $url; ?>" <?php if($blank=='y'){print ' target="_blank" ';} ?> class="btn <?php print $style. ' '. $size; ?>"><?php print $text; ?></a>
<?php } else if($action=='popup') { ?>
<?php

     $rand = uniqid();

     ?>
<a href="javascript:;" id="btn<?php print $rand; ?>" class="btn <?php print $style. ' '. $size; ?>"><?php print $text; ?></a>
<textarea id="area<?php print $rand; ?>" class="hide"><?php print $action_content; ?></textarea>
<script>

$(document).ready(function(){
  mw.$("#btn<?php print $rand; ?>").click(function(){
      mw.modal({
        name:'frame<?php print $rand; ?>',
        html:mwd.getElementById('area<?php print $rand; ?>').value,
        template:'basic',
        title:"<?php print $text; ?>"
      });
  })

});

</script>
<?php } ?>