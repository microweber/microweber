<? only_admin_access(); ?>


<style>

.tpl_previe_overlay{
   background: none repeat scroll 0 0 transparent;
    display: block;
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 995;
}

</style>



<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Default template saved"); ?>.");
    });



});



modulePreview = function(el){
   var url = el.tagName == 'A' ? el.href : el.src;

   var modal = mw.tools.modal.frame({
     url:url,
     name:"preview_" + el.id,
     title: 'Preview Template - <b>' + el.title + "</b>",
     height:600,
     template:'mw_modal_simple'
   });

   if(!modal){
     mw.tools.highlight(mwd.getElementById("preview_" + el.id), "#FF0000");
   }
   else{
     $(modal.container).css("position", "relative").append("<div class='tpl_previe_overlay'></div>");
   }

}


</script>
<?
if(isset($params['for'])){
	$params['parent-module'] = $params['for'];
	$params['parent-module-id'] =  $params['for'];
}
if(!isset($params['parent-module'])){
error('parent-module is required');
	
}

if(!isset($params['parent-module-id'])){
error('parent-module-id is required');	
	
}
 $curent_module = $params['parent-module'];
 $curent_module_url = module_name_encode($params['parent-module']);
 $templates = module_templates($params['parent-module']);
//$params['type'];

$cur_template = get_option('data-template', $params['parent-module-id']);
 ?>
<?  if(is_arr( $templates)): ?>

<label class="mw-ui-label">Set default skin for the whole website</label>
<div class="mw-ui-select" style="width: 70%">
  <select name="data-template"     class="mw_option_field" option_group="<? print $params['parent-module-id'] ?>"  data-refresh="<? print $params['parent-module-id'] ?>"  >
    <option  value="default"   <? if(('default' == $cur_template)): ?>   selected="selected"  <? endif; ?>>Default</option>
    <?  foreach($templates as $item):	 ?>
    <? if((strtolower($item['name']) != 'default')): ?>
    <option value="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $cur_template)): ?>   selected="selected"  <? endif; ?>     > <? print $item['name'] ?> </option>
    <? endif; ?>
    <? endforeach; ?>
  </select>
</div>
<a class="mw-ui-btn mw-ui-btn-green " href="javascript:;">Get more templates</a>
<div class="mw-admin-templates-browse-holder">
  <? if(isarr($templates )): ?>
  <? $i = 1; foreach($templates  as $item): ?>
  <? if(isset($item['name'])): ?>
  <h2><? print $item['name'] ?></h2>
   <? if(isset($item['description'])): ?>
  <h4><? print $item['description'] ?></h4>
  <? endif; ?>
  

  <div class="templatePreviewHolder" onclick="modulePreview(mwd.getElementById('skin_num_<? print $i.md5($curent_module); ?>')); return false;">

  <? if(isset($item['icon'])){ ?>
        <img src="<? print $item['icon'] ?>" width="365" height="365" />
  <? } else if(isset($item['image'])){ ?>
        <img src="<? print $item['image'] ?>" width="365" height="365" />
  <? } else {; ?>
       <iframe
              src="<? print site_url('clean') ?>/preview_module:<? print ($curent_module_url) ?>/preview_module_template:<? print module_name_encode($item['layout_file']) ?>/preview_module_id:skin_num_<? print $i.md5($curent_module); ?>"
              width="365"
              height="365"
              frameborder="0"
              scrolling="no"
              >
       </iframe>
  <?php } ?>

  </div>

  <a onclick="modulePreview(this); return false;" title="<? print $item['name'] ?>" id="skin_num_<? print $i.md5($curent_module); ?>" href="<? print site_url('clean') ?>/preview_module:<? print ($curent_module_url) ?>/preview_module_template:<? print module_name_encode($item['layout_file']) ?>/preview_module_id:skin_num_<? print $i.md5($curent_module); ?>"  class="mw-ui-btn">Preview</a>

  <? //d($item); ?>

  
  
  
  <? endif; ?>
  <?  $i++; endforeach ; ?>
  <? endif; ?>
</div>
<? endif; ?>
