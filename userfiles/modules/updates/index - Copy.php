<?php
 
if(mw('url')->param('add_module')){

}

	
	$install = mw('url')->param('add_module');
	
	 $update_api = new \Microweber\Update();
 
	$iudates = $update_api -> check();




?><pre><?php    print_r($iudates); ?></pre>

<?php if(!empty($iudates)): ?>
 <pre id="update_lddog_{rand}">
<?php d($iudates); ?>
</pre>

<?php if(isset($iudates["license_check"]) and isset($iudates["license_check"]["modules"])): ?>
 <?php foreach($iudates["license_check"]["modules"] as  $item): ?>
 <pre id="update_log_{rand}">
<?php d($item); ?>
</pre>
 <?php endforeach; ?>
<?php endif;  ?>
 <script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#mw_form1_updates{rand}').submit(function() { 

 
 mw.form.post(mw.$('#mw_form1_updates{rand}') , '<?php print mw_site_url('api') ?>/mw_apply_updates', function(){
	 
var obj =  (this);




	 if(typeof obj !== 'undefined' && obj != null){
	 mw.$('#update_log_{rand}').empty();
	 $.each(obj, function(index, value) { 
	 
 
	 
	 
mw.$('#update_log_{rand}').append(value);
});
	  }
	// mw.log(this);
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });







 return false;
 
 
 });
   
 


 
   
});
</script>





<form id="mw_form1_updates{rand}" name="form1" method="post">
  <?php if(isset($iudates["mw_new_version_download"])): ?>
  <h3>New Microweber version available</h3> 
  
   <p>Your version <?php print MW_VERSION ?></p>
  <p>New version <?php print $iudates["mw_version"] ?></p>
  <p>
    <label>
      <input type="checkbox" name="mw_new_version_download" value="<?php print $iudates["mw_new_version_download"] ?>"  />
      Install <?php print $iudates["mw_version"] ?></label>
    <br />
  </p>
  <?php endif; ?>
  <?php if(isset($iudates["modules"]) and !empty($iudates["modules"])): ?>
  <h4>New module updates are available</h4>
  <?php foreach($iudates["modules"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="modules['<?php print $item["module"] ?>']" value="<?php print $item["mw_new_version_download"] ?>"  />
       
      <?php print $item["name"] ?> <?php print $item["version"] ?></label>
    <br />
  </p>
  <?php endforeach; ?>
  <?php endif; ?>
  <?php if(isset($iudates["elements"]) and !empty($iudates["elements"])): ?>
  <h4>New layouts updates are available</h4>
  <?php foreach($iudates["elements"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="elements['<?php print $item["module"] ?>']" value="<?php print $item["mw_new_version_download"] ?>"  />
     
      
      <?php print $item["name"] ?> <?php print $item["version"] ?></label>
    <br />
  </p>
  <?php endforeach; ?>
  <?php endif; ?>
  <input type="submit" value="install" />
</form>
<?php endif; ?>
