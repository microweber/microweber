<?php only_admin_access(); ?>

<style>
     #hello_world_module{
       padding: 20px;
     }
     #hello_world_module label{
       display: block;
       padding: 8px 0 3px;
     }
</style>

<?php

    $text =  get_option('text', $params['id']);

    if($text==false)
    {
      $text = 'Hello world';
    }

?>
<div id="hello_world_module">
  <label>Enter some text</label>
  <textarea class="mw_option_field mw-ui-field w100" rows="30"  name="text"><?php print $text; ?></textarea>
 

</div>