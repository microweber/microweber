<? 

 only_admin_access();
 $update_api = new \mw\Update();
 $forced = false;
 if(isset($params['force'])){
	  $forced = 1;
 }
	$iudates = $update_api -> check($forced);
	
 
 
?>

<form class="mw-select-updates-list" name="form1" method="post">
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
  
    <? if(isset($iudates["module_templates"]) and !empty($iudates["module_templates"])): ?>
   
  <h4>New module templates</h4>
  <? foreach($iudates["module_templates"] as $k => $item): ?>
  <p>
    <label>
      <input type="checkbox" name="module_templates[<? print $item["module"] ?>][]" value="<? print $item["layout_file"] ?>"  />
      <? print $item["name"] ?> <? print $item["version"] ?> <em>(<? print $item["module"] ?>)</em> </label>
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