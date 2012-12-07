<?
 
if(!isset($params['to_table_id'])){
	$params['to_table_id'] = $params['id']; 
}

 if(isset($params['to_table_id']) == true): ?>
<? $data = get_pictures($content_id = $params['to_table_id'], $for = 'post'); 
 
 $template = get_option('data-template', $params['id']);
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
    $template_file = module_templates($params['type'], $template);
 }

?>
<?php
switch ($template_file):
    case true:
        ?>
<? include($template_file); ?>
<?
        // d();

        if ($template_file != false) {
            break;
        }
        ?>
<?php
    case false:
        ?>
<? if(isarr($data )): ?>

<div class="mw-gallery-holder">
  <? foreach($data  as $item): ?>
  <div class="mw-gallery-item mw-gallery-item-<? print $item['id']; ?>"><img src="<? print $item['filename']; ?>" /></div>
  <? endforeach ; ?>
</div>
<? endif; ?>
<?php break; ?>
<?php endswitch; ?>
<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>
