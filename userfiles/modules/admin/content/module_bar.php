<? 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;


$modules = CI::model('template')->getModules($modules_options );


//

?>
<? // p($params) ?>
<script type="text/javascript">
	 
	$(function() {
	
	 init_edits() 
	}); 
	
		
	
	
	function init_bar() {
		//var item = $('table.mw_sidebar_module_group_table');
// do something
//item.find('.module_draggable').html('aaa');



		//$('.mw_sidebar_module_group_table').find('div.module_draggable').css('background-color', 'red'); 
		
		
		
		// $(".mw_sidebar_module_group_table").each(function (index) {
       
	   
	  // $(this).find('.module_draggable').last().css('background-color', 'red');
	   
	 //   var val = $(this).find('.module_draggable:last-child').addClass('module_draggable_last');
   // alert('i selected: ' + val);



	   
	   
 
	   
	   
	   
     
		 
		 
	 
		
	//	$(".mw_sidebar_module_group_table .module_draggable:last").addClass("module_draggable_last");
		
		
 
/*  $(".module_accordion li").draggable({
			connectToSortable: ".editblock",
		    helper: 'clone',
			revert: "invalid"
			
			//,            cursorAt: { left: -15, top:0 }
		});
  
  $(".module_accordion li").draggable({
			connectToSortable: ".edit",
		    helper: 'clone',
			revert: "invalid"
			
			//,            cursorAt: { left: -15, top:0 }
		});
		 
		 
		 
		 	 $(".module_bar_modules_holder li").draggable({
			connectToSortable: ".editblock",
		    helper: 'clone',
			revert: "invalid"
			
			//,            cursorAt: { left: -15, top:0 }
		});
			 
			 
	 */
				 
				 		   
							 
							 
							 		 
				 		  	  
							 
							 
							 
	//$(".module_bar").disableSelection();
	//$( ".edit" ).draggable({ cancel: '.module'});
		 init_edits() 
 }
 window.onload = init_bar;
 
 
 
 
 
</script>
<? $showed_module_groups = array(); ?>



<div class="module_bar">
  <div class="sortable_modules">
    <? if(intval($params['page_id']) != 0): ?>
    <? endif ?>
    <? foreach($modules as $module): ?>
    <?
 $module_group = explode(DIRECTORY_SEPARATOR ,$module['module']);
 $module_group = $module_group[0];

?>
    <? if(!in_array($module_group, $showed_module_groups))  : ?>
    <span class="mw_sidebar_module_group_title"><a href="#" class="mw_sidebar_module_group_title"><? print ($module_group); ?></a></span>
    <div class="mw_sidebar_module_group_div">
      <div class="mw_sidebar_module_group_div_roundtop"></div>
      <table  border="0" cellspacing="0" cellpadding="0" class="mw_sidebar_module_group_table">
        <? foreach($modules as $module2): ?>
        <?
		 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
		 $module_group2 = $module_group2[0];
		?>
        <? if($module_group2 == $module_group)  : ?>
        <tr valign="middle">
          <td><? if($module_group2 == $module_group)  : ?>
          <? $module2['module'] = str_replace('\\','/',$module2['module']); ?>
            <div class="module_draggable mw_no_module_mask">
              <div class="js_mod_remove">
                <? if($module2['icon']): ?>
                <div class="mw_sidebar_module_icon"> <img src="<? print $module2['icon'] ?>" height="24" style="float:left" /> </div>
                <? endif; ?>
                <div class="mw_sidebar_module_insert"></div>
                <? print $module2['name'] ?><? //print $module2['module'] ?>
                <textarea id="md5_module_<? print md5($module2['module']) ?>" style="display: none"><? print $module2['module'] ?></textarea>
              </div>
              <tag_to_remove_add_module_string module="<? print $module2['module'] ?>" mw_params_module="<? print $module2['module'] ?>" class="mw_put_module_ids" />
            </div>
            <? endif; ?></td>
        </tr>
        <? endif; ?>
        <? endforeach; ?>
      </table>
      <div class="mw_sidebar_module_group_div_roundbottom"></div>
    </div>
    <br />
    <br />
    <? endif; ?>
    <?  $showed_module_groups[] = $module_group; ?>
    <? endforeach; ?>
  </div>
</div>
<div class="modddddule_bar">
  <? if(intval($params['page_id']) != 0): ?>
  <? endif ?>
  <div class="moddddule_accordion">
    <? foreach($mdgdgdgodules as $module): ?>
    <?
 $module_group = explode(DIRECTORY_SEPARATOR ,$module['module']);
 $module_group = $module_group[0];

?>
    <? if(!in_array($module_group, $showed_module_groups))  : ?>
    <span class="mw_sidebar_module_group_title"><a href="#" class="mw_sidebar_module_group_title"><? print ($module_group); ?></a></span>
    <div class="mw_sidebar_module_group_div">
      <div class="mw_sidebar_module_group_div_roundtop"></div>
      <table  border="0" cellspacing="0" cellpadding="0" class="mw_sidebar_module_group_table">
        <? foreach($modules as $module2): ?>
        <?
		 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
		 $module_group2 = $module_group2[0];
		?>
        <? if($module_group2 == $module_group)  : ?>
        <tr valign="middle">
          <td><? if($module_group2 == $module_group)  : ?>
            <div class="module_draggable mw_no_module_mask">
              <? if($module2['icon']): ?>
              <div class="mw_sidebar_module_icon"> <img src="<? print $module2['icon'] ?>" height="24" style="float:left" /> </div>
              <? endif; ?>
              <!--      <a onclick="mw_insert_module_at_cursor('<? print urlencode($module2['module']) ?>')" href="#">insert</a>;-->
              <? print $module2['name'] ?>
              <textarea id="md5_module_<? print md5($module2['module']) ?>" style="display: none"><? print $module2['module'] ?></textarea>
            </div>
            <? endif; ?></td>
        </tr>
        <? endif; ?>
        <? endforeach; ?>
      </table>
      <div class="mw_sidebar_module_group_div_roundbottom"></div>
    </div>
    <br />
    <br />
    <? endif; ?>
    <?  $showed_module_groups[] = $module_group; ?>
    <? endforeach; ?>
  </div>
</div>
