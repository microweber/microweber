<? 

$form_id='form_'.rand();
if(strstr($params['id'], '_')){
				  $params['id'] = explode('_', $params['id']);
				   $params['id'] =  end($params['id']);;
				  }


$params['id'] = intval($params['id'] );


//p($params);


$menu = CI::model('content')->getMenuItemById($params['id']) ;
//p($menu);

?>
 
    
    
 
<form id="<? print $form_id?>">
<table width="100%" border="0">
  
  
  
  
  <tr>
    <td>
    
    <input name="item_parent" value="<? print $menu['item_parent']?>" type="hidden" /> 
    <input name="save" type="button" onClick="save_edited_item('<? print $form_id?>')" value="save">
    
    <input name="delete" type="button" onClick="delete_edited_item('<? print $form_id?>')" value="delete">
    
    id</td>
    <td><input name="id" value="<? print $menu['id']?>" type="text" /></td>
  </tr>
  
 
     
 
  
  <tr>
    <td>item_title</td>
    <td><input name="item_title" value="<? print $menu['item_title']?>" type="text" /></td>
  </tr>
  
  
  <tr>
    <td>content_id</td>
    <td><input name="content_id" value="<? print $menu['content_id']?>" type="text" /></td>
  </tr>
  
  
  
  <tr>
    <td>is_active</td>
    <td><input name="is_active" value="<? print $menu['is_active']?>" type="text" /></td>
  </tr>
  
  
  <tr>
    <td>position</td>
    <td><input name="position" value="<? print $menu['position']?>" type="text" /></td>
  </tr>
  
  
  <tr>
    <td>menu_description</td>
    <td><input name="menu_description" value="<? print $menu['menu_description']?>" type="text" /></td>
  </tr>
  
   <tr>
    <td>menu_title</td>
    <td><input name="menu_title" value="<? print $menu['menu_title']?>" type="text" /></td>
  </tr>
  
   <tr>
    <td>menu_url</td>
    <td><input name="menu_url" value="<? print $menu['menu_url']?>" type="text" /></td>
  </tr>
   <tr>
    <td>taxonomy_id</td>
    <td><input name="taxonomy_id" value="<? print $menu['taxonomy_id']?>" type="text" /></td>
  </tr>
  
   <tr>
    <td>the_url</td>
    <td><input name="the_url" value="<? print $menu['the_url']?>" type="text" /></td>
  </tr>
  
  
  
  
 
  
</table>





</form>