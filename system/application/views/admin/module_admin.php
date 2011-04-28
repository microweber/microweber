<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="<?php echo css_browser_selector() ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<?  include('header_scripts.php'); ?>
<?  


$no_config = $params['no_config'];
if($no_config == false){
$no_config=	$config['no_config'];
}

if($no_config == false){
$no_config=	url_param('no_config');
}

?>
</head>
<body>
 
   
   {content}  
 
    <? if($no_config == false) : ?>
    <h3><? print($config['name']) ?> configuration</h3>
    <script>
 
  

  
  
  // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var module_edit_form_options = { 
       // target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  module_edit_showRequest,  // pre-submit callback 
        success:       module_edit_showResponse,  // post-submit callback 
		type:      'post'   ,     // 'get' or 'post', override for form's 'method' attribute 
 url:       '<? print site_url('api/template/encode_post') ?>'   
 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
     $('#module_edit_form').ajaxForm(module_edit_form_options); 
}); 
 
// pre-submit callback 
function module_edit_showRequest(formData, jqForm, options) { 
 
    var queryString = $.param(formData); 
 
  //  alert('About to submit: \n\n' + queryString); 
 
    return true; 
} 
 
// post-submit callback 
function module_edit_showResponse(responseText, statusText, xhr, $form)  { 

  parent.update_module_element(responseText);
} 
  </script>
    <br />
    <?   if(!empty($config['params'])):  ?>
    <form id="module_edit_form">
      <input name="save" type="submit" value="save" />
      <? foreach($params as $k => $v): ?>
      <?   if(empty($config['params'][$k])):  ?>
      <input name="<? print $k ?>" type="hidden" value="<? print $v ?>"  />
      <? endif; ?>
      <? endforeach; ?>
      <table width="100%" border="0">
        <? foreach($config['params'] as $item): ?>
        <tr>
          <td><h4><? print $item['name'] ?></h4>
            <?   if(($item['help'])):  ?>
            <small><? print $item['help'] ?></small>
            <? endif; ?></td>
          <td><? // print $item['type'] ?>
            <?php switch($item['type']): 
case 'category_selector': ?>
            <mw module="admin/content/category_selector"  active_category="<? print  $params[$item['param']]? $params[$item['param']] : $item['default'] ; ?>" update_field="#category_<? print $item['param'] ?>" />
            <input name="<? print $item['param'] ?>" id="category_<? print $item['param'] ?>" type="hidden" value="<? print  $params[$item['param']]? $params[$item['param']] : $item['default'] ; ?>"  />
            <?php break;?>
            <?php case 'number': ?>
            <?php case 'text': ?>
            <div class="field2">
              <input name="<? print $item['param'] ?>" type="text" value="<? print  $params[$item['param']]? $params[$item['param']] : $item['default'] ; ?>"  />
            </div>
            <?php break;?>
            <?php endswitch;?></td>
          <td><? // p($item) ?></td>
        </tr>
        <? endforeach; ?>
      </table>
    </form>
    <? else: ?>
    This module doesn't have configuration.
    <? endif; ?>
    <? endif; ?>
 
 
<?  include('footer.php'); ?>
</body>
</html>