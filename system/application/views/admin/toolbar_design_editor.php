<? 
$elements2 = CI::model('template')->getDesignElementsDirs();
//p($elements2 );
$elements = CI::model('template')->getDesignElements();

$showed_element_groups = array(); ?>

<div class="mw_admin_sidebar_text" > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/drag.png" hspace="5" style="float:left" /> Drag and drop the desired layout in your website <a target="_blank" href="http://microweber.com">(see how)</a> </div>
<div class="module_bar">
  <div class="sortable_modules">
    <? if(intval($params['page_id']) != 0): ?>
    <? endif ?>
    <? foreach($elements2 as $element_dir): ?>
    <? //	p($element);
	$o = array();
	$o['dir_name'] = $element_dir;
	
	
	$elements = CI::model('template')->getDesignElements($o);
	 
 //$element_group = explode(DIRECTORY_SEPARATOR ,$element['module']);
 $element_group = $element_dir; ?>
    <span class="mw_sidebar_module_group_title"><a href="#" class="mw_sidebar_module_group_title"><? print ($element_group); ?></a></span>
    <div class="mw_sidebar_module_group_div">
      <div class="mw_sidebar_module_group_div_roundtop"></div>
      <table border="0" cellspacing="0" cellpadding="0" class="mw_sidebar_module_group_table">
        <tr>
          <td><ul class="mw_sidebar_layouts_lists_to_drop">
              <? foreach($elements as $element2): ?>
         <? // p($element2) ;?>
              <li>
             
                <div class="module_draggable mw_no_module_mask"  title="<? print addslashes($element2['description']); ?>">
                  <div class="js_mod_remove">
                    <? if($element2['icon']): ?>
                    <div class="mw_sidebar_module_icon"> <img src="<? print $element2['icon'] ?>" height="40"  /> </div>
                    <? endif; ?>
                    <div class="mw_sidebar_module_insert"></div>
                    <? //print $element2['name'] ?>
                    <textarea id="md5_module_<? print md5($element2['module']) ?>" style="display: none"><? print  $element_dir. '/'.$element2['module'] ?></textarea>
                  </div>
                  <tag_to_remove_add_module_string element="<? print $element_dir. '/'.$element2['module'] ?>" />
                </div>
              </li>
             
              <? endforeach; ?>
            </ul></td>
        </tr>
      </table>
      <div class="mw_sidebar_module_group_div_roundbottom"></div>
    </div>
    <br />
    <br />
    <?  $showed_element_groups[] = $element_group; ?>
    <? endforeach; ?>
  </div>
</div>
