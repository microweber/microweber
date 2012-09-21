 <?  
 $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 
  $for_id =$params['id'];
 if(isset($params['to_table_id'])){
$for_id =$params['to_table_id'];
 }
 
 
 $more = get_custom_fields($for ,$for_id,1); 
 
 
 ?>
<?

if(!empty($more )): ?>
<? foreach($more  as $field): ?>
<?
 print  make_field($field);
   ?>
<? endforeach; ?>
<? else: ?>
<? //_e("You don't have any custom fields."); ?>
<? endif; ?>