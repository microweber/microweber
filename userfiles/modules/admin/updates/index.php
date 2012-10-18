<? $rand = uniqid(); ?>
<?  $iudates =  mw_check_for_update();

//d($iudates);
?>

<? if(!empty($iudates)): ?>
 <pre id="update_lddog_<? print $rand ?>">
<? d($iudates); ?>
</pre>

<? if(isset($iudates["license_check"]) and isset($iudates["license_check"]["modules"])): ?>
 <? foreach($iudates["license_check"]["modules"] as  $item): ?>
 <pre id="update_log_<? print $rand ?>">
<? d($item); ?>
</pre>
 <? endforeach; ?>
<? endif;  ?>
 <script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#mw_form1_updates<? print $rand ?>').submit(function() { 

 
 mw.form.post(mw.$('#mw_form1_updates<? print $rand ?>') , '<? print site_url('api') ?>/mw_apply_updates', function(){
	 
var obj =  (this);




	 if(mw.is.defined(obj) && obj != null){
	 mw.$('#update_log_<? print $rand ?>').empty();
	 $.each(obj, function(index, value) { 
	 
 
	 
	 
mw.$('#update_log_<? print $rand ?>').append(value);
});
	  }
	// mw.log(this);
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages_menu"]');
	 });







 return false;
 
 
 });
   
 


 
   
});
</script>





<form id="mw_form1_updates<? print $rand ?>" name="form1" method="post">
  <? if(isset($iudates["mw_new_version_download"])): ?>
  <h3>New Microweber version available</h3> 
  
   <p>Your version <? print MW_VERSION ?></p>
  <p>New version <? print $iudates["mw_version"] ?></p>
  <p>
    <label>
      <input type="checkbox" name="mw_new_version_download" value="<? print $iudates["mw_new_version_download"] ?>"  />
      Install <? print $iudates["mw_version"] ?></label>
    <br />
  </p>
  <? endif; ?>
  <? if(isset($iudates["modules"]) and !empty($iudates["modules"])): ?>
  <h4>New module updates are available</h4>
  <? foreach($iudates["modules"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="modules['<? print $item["module"] ?>']" value="<? print $item["mw_new_version_download"] ?>"  />
       
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
      <input type="checkbox" name="elements['<? print $item["module"] ?>']" value="<? print $item["mw_new_version_download"] ?>"  />
     
      
      <? print $item["name"] ?> <? print $item["version"] ?></label>
    <br />
  </p>
  <? endforeach; ?>
  <? endif; ?>
  <input type="submit" value="install" />
</form>
<? endif; ?>
