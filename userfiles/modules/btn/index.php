
<?php

    $style =  get_option('button_style', $params['id']);
    $size =  get_option('button_size', $params['id']);
    $action =  get_option('button_action', $params['id']);
    $action_content =  get_option('popupcontent', $params['id']);



?>


<?php if($action=='url' or $action==''){ ?>

<a href="<?php $url; ?>" class="btn <?php print $style. ' '. $size; ?>">Button</a>


<?php } else if($action=='popup') { ?>
     <?php

     $rand = uniqid();

     ?>
<script>
  mw.require('tools.js', true);
  mw.require('mw.ui.css', true);
</script>

<a href="javascript:;" id="btn<?php print $rand; ?>" class="btn <?php print $style. ' '. $size; ?>">Button</a>
<textarea id="area<?php print $rand; ?>" class="hide"><?php print $action_content; ?></textarea>
<script>

$(document).ready(function(){
  mw.$("#btn<?php print $rand; ?>").click(function(){
      mw.tools.modal.init({
        name:'frame<?php print $rand; ?>',
        html:mwd.getElementById('area<?php print $rand; ?>').value,
        template:'mw_modal_simple'
      })
  })

});

</script>



<?php } ?>