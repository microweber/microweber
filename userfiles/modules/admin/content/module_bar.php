<? 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;


$modules = CI::model('template')->getModules($modules_options );


//

?>
<?// p($params) ?>
 <script type="text/javascript">
	/*$(function() {
		var icons = {
			header: "ui-icon-circle-arrow-e",
			headerSelected: "ui-icon-circle-arrow-s"
		};
		$( "#module_accordion" ).accordion({
										   
			icons: icons
		});
	 
	}); */
	
		 $("#module_accordion div").draggable({
			connectToSortable: ".editblock",
		    helper: 'clone',
			revert: "invalid"
			
			//,            cursorAt: { left: -15, top:0 }
		});
		 
		 
		 
		 	 $("#module_bar_modules_holder div").draggable({
			connectToSortable: ".editblock",
		    helper: 'clone',
			revert: "invalid"
			
			//,            cursorAt: { left: -15, top:0 }
		});
		 
		 
</script>


  <? $showed_module_groups = array(); ?>
  <div id="module_bar" style="background-color:#FFF">
  
  
  
  
  <? if(intval($params['page_id']) != 0): ?>
 


  <? endif ?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
    <div id="module_accordion">
      <? foreach($modules as $module): ?>
      <?
 $module_group = explode(DIRECTORY_SEPARATOR ,$module['module']);
 $module_group = $module_group[0];

?>
      <? if(!in_array($module_group, $showed_module_groups))  : ?>
      <h3><a href="#"><? print ($module_group); ?></a></h3>
      <div>
        <? foreach($modules as $module2): ?>
        <?
 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
 $module_group2 = $module_group2[0];

?>
        <? if($module_group2 == $module_group)  : ?>
        <div>
          <? if($module2['icon']): ?>
          <img src="<? print $module2['icon'] ?>" height="32" style="float:left" />
          <? endif; ?>
          <? print $module2['name'] ?> <? print $module2['description'] ?>
          <textarea style="display: none">
          
          <nomw><microweber module="<? print $module2['module'] ?>" /></nomw>
          
</textarea>
        </div>
        <br />        <br />
        <hr />
        <? endif; ?>
        <? endforeach; ?>
      </div>
      <? endif; ?>
      <?  $showed_module_groups[] = $module_group; ?>
      <? endforeach; ?>
    </div>
    <div id="module_bar_modules_holder">
      <div class="bar_module">
        <textarea style="display: none" id="qwewqreeq"><nomw><microweber module="content/content" /></nomw>
</textarea>
      </div>
    </div>
  </div>