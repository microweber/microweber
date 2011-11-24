<script type="text/javascript">
	function delete_menu_item($delete_menu_item){
	
	
	var answer = confirm("Sure?")
	if (answer){
		$.post("<?php print site_url('admin/content/menus_delete_menu_item') ?>", { delete_menu_item: $delete_menu_item },
  function(data){
 //alert(data); 
 $("#menu_item_id_" +$delete_menu_item ).addClass("light_red_background");
  $("#menu_item_id_" +$delete_menu_item  ).fadeOut();
  
  });
	}
	else{
	//	alert("Thanks for sticking around!")
	}


	}
	
	
	
	
	
		function delete_menu($id){
	
	
	var answer = confirm("Sure?")
	if (answer){
	window.location= '<?php print $this->uri->uri_string(); ?>/delete:'+ $id ; 
	}
	else{
	//	alert("Thanks for sticking around!")
	}


	}
	
	
	
	
	
	
	
	
	
	function menu_item_update_placeholder($id){
	$.get("<?php print site_url('admin/content/menus_show_menu_ajax') ?>/id:"+$id, { name: "John", time: "2pm" },
  function(data){
    $("#menu_container_"+$id).html(data);
  });
	
	}
	
	
	function menu_item_change_url($id){
	
	
	}
	
	
	
	
</script>
<script type="text/javascript">
function select_category_for_menu_item($id, $form_id, $type){


if($type == "category"){

$("#"+$form_id+"_taxonomy_id").val($id);
$("#"+$form_id+"_content_id").val(0);
$("#"+$form_id+"_menu_url").val(0);

}


if($type == "page"){

$("#"+$form_id+"_taxonomy_id").val(0);
$("#"+$form_id+"_content_id").val($id);
$("#"+$form_id+"_menu_url").val(0);
}



//alert($id+$form_id+$type); 
tb_remove();
}
</script>

<div class="boo">
  <table border="0" cellspacing="3" cellpadding="3" width="100%">
    <?php foreach($menus as $item): ?>
    <tr>
      <td><form action="<?php print $this->uri->uri_string(); ?>" method="post" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-weight:bold;">
            <tr>
              <td><label>Menu unique id:
                <input name="item_title" type="text" value="<?php print $item['item_title']; ?>">
                </label></td>
              <td><label>Title:
                <input name="menu_title" type="text" value="<?php print $form_values['menu_title']; ?>">
                </label></td>
              <td><label>Description:
                <input name="menu_description" type="text" value="<?php print $form_values['menu_description']; ?>">
                </label></td>
              <td><label>is_active:
                <select name="is_active">
                  <option  <?php if($item['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                  <option  <?php if($item['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                </select>
                </label></td>
              <td><input name="Save" value="Save" type="submit">
              </td>
              <td><input name="Delete" value="Delete" type="button" onClick="delete_menu('<?php print $item['id']; ?>')" />
                <input name="id" type="hidden" value="<?php print $item['id']; ?>"></td>
            </tr>
          </table>
        </form>
        <?php $this->firecms = get_instance();  
$menu_items = $this->firecms->content_model->getMenuItems($item['id']);
//var_dump($menu_items); 
?>
        <?php if(!empty($menu_items)): ?>
        <?php foreach($menu_items as $menu_item): ?>
        <form  action="<?php print site_url('admin/content/menus_save_menu_item') ?>" method="post" id="menu_item_form_<?php print $menu_item['id'] ;  ?>" enctype="multipart/form-data">
          <table border="1" width="100%"  id="menu_item_id_<?php print $menu_item['id'] ;  ?>">
            <tr>
              <td><?php print trim($menu_item['id'] );  ?></td>
              <td><label>Title:
                <input name="item_title" type="text" value="<?php print trim($menu_item['item_title'] );  ?>" />
                </label></td>
              <td><?php $img = $this->content_model->menusGetThumbnailImageById($menu_item['id'], 24); ?>
                <?php if($img != false) : ?>
                <img src="<?php print($img); ?>" />
                <?php endif ; ?>
                
                <?php $unique_pic_id = 'pic_'.md5(rand()); ?>
                <a href="#TB_inline?height=100&width=300&inlineId=<?php print $unique_pic_id ; ?>&modal=false" class="thickbox">Change picture</a>
               <div id="<?php print $unique_pic_id ; ?>" style="display:none">
              <input name="picture_1" type="file"  />
              <input type="button" name="Close" value="Close" onclick="javascript:tb_remove()"  />
              </div>
                
                
                
             
              </td>
              <td><label>is_active:
                <select name="is_active">
                  <option  <?php if($menu_item['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                  <option  <?php if($menu_item['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                </select>
                </label></td>
              <td><?php if($menu_item['content_id'] != 0):  ?>
                <?php $url = $this->content_model->contentGetHrefForPostId($menu_item['content_id']) ;  ?>
                <!--
                <label><a href="<?php print $this->content_model->contentGetHrefForPostId($menu_item['content_id']) ; ?>" target="_blank">url</a>:
                <input name="menu_url_no_such_thing" type="text" style="width:400px;"   disabled="disabled"    id="menu_item_form_<?php print $menu_item['id'] ;  ?>_menu_url_no_such_thing"     value="<?php print $this->content_model->contentGetHrefForPostId($menu_item['content_id']) ; ?>" />
                </label>-->
                <?php elseif($menu_item['taxonomy_id'] != 0):  ?>
                <?php $url = $this->taxonomy_model->getUrlForId($menu_item['taxonomy_id']);    ?>
                <!--<a href="<?php print $url ; ?>" target="_blank">url</a>:
                <input name="menu_url_no_such_thing" type="text" style="width:400px;" id="menu_item_form_<?php print $menu_item['id'] ;  ?>_menu_url_no_such_thing"  disabled="disabled" value="<?php print $url ; ?>" />-->
                <?php else : ?>
                <?php $url =  trim($menu_item['menu_url'] );  ?>
                <!-- <label><a href="<?php print trim($menu_item['menu_url'] );  ?>" target="_blank">url</a>:
                <input name="menu_url" type="text"   id="menu_item_form_<?php print $menu_item['id'] ;  ?>_menu_url"   style="width:400px;"   value="<?php print trim($menu_item['menu_url'] );  ?>" /></label>-->
                <?php endif; ?>
                <label><a href="<?php print $url; ?>" target="_blank">url</a>:<?php print $url; ?></label>
                <a href="<?php print site_url('admin/content/menus_edit_small_menu_item') ?>/id:<?php print $menu_item['id']; ?>/form:menu_item_form_<?php print $menu_item['id'] ;  ?>/?height=500&width=400&modal=false" class="thickbox" title="Change item">change</a>
                <input name="content_id" type="hidden" id="menu_item_form_<?php print $menu_item['id'] ;  ?>_content_id"  value="<?php print $menu_item['content_id'] ;  ?>"  />
                <input name="taxonomy_id" type="hidden" id="menu_item_form_<?php print $menu_item['id'] ;  ?>_taxonomy_id"   value="<?php print $menu_item['taxonomy_id'] ;  ?>"     />
                <input name="menu_url" type="hidden" id="menu_item_form_<?php print $menu_item['id'] ;  ?>_menu_url"   value="<?php print $menu_item['menu_url'] ;  ?>"     />
              </td>
              <td><input name="id" type="hidden" value="<?php print $menu_item['id']; ?>">
                <input name="Save" value="Save" type="submit"></td>
              <td><a href="<?php print $this->uri->uri_string(); ?>/move_up:<?php print $menu_item['id'] ;  ?>">up</a></td>
              <td><a href="<?php print $this->uri->uri_string(); ?>/move_down:<?php print $menu_item['id'] ;  ?>">down</a></td>
              <td><a href="javascript:delete_menu_item('<?php print $menu_item['id'] ;  ?>');">delete</a></td>
            </tr>
          </table>
        </form>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php $unique_form_id = md5(rand()); ?>
        <?php $unique_pic_id = 'pic_'.md5(rand()); ?>
        <form   action="<?php print site_url('admin/content/menus_save_menu_item') ?>"   method="post" id="menu_item_form_<?php print $unique_form_id ;  ?>" enctype="multipart/form-data">
          <table border="1" width="100%">
            <tr>
              <td>New</td>
              <td><label>Title:
                <input name="item_title" type="text" value="" />
                </label></td>
              <td>
              <a href="#TB_inline?height=100&width=300&inlineId=<?php print $unique_pic_id ; ?>&modal=false" class="thickbox">Add picture</a>
               <div id="<?php print $unique_pic_id ; ?>" style="display:none">
              <input name="picture_1" type="file"  />
              <input type="button" name="Close" value="Close" onclick="javascript:tb_remove()"  />
              </div>
              
              
              
              </td>
              <td><label>active?:
                <select name="is_active">
                  <option    selected="selected"    value="y">yes</option>
                  <option    value="n">no</option>
                </select>
                </label></td>
              <td><input name="item_parent" type="hidden" value="<?php print $item['id']; ?>">
                <!--<label>url:
                <input name="menu_url" type="text" value="" />
                </label>-->
                 <a href="<?php print site_url('admin/content/menus_edit_small_menu_item') ?>/id:<?php print $unique_form_id ;  ?>/form:menu_item_form_<?php print $unique_form_id ;  ?>/?height=500&width=400&modal=false" class="thickbox" title="Change item">url</a>
                <input name="content_id" type="hidden" id="menu_item_form_<?php print $unique_form_id ;  ?>_content_id"  value=""  />
                <input name="taxonomy_id" type="hidden" id="menu_item_form_<?php print $unique_form_id ;  ?>_taxonomy_id"   value=""     />
                <input name="menu_url" type="hidden" id="menu_item_form_<?php print $unique_form_id ;  ?>_menu_url"   value=""     />
              </td>
              <td><input name="Save" value="Save" type="submit"></td>
            </tr>
          </table>
        </form>
        <br />
        <br />
        <hr />
        <br />
        <br />
      </td>
    </tr>
    <?php endforeach ?>
  </table>
</div>
<br />
<br />
<hr />
<br />
<br />
<form action="<?php print $this->uri->uri_string(); ?>" method="post" enctype="multipart/form-data">
  <fieldset>
  <legend>
  <h1>Add/Edit menu</h1>
  </legend>
  <label>Menu title:
  <input name="item_title" type="text" value="<?php print $form_values['item_title']; ?>">
  </label>
  <label>is_active:
  <select name="is_active">
    <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
    <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
  </select>
  </label>
  <label>Title:
  <input name="menu_title" type="text" value="<?php print $form_values['menu_title']; ?>">
  </label>
  <label>Description:
  <input name="menu_description" type="text" value="<?php print $form_values['menu_description']; ?>">
  </label>
  <input name="Save" value="Save" type="submit">
  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  </fieldset>
</form>
</div>
