
    <? $v = ( url_param('action', true) );?>
      
 <? if($v) {
	 
	 include("snippets/".$v.'.php'); 
	 
	 
 }  else { ?>
<? 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;


$modules = CI::model('template')->getModules($modules_options );


//

?>

<div class="mercury-snippet-panel">
  <div class="filter">
    <input class="filter" type="text">
  </div>
  <ul>
  <? foreach($modules as $module): ?>
    <?
 $module_group = explode(DIRECTORY_SEPARATOR ,$module['module']);
 $module_group = $module_group[0];

?>
    <? if(!in_array($module_group, $showed_module_groups))  : ?>
    
    
    
    
    
    
    
    
        <? foreach($modules as $module2): ?>
        <?
		 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
		 $module_group2 = $module_group2[0];
		?>
        <? if($module_group2 == $module_group)  : ?>
        
          <? if($module_group2 == $module_group)  : ?>
          <? $module2['module'] = str_replace('\\','/',$module2['module']); ?>
                    <? $module2['module_clean'] = str_replace('/','__',$module2['module']); ?>
                    <? $module2['name_clean'] = str_replace('/','-',$module2['module']); ?>
                                        <? $module2['name_clean'] = str_replace(' ','-',$module2['name_clean']); ?>


          
          
          
          
           <li data-filter="editable, snippet, <? print $module2['name'] ?>"> 
           <? if($module2['icon']): ?>
                            <img alt="<? print $module2['name'] ?>" data-snippet="<? print $module2['module_clean'] ?>|<? print $module2['name_clean'] ?>_<? print date("YmdHis") ?>" src="<? print $module2['icon'] ?>" height="24" />

                <? endif; ?>
      <h4><? print $module2['name'] ?></h4>
    <? //print $module2['module'] ?>
                  <tag_to_remove_add_module_string module="<? print $module2['module'] ?>" mw_params_module="<? print $module2['module'] ?>" class="mw_put_module_ids" />

                <textarea id="md5_module_<? print md5($module2['module']) ?>" style="display: none"><? print $module2['module'] ?></textarea>
      <div class="description">A one to two line long description of what this snippet does.</div>
    </li>
          
          
           
            <? endif; ?> 
         
        <? endif; ?>
        <? endforeach; ?>
      
    
    <? endif; ?>
    <?  $showed_module_groups[] = $module_group; ?>
    <? endforeach; ?>
    
    
    <li data-filter="editable, snippet, favorite, beer"> <img alt="Snippet Name" data-snippet="editable" src="http://www.bytemycode.com/img/icon_snippet.gif"/>
      <h4>Snippet Name</h4>
      <div class="description">A one to two line long description of what this snippet does.</div>
    </li>
  </ul>
</div>
 
<?  }   ?>