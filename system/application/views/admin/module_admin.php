<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="<?php echo css_browser_selector() ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/iframe.css" type="text/css" media="screen"  />
<title><? print($config['name']) ?>configuration</title>
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
<body id="iframe_body">
<div class="mw_iframe_inside_holder">
  <!--<div class="mw_iframe_header">
    <div class="mw_iframe_header_title"> <img src="<? print($config['icon']); ?>" align="left" height="25"  class="mw_iframe_header_icon" /> <? print($config['name']); ?></div>
    <a href="javascript:parent.mw_sidebar_nav('#mw_sidebar_modules_holder')" class="mw_nav_button_blue_small"> <span> Back </span> </a> </div>-->
  <div class="mw_iframe_inside_holder_padding">
    <div class="mw_iframe_sub_header">
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><? print($config['description']); ?></td>
          <? if(strval($config['website']) != '') : ?>
          <td><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/api/help.png" align="right" /></td>
          <? endif; ?>
        </tr>
      </table>
    </div>
    <? // p($params); ?>
    <?  // p($config); ?>
    {content}
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
	 
	  var module_edit_form_options1 = { 
       // target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  module_edit_showRequest,  // pre-submit callback 
        success:       module_edit_showResponse,  // post-submit callback 
		type:      'post'   ,     // 'get' or 'post', override for form's 'method' attribute 
 url:       '<? print site_url('api/content/save_option') ?>'   
 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    };
	 $('#module_edit_form1').ajaxForm(module_edit_form_options1); 
	 
	 
	 
}); 
 
// pre-submit callback 
function module_edit_showRequest(formData, jqForm, options) { 
 
    var queryString = $.param(formData); 
 
  //  alert('About to submit: \n\n' + queryString); 
 
    return true; 
} 
 
// post-submit callback 
function module_edit_showResponse(responseText, statusText, xhr, $form)  { 

 mw.reload_module('<? print $params['module'] ?>');
//  parent.update_module_element(responseText);
 
} 
  </script>
    <?
   
   /*  <? if($no_config == false) : ?><br />
    <? //p($params); ?>
    <?   if(!empty($config['params'])):  ?>
    <? foreach($config['params'] as $item): ?>
    <form id="module_edit_form1">
      <input name="save" type="submit" value="save" />
      <input name="option_group" type="text" value="<? print $params['module_id'] ?>"  />
      <table width="100%" border="0">
        <tr>
          <td><h4><? print $item['name'] ?></h4>
            <?   if(($item['help'])):  ?>
            <small><? print $item['help'] ?></small>
            <? endif; ?></td>
          <td><? // print $item['type'] ?>
            <?php switch($item['1type']): 
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
            <?php endswitch;?>
            <input name="option_key" type="text" value="<? print $item['param'] ?>"  />
            <? $new = option_get($key = $item['param'], $group = $params['module_id']); ?>
            <input name="option_value" type="text" value="<? print  $new? $new : $item['default'] ; ?>"  /></td>
          <td><? // p($item) ?></td>
        </tr>
      </table>
    </form>
    <? endforeach; ?>
    <? else: ?>
    This module doesn't have configuration.
    <? endif; ?>
    <? endif; ?>*/
   
   ?>
  </div>
</div>
<?  include('footer.php'); ?>
</body>
</html>