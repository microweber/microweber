<? 
 only_admin_access();
 api_expose('updates');
if(url_param('add_module')){

}

	
	$install = url_param('add_module');
	
	 $update_api = new \mw\update(true);
 
	$iudates = $update_api -> check();




?>


<? d($config["module_view"]); ?>

<pre><?    print_r($iudates); ?>
</pre>
<? if(!empty($iudates)): ?>
<pre id="update_lddog_<? print $params['id']; ?>">
<? d($iudates); ?>
</pre>
<? if(isset($iudates["license_check"]) and isset($iudates["license_check"]["modules"])): ?>
<? foreach($iudates["license_check"]["modules"] as  $item): ?>
<pre id="update_log_<? print $params['id']; ?>">
<? d($item); ?>
</pre>
<? endforeach; ?>
<? endif;  ?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#mw_form1_updates<? print $params['id']; ?>').submit(function() { 

 
 mw.form.post(mw.$('#mw_form1_updates<? print $params['id']; ?>') , '<? print $config["module_view"] ?>/apply_updates', function(){
	 
var obj =  (this);




	 if(mw.is.defined(obj) && obj != null){
	 mw.$('#update_log_<? print $params['id']; ?>').empty();
	 $.each(obj, function(index, value) { 
	 
 
	 
	 
mw.$('#update_log_<? print $params['id']; ?>').append(value);
});
	  }
 
	 });







 return false;
 
 
 });
   
 


 
   
});
</script>
<form id="mw_form1_updates<? print $params['id']; ?>" name="form1" method="post">
  <? if(isset($iudates["core_update"])): ?>
  <h3>New Microweber version available</h3>
  <p>Your version <? print MW_VERSION ?></p>
  <p>New version <? print $iudates["version"] ?></p>
  <p>
    <label>
      <input type="checkbox" name="mw_version" value="<? print $iudates["version"] ?>"  />
      Install <? print $iudates["version"] ?></label>
    <br />
  </p>
  <? endif; ?>
  <? if(isset($iudates["modules"]) and !empty($iudates["modules"])): ?>
  <h4>New module updates are available</h4>
  <? foreach($iudates["modules"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="modules[]" value="<? print $item["module"] ?>"  />
      <? print $item["name"] ?> <? print $item["version"] ?></label>
    <br />
  </p>
  <? endforeach; ?>
  <? endif; ?>
  <? if(isset($iudates["elements"]) and !empty($iudates["elements"])): ?>
  <h4>New layouts updates are available</h4>
  <? foreach($iudates["elements"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="elements[]" value="<? print $item["module"] ?>"  />
      <? print $item["name"] ?> <? print $item["version"] ?></label>
    <br />
  </p>
  <? endforeach; ?>
  <? endif; ?>
  <input type="submit" value="install" />
</form>
<? endif; ?>
