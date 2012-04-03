<?  

  p( $params); 

$orig_params = $params;
$add_field = $params;
      
if($params['custom_fields']){
$add_fields =   decode_var($params['custom_fields']);
if(is_array($add_fields)){
    $fields1 = $add_fields;
    $add_field = $add_fields[0];
}
}

if($params['custom_fields']){
    
    
}


if($params['post_id']){
$the_post =   get_post($params['post_id']);
//p($the_post)
}


if($params['user_id']){
$f =    get_custom_fields_for_user($params['user_id'], $field_name = $params['name']);
p($f );
}



?> 
<? if($orig_params['autosave']): ?>
 aaaaacustom_field_<? print  md5($add_field['name']) ?>
     <script src="<? print $config['url_to_module'] ?>views/custom_fields/jquery.MultiFile.js" type="text/javascript" ></script>
   <script>        
        
     $(document).ready(function() { 
 
 
$('#custom_field_<? print  md5($add_field['name']) ?>').MultiFile({
accept:'gif|jpg', max:3, STRING: {
remove:'Remover',
selected:'Selecionado: $file',
denied:'Invalido arquivo de tipo $ext!',
duplicate:'Arquivo ja selecionado:\n$file!'
}
});
 
});
      
    </script>    

<script type="text/javascript">
        $(document).ready(function() { 
 //  createUploader<? print  md5($add_field['name']) ?>()
    }); 
     
</script>
<? endif; ?>

<? if($orig_params['edit']): ?>
<?php switch(strtolower($orig_params['type'])): ?>
<?php case 'textarea': ?>

<textarea class="custom-field-edit <? print strtolower($add_field['type']) ?>" id="custom_field_<? print  md5($add_field['name']) ?>"  name="<? print url_title($add_field['name']); ?>"  rows="4"><? print $add_field['value'] ?></textarea>

  
<?php break;?>
<?php case 'file': ?>
 <input type="file"   class="custom-field-edit <? print strtolower($add_field['type']) ?>" id="custom_field_<? print  md5($add_field['name']) ?>"  name="<? print url_title($add_field['name']); ?>" />

   
       
<?php break;?>
<?php endswitch;?>

<? endif; ?>