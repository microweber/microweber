<? if (!empty($data)) : ?>
<? $vals = $params['value']; 

if( $vals == false){
	$vals = $data['param_values']; 
	
	
}
if( $vals != false){
	$vals = explode(',',$vals);
}
?>

    
            
          
          
 
    <? if(!empty($vals)) :?>
    <ul>
    <? foreach($vals as $val): ?>
    <div>
    
    <li><label for=""><input type="checkbox" class="custom_field_radio custom_field_<?  print $data['param'];  ?>" value="<? print $val  ?>"  name="custom_field_<?  print $data['param'];  ?>"      <?  if( $data['param_default'] == $val) : ?> checked="checked"   <? endif; ?>  <?  if( $data['help']) : ?> title="<?  print addslashes($data['help']);  ?>"   <? endif; ?>     >
              <? print $val  ?></label></li>
            
            
    
 
</div>
    <? endforeach; ?>
    </ul>
    <? endif; ?>
  
 
<?  endif; ?>










 