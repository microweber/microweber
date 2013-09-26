<?php
if(!is_admin()){
return;	
}
$save_url = site_url('module?type=settings___template');

$tpl_settings_for_theme = TEMPLATE_DIR.'template_settings.php';
 
  
if(!is_file($tpl_settings_for_theme)){
return;	
}



if (isset($_POST['save_template_settings'])) {
    $css = "";
	
	if (isset($_POST['save_template_settings'])) {
	 unset($_POST['save_template_settings']);
	}
	if (isset($_POST['module'])) {
	 unset($_POST['module']);
	}
	if (isset($_POST['type'])) {
	 unset($_POST['type']);
	}
    $json = json_encode($_POST);
    foreach($_POST as $a=>$b){
       $props = explode(',', $b['property']);
       $curr = "";
       foreach($props as $prop){
           $curr.= $prop . ":" .$b['value'] .";";
       }
       $css .= $b['selector']."{".$curr."}". "\n\r\n";
    }


$tpl_settings = TEMPLATE_DIR.'template_settings.css';
 
 
  //$chmod = chmod("/userfiles/templates/default/settings/", 0755);
  //$fp = fopen($tpl_settings, "w+");


//fwrite($fp,$css);
//fclose($fp);


    $option = array();
    $option['option_value'] = $json;
    $option['option_key'] = 'template_settings';
    $option['option_group'] = 'template_'.TEMPLATE_NAME;
    save_option($option);
   file_put_contents($tpl_settings,$css);
  
   return;
} 
$arr = array();
    $json = get_option('template_settings', 'template_'.TEMPLATE_NAME);
	if($json != false){
     $arr = json_decode($json, true);
	}




include($tpl_settings_for_theme);
    ?>



 





<script>

mw.tpl = {
  save:function(){
    var u = "<?php print $save_url; ?>",  obj = {}, m = mwd.getElementById('mw-template-settings');
    mw.$(".tpl-field", m).each(function(){
        var name = this.name;
		obj.module= "settings/template";
		obj.save_template_settings= true;
        obj[name] = {
            selector:$(this).dataset("selector"),
            value:this.value,
            property:$(this).dataset("property")
        }
    });
    $.post(u,obj, function(){
      Alert('Saved');
    })
  }
}

mw.tools.modal.init({
  html:mwd.getElementById('mw-template-settings').innerHTML,
  draggable:true,
  callback:function(){

  }
});

mw.$("#mw-template-settings-holder").remove();

</script>

