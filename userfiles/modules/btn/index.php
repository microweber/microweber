<?php

    $style =  get_option('button_style', $params['id']);
    $size =  get_option('button_size', $params['id']);
    $action =  get_option('button_action', $params['id']);
    $action_content =  get_option('popupcontent', $params['id']);
    $url =  get_option('url', $params['id']);
    $blank =   get_option('url_blank', $params['id']);
    $text =  get_option('text', $params['id']);
    $icon =  get_option('icon', $params['id']);

     if ($text == ''){ $text = 'Button';}
     if ($style == ''){ $style = 'btn-default';}

     if($size == false and isset($params['button_size'])){
        $size = $params['button_size'];

     }

?>
<?php if($action=='url' or $action==''){ ?>

<a href="<?php print $url; ?>" <?php if($blank=='y'){print ' target="_blank" ';} ?> class="btn <?php print $style. ' '. $size; ?>"><?php print $text; ?></a>
<?php } else if($action=='popup') { ?>
<?php

     $rand = uniqid();

     ?>
<script>
  mw.require('tools.js', true);
  mw.require('ui.css', true);
</script>
<a href="javascript:;" id="btn<?php print $rand; ?>" class="btn <?php print $style. ' '. $size; ?>"><?php print $text; ?></a>

<script type="text/microweber" id="area<?php print $rand; ?>">
    <?php print $action_content; ?>
</script>
<script>

$(document).ready(function(){
  mw.$("#btn<?php print $rand; ?>").click(function(){
      mw.modal({
        name:'frame<?php print $rand; ?>',
        html:$(mwd.getElementById('area<?php print $rand; ?>')).html(),
        template:'basic',
        title:"<?php print $text; ?>"
      });
  })

});

</script>
<?php } ?>
