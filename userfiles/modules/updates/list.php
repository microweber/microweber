<?php

 only_admin_access();
 $update_api = new \Microweber\Update();
 $forced = false;
 if(isset($params['force'])){
	  $forced = 1;
 }
	$iudates = $update_api -> check($forced);




?>
<script>


$(document).ready(function(){

  mw.$("#select_all").commuter(function(){
     mw.check.all('#mw-update-table');
  }, function(){
     mw.check.none('#mw-update-table');
  });
  mw.$(".update-items input:checkbox").commuter(function(){
    //
  }, function(){
     mw.$("#select_all")[0].checked = false;
  });


  mw.$(".single-update-install").click(function(){
     mw.check.none('#mw-update-table');
     mw.tools.firstParentWithTag(this, 'tr').querySelector('input').checked = true;
     $(document.forms['form1']).submit();
  });


});

</script>
<?php $is_up_to_date = true; ?>

<form class="mw-select-updates-list" name="form1" method="post">
  <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-large" id="mw-update-table"  width="100%">
    <colgroup>
    <col width="40">
    <col width="140">
    <col width="777">
    </colgroup>
    <tr class="mw-table-head">
      <td><label class="mw-ui-check">
          <input type="checkbox" id="select_all" />
          <span></span></label></td>
      <td colspan="2"><span class="mw-ui-link-nav">

        <span class="mw-ui-link" onclick="mw.check.all('#mw-update-table')">
        <?php _e("Select All"); ?>
        </span>
        <span onclick="mw.check.none('#mw-update-table')">
        <?php _e("Unselect All"); ?>
        </span>

      </span>
        <input type="submit" value="<?php _e("Install Selected Updates"); ?>" id="installsubmit" class="mw-ui-btn mw-ui-btn-blue" /></td>
    </tr>
    <?php if(isset($iudates["core_update"])): ?>
    <?php $is_up_to_date = false; ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php _e("New Microweber version available"); ?></td>
    </tr>
    <tr class="update-items">
      <td><label class="mw-ui-check">
          <input type="checkbox" name="mw_version" value="<?php print $iudates["version"] ?>"  />
          <span></span></label></td>
      <td><img src="<?php print mw_includes_url(); ?>img/mw_system.png" alt="Microweber" /><br>
        <span class="update-version"><?php print MW_VERSION ?></span></td>
      <td><h2> <?php _e("New version"); ?> <?php print $iudates["version"] ?>
          <?php if(isset($item["description"])) : ?>
          <span class="update-description"><?php print $item["description"] ?></span>
          <?php endif ?>
        </h2>
        <span class="mw-ui-btn mw-ui-btn-invert show-on-hover single-update-install">
        <?php _e("Install Update"); ?>
        </span></td>
    </tr>
    <?php endif; ?>
    <?php if(isset($iudates["modules"]) and !empty($iudates["modules"])): ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php _e("New module updates are available"); ?></td>
    </tr>
    <?php foreach($iudates["modules"] as $k => $item): ?>
    <?php $is_up_to_date = false; ?>
    <tr class="update-items">
      <td><label class="mw-ui-check">
          <input type="checkbox" name="modules[]" value="<?php print $item["module"] ?>"  />
          <span></span></label></td>
      <td><?php if(isset($item["icon"])) : ?>
        <img src="<?php print $item["icon"] ?>" alt="" /> <br>
        <?php else: ?>
        <img src="<?php print mw_includes_url(); ?>img/module_no_icon.png" alt="" /><br>
        <?php endif ?>
        <span class="update-version"><?php print $item["version"] ?></span></td>
      <td><h2> <?php print $item["name"] ?> </h2>
        <?php if(isset($item["description"])) : ?>
        <span class="update-description"><?php print $item["description"] ?></span>
        <?php endif ?>
        <span class="mw-ui-btn mw-ui-btn-invert show-on-hover single-update-install">
        <?php _e("Install Update"); ?>
        </span></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($iudates["module_templates"]) and !empty($iudates["module_templates"])): ?>
    <?php $is_up_to_date = false; ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php _e("New module templates"); ?></td>
    </tr>
    <?php foreach($iudates["module_templates"] as $k => $item): ?>
    <tr class="update-items">
      <td><label class="mw-ui-check">
          <input type="checkbox" name="module_templates[<?php print $item["module"] ?>]" value="<?php print $item["module_skin_id"] ?>"  />
          <span></span></label></td>
      <td><?php if(isset($item["icon"])) : ?>
        <img src="<?php print $item["icon"] ?>" alt="" /> <br>
        <?php else: ?>
        <img src="<?php print mw_includes_url(); ?>img/module_no_icon.png" alt="" /><br>
        <?php endif ?>
        <span class="update-version"><?php print $item["version"] ?></span></td>
      <td><h2> <strong>"<?php print $item["name"]; ?>"</strong> template of "<?php print $item["module"] ?>".
          <?php if(isset($item["description"])) : ?>
          <span class="update-description"><?php print $item["description"] ?></span>
          <?php endif ?>
        </h2>
        <span class="mw-ui-btn mw-ui-btn-invert show-on-hover single-update-install">
        <?php _e("Install Update"); ?>
        </span></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($iudates["templates"]) and !empty($iudates["templates"])): ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php _e("New templates updates are available"); ?></td>
    </tr>
    <?php foreach($iudates["templates"] as $k => $item): ?>
    <?php $is_up_to_date = false; ?>
    <tr class="update-items">
      <td><label class="mw-ui-check">
          <input type="checkbox" name="templates[]" value="<?php print $item["dir_name"] ?>"  />
          <span></span></label></td>
      <td><?php if(isset($item["icon"])) : ?>
        <img src="<?php print $item["icon"] ?>" alt="" /> <br>
        <?php else: ?>
        <img src="<?php print mw_includes_url(); ?>img/module_no_icon.png" alt="" /><br>
        <?php endif ?>
        <span class="update-version"><?php print $item["version"] ?></span></td>
      <td><h2> <?php print $item["name"] ?>
          <?php if(isset($item["description"])) : ?>
          <span class="update-description"><?php print $item["description"] ?></span>
          <?php endif ?>
        </h2>
        <span class="mw-ui-btn mw-ui-btn-invert show-on-hover single-update-install">
        <?php _e("Install Update"); ?>
        </span></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($iudates["elements"]) and !empty($iudates["elements"])): ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php _e("New layouts updates are available"); ?></td>
    </tr>
    <?php foreach($iudates["elements"] as $k => $item): ?>
    <?php $is_up_to_date = false; ?>
    <tr class="update-items">
      <td><label class="mw-ui-check">
          <input type="checkbox" name="elements[]" value="<?php print $item["element_id"] ?>"  /> 
          <span></span></label></td>
      <td><?php if(isset($item["icon"])) : ?>
        <img src="<?php print $item["icon"] ?>" alt="" /> <br>
        <?php else: ?>
        <img src="<?php print mw_includes_url(); ?>img/module_no_icon.png" alt="" /><br>
        <?php endif ?>
        <span class="update-version"><?php print $item["version"] ?></span></td>
      <td><h2> <?php print $item["name"] ?>
          <?php if(isset($item["description"])) : ?>
          <span class="update-description"><?php print $item["description"] ?></span>
          <?php endif ?>
        </h2>
        <span class="mw-ui-btn mw-ui-btn-invert show-on-hover single-update-install">
        <?php _e("Install Update"); ?>
        </span></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if($is_up_to_date == true) : ?>
    <tr class="mw-table-head">
      <td colspan="3"><?php print notif('Everything is up to date') ?></td>
    </tr>
    <?php endif; ?>
  </table>
</form>
