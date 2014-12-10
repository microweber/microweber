<?php if(is_admin()==false) {
    return array('error' => 'Not logged in as admin');
} ?>
<script  type="text/javascript">

  mw.require('<?php print $config['url_to_module']; ?>forms_data_manager.js');

  </script>
<script  type="text/javascript">


toggle_show_less = function(el){
    var el = $(el);
    el.prev().toggleClass('semi_hidden');
    var html = el.html();
    el.html(el.dataset("later"));
    el.dataset("later", html);
}



  </script>
<?php

$data = array();
 if(isset($params['load_list'])){
	 if($params['load_list'] == 'default'){
		$params['load_list'] = '0';
	 }
$data['list_id'] =$params['load_list'];
}

if(isset($params['keyword'])){
   $data['keyword'] =$params['keyword'];
 }
 if(isset($params['for_module'])){
	 $data['module_name'] =$params['for_module'];
 }

 $custom_fields = array();

 
$data_paging = $data;
$data_paging['page_count'] = 1;


 $data_paging = get_form_entires($data_paging);

if((url_param('current_page') != false)){
$data['current_page'] = url_param('current_page');
}

$custom_fields = array();
 $data = get_form_entires($data);
if(is_array($data)){
  foreach ($data as $item) {
   if(isset($item['custom_fields'])){
    foreach ($item['custom_fields'] as $k=>$value) {
     $custom_fields[$k] =$value;
    }
   }
  }
}
 
?>

<table id="table_data_<?php print $params['id'] ?>" cellspacing="0" cellpadding="0" width="100%" class="mw-ui-table">
  <col width="20">
  <thead>
    <tr>
      <th class="mw-ui-table-small"><?php _e("ID"); ?></th>
      <?php if(is_array($custom_fields )): ?>
      <?php foreach($custom_fields   as $k=>$item): ?>
      <th><?php print   mw('format')->no_dashes($k); ?></th>
      <?php endforeach ; ?>
      <?php endif; ?>
      <th width="20" class="mw-ui-table-small"><?php _e("Delete"); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td class="mw-ui-table-small"><?php _e("ID & Date"); ?></td>
      <?php if(is_array($custom_fields )): ?>
      <?php foreach($custom_fields   as $k=>$item): ?>
      <td><?php print   mw('format')->no_dashes($k); ?></td>
      <?php endforeach ; ?>
      <?php endif; ?>
      <td width="20" class="mw-ui-table-small"><?php _e("Delete"); ?></td>
    </tr>
  </tfoot>
  <tbody>
    <?php if(is_array($data)): ?>
    <?php foreach ($data as $item) : ?>
    <tr class="mw-form-entry-item mw-form-entry-item-<?php print $item['id'] ?>">
      <td width="50" style="text-align: center"><?php print $item['id'] ?>
        <div class="mw-date" title="<?php print mw('format')->ago($item['created_at'],1); ?>"><?php print mw('format')->date($item['created_at']);; ?></div></td>
      <?php if(is_array($custom_fields )): ?>
      <?php foreach($custom_fields   as  $key =>$value): ?>
      <td><?php
      		 
      		    $values_plain = mw('format')->clean_html( $value);;
      	 
            $max = 150;
             if(strlen($values_plain) > $max){
                $first = substr($values_plain, 0, $max);
                $rest = substr($values_plain, $max);
                print '<div>' . $first. '<span class="semi_hidden">'.$rest.'</span> <a href="javascript:;" onclick="toggle_show_less(this);" class="mw-ui-link" data-later="Less"> ...more</a></div>';
             }
             else {
           print mw('format')->autolink( $value);
             }
        ?></td>
      <?php endforeach ; ?>
      <?php endif; ?>
      <td class="mw-ui-table-delete-item"><a class="show-on-hover mw-icon-close" href="javascript:mw.forms_data_manager.delete('<?php print $item['id'] ?>','.mw-form-entry-item-<?php print $item['id'] ?>');"></a></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td colspan="100" align="center" style="background: #FFFD8C;"><?php _e("No items found"); ?></td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>
<?php if(is_array($data)) :?>
<div class="mw-paging left"> <?php print paging("num=$data_paging"); ?> </div>
<?php if(isset($params['export_to_excel'])) : ?>
<?php endif; ?>
<?php if(isset($params['export_to_excel'])) : ?>
<?php endif; ?>
<?php endif; ?>
<div id="start-email-campaign"> <a class="mw-ui-btn pull-right" href="javascript:;" onclick="Alert('<?php _e("Coming Soon"); ?>!');" >
  <?php _e("Start an Email Campaign"); ?>
  </a> <span class="pull-right" style="margin: 9px 20px 0 0;">
  <?php _e("Get more from your mailing lists, send email to your users"); ?>
  </span> </div>
