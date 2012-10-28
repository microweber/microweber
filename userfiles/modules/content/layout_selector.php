<?




 
 
$rand = uniqid();
if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}
 
$data = get_content_by_id($params["data-page-id"]); 

if($data == false or empty($data )){
include('_empty_content_data.php');	
}


if(isset($params["data-active-site-template"])){
	$data['active_site_template'] = $params["data-active-site-template"] ;
}



 
 $templates= templates_list();
 
 $layout_options = array();
 
 $layout_options  ['site_template'] = $data['active_site_template'];
 
  $layouts = layouts_list($layout_options);

 ?>
<script>


safe_chars_to_str = function(str){ return str.replace(/\\/g,'____').replace(/\'/g,'\\\'').replace(/\"/g,'\\"').replace(/\0/g,'____');}


mw.templatePreview = {
  set:function(){
    mw.$('.preview_frame_wrapper iframe')[0].contentWindow.scrollTo(0,0);
    mw.$('.preview_frame_wrapper').removeClass("loading");
  },
  rend:function(url){
    var frame = '<iframe src="'+url+'" class="preview_frame_small" tabindex="-1" onload="mw.templatePreview.set();" frameborder="0" scrolling="no"></iframe>';
    mw.$('.preview_frame_container').html(frame);
  },
  next:function(){
    var index = mw.templatePreview.selector.selectedIndex;
    var next = mw.templatePreview.selector.options[index+1] !== undefined ? (index+1) : 0;
    mw.templatePreview.view(next);
  },
  prev:function(){
    var index = mw.templatePreview.selector.selectedIndex;
    var prev = mw.templatePreview.selector.options[index-1] !== undefined ? (index-1) : mw.templatePreview.selector.options.length-1;
    mw.templatePreview.view(prev);
  },
  view:function(which){
    mw.templatePreview.selector.selectedIndex = which;
    mw.$("#layout_selector li.active").removeClass('active');
    mw.$("#layout_selector li").eq(which).addClass('active');
    $(mw.templatePreview.selector).trigger('change');
  },
  zoom:function(){
    mw.$('.preview_frame_wrapper').toggleClass('zoom');
    mw.$('.preview_frame_wrapper iframe')[0].contentWindow.scrollTo(0,0);
  },
  generate:function(){
        mw.$('.preview_frame_wrapper').addClass("loading");

		var template = mw.$('#active_site_template_<? print $rand; ?>').val();
		var layout = mw.$('#active_site_layout_<? print $rand; ?>').val();

		var template = safe_chars_to_str(template);
		var layout =  safe_chars_to_str(layout);

		var template = template.replace('/','___');;
		var layout = layout.replace('/','___');;

		var iframe_url = '<? print page_link($data['id']); ?>/no_editmode:true/preview_template:'+template+'/preview_layout:'+layout

        mw.templatePreview.rend(iframe_url);
  },
  _once:false
}


ToggleAdvancedSettings = function(){
  mw.$(".advanced_settings_holder").toggle();

  var main = mwd.getElementById('mw_edit_pages_content');
  main.scrollTop = main.offsetHeight;

}




$(document).ready(function() {


    mw.templatePreview.selector = mwd.getElementById('active_site_layout_<? print $rand; ?>');

	mw.$('#active_site_template_<? print $rand; ?>').bind("change", function(e) {
		var parent_module = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
		parent_module.attr('data-active-site-template',$(this).val());
        mw.reload_module(parent_module);
    });

	mw.$('#active_site_layout_<? print $rand; ?>').bind("change", function(e) {
		mw.templatePreview.generate();
    });




	

	

});

</script>

<label class="mw-ui-label">Template</label>
<div class="mw-ui-select" style="width: 275px">
<? if($templates != false and !empty($templates)): ?>
<select name="active_site_template" id="active_site_template_<? print $rand; ?>">
  <option value="default"   <? if(('' == trim($data['active_site_template']))): ?>   selected="selected"  <? endif; ?>>Default</option>
  <? if(('' != trim($data['active_site_template']))): ?>
  <option value="<? print $data['active_site_template'] ?>"     selected="selected" ><? print $data['active_site_template'] ?></option>
  <? endif; ?>
  <? foreach($templates as $item): ?>
  <? $attrs = ''; 
 foreach($item as $k=>$v): ?>
  <? $attrs .= "data-$k='{$v}'"; ?>
  <? endforeach ?>
  <option value="<? print $item['dir_name'] ?>"    <? if ($item['dir_name'] == $data['active_site_template']): ?>   selected="selected"  <? endif; ?>   <? print $attrs; ?>  > <? print $item['name'] ?> </option>
  <? endforeach; ?>
</select>
<? endif; ?>

</div>

<select name="layout_file" style="display: none" id="active_site_layout_<? print $rand; ?>">
  <option value="inherit"  <? if(('' == trim($data['layout_file']))): ?>   selected="selected"  <? endif; ?>>None</option>
  <? if(('' != trim($data['layout_file']))): ?>
  <option value="<? print $data['layout_file'] ?>"     selected="selected" ><? print $data['layout_file'] ?></option>
  <? endif; ?>
  <? if(!empty($layouts)): ?>
  <? foreach($layouts as $item): ?>
  <option value="<? print $item['layout_file'] ?>"  title="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $data['layout_file']) ): ?>   selected="selected"  <? endif; ?>   > <? print $item['name'] ?> </option>
  <? endforeach; ?>
  <? endif; ?>
</select>
<br />
<br />
Preview<br />
<br />



<div class="preview_frame_wrapper loading left">
    <div class="preview_frame_ctrls">
        <span class="zoom" title="<?php _e('Zoom in/out'); ?>" onclick="mw.templatePreview.zoom();"></span>
        <span class="prev" title="<?php _e('Previous layout'); ?>" onclick="mw.templatePreview.prev();"></span>
        <span class="next" title="<?php _e('Next layout'); ?>" onclick="mw.templatePreview.next();"></span>
    </div>
    <div class="preview_frame_container"></div>
    <div class="mw-overlay" onclick="mw.templatePreview.zoom();">&nbsp;</div>
</div>


<div class="layouts_box_holder">

  <label class="mw-ui-label">Page Layout</label>

  <div class="layouts_box_container">
      <div class="layouts_box" id="layout_selector">

          <ul>
            <li value="inherit"  onclick="mw.templatePreview.view(0);"  <? if(('' == trim($data['layout_file']))): ?>   selected="selected"  <? endif; ?>>None</li>
            <? if(('' != trim($data['layout_file']))): ?>
                <li value="<? print $data['layout_file'] ?>"   onclick="mw.templatePreview.view(1);"    class="active" ><? print $data['layout_file'] ?></li>
            <? endif; ?>
            <? if(!empty($layouts)): ?>
              <? $i=1; foreach($layouts as $item): ?>
                   <?php $i++; ?>
                  <li value="<? print $item['layout_file'] ?>"  onclick="mw.templatePreview.view(<?php print $i; ?>);"   title="<? print $item['layout_file'] ?>"   <? if(($item['layout_file'] == $data['layout_file']) ): ?>   selected="selected"  <? endif; ?>   > <? print $item['name'] ?> </li>
              <? endforeach; ?>
            <? endif; ?>
          </ul>

      </div>
  </div>


</div>


<div class="mw_clear">&nbsp;</div>


