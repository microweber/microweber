<?
 
//p(TEMPLATE_DIR);

$id = $params['content_id'];
  $for = $params['for'];
  
  $size = $params['size'];
  
  $queue_id = $params['queue_id'];
  
  
   
  $media_type = $params['type'];
  if($media_type == false){
	  
	$media_type = 'picture';  
  }
  
  $to_table = $params['for'];;
    if($to_table == false){
	  $to_table = 'table_content';
  }
  
  $collection = $params['collection'];;
   
  
  
  $to_table_id = $params['for_id'];;
    if($to_table_id == false){
	$to_table_id = $params['content_id'];
  }
  
   // $CI = get_instance ();
  
 	$media1 =get_instance()->core_model->mediaGet($to_table, $to_table_id, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false, $id = false, $collection );
	//p( $media1 );
if(!empty($media1)){
  $media1 = $media1['files'];
}
   
  ?>
 
 

 

            
            <?php if(empty($media1)  ): ?>
            <div class="no-files-uploaded">
            No files uploaded
            </div>
<?php else : ?>
            
            
            
            
            <?php $i = 1; if(!empty($media1)): ?>
            
            <table  class="files_list_table">
 <?php foreach($media1 as $pic): ?>
  <tr class="file_id_<?php print ($pic['id']); ?>">
    <td><input type="hidden" class="file_id_<?php print ($pic['id']); ?>" name="attached_file_<? print $i; ?>" value="<?php print ($pic['url']); ?>" />
            
            <a href="<?php print ($pic['url']); ?>"  title="<?php print addslashes($pic['media_name']); ?>"  alt="<?php print addslashes($pic['media_description']); ?>" ><?php print ($pic['filename']); ?></a></td>
    <td><a href="javascript:mw.media.del('<?php print ($pic['id']); ?>', '#file_id_<?php print ($pic['id']); ?>') " >[x]</a></td>
  </tr>
   
  <?php $i++; endforeach; ?>
    
</table>

            
            
            
            
            
            
            
            
            <?php foreach($media1 as $pic): ?>
            
            <?php $i++; endforeach; ?>
           
           
           
            <?php endif; ?>
        
            <?php endif; ?>


 