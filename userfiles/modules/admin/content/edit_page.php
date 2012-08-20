<? $data = get_content_by_id(PAGE_ID); 

if(empty($data )){
$data  = array();
$data['id'] = 0;	
$data['content_type'] = 'page';	
$data['content_title'] = 'Title';	
$data['content_url'] = '';	
$data['is_active'] = 'y';	
$data['content_subtype'] = '';	
$data['content_description'] = '';	
$data['active_site_template'] = '';	
$data['content_subtype_value'] = '';	
$data['content_parent'] = 0;	
$data['content_layout_name'] = '';		
$data['content_layout_file'] = '';		
}
?>

<pre>
 <? print_r($data); ?></pre>
<form>
  id
  <input name="id"  type="text" value="<? print ($data['id'])?>" />
  <br />
  content_parent
  <input name="content_parent"  type="text" value="<? print ($data['content_parent'])?>" />
  <br />
  content_type
  <input name="content_type"  type="text" value="<? print ($data['content_type'])?>" />
  <br />
  content_title
  <input name="content_title"  type="text" value="<? print ($data['content_title'])?>" />
  <br />
  content_url
  <input name="content_url"  type="text" value="<? print ($data['content_url'])?>" />
  <br />
  is_active
  <input name="is_active"  type="text" value="<? print ($data['is_active'])?>" />
  <br />
  content_subtype
  <input name="content_subtype"  type="text" value="<? print ($data['content_subtype'])?>" />
  <br />
  content_description
  <input name="content_description"  type="text" value="<? print ($data['content_description'])?>" />
  <br />
  content_layout_name
  <input name="content_layout_name"  type="text" value="<? print ($data['content_layout_name'])?>" />
  <br />
  content_layout_file
  <input name="content_layout_file"  type="text" value="<? print ($data['content_layout_file'])?>" />
  <br />
  active_site_template
  <input name="active_site_template"  type="text" value="<? print ($data['active_site_template'])?>" />
  <br />
  content_subtype_value
  <input name="content_subtype_value"  type="text" value="<? print ($data['content_subtype_value'])?>" />
  <br />
</form>
