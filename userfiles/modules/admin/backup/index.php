<? if(!is_admin()){error("must be admin");}; ?>

<div id="mw_backups_settings">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;Backups</h2>
    <div class="mw-admin-side-nav"  >
      <div id="">
        <ul>
          <li><a href="<? print $config['url']; ?>?backup_action=manage">Manage</a></li>
          <li><a href="<? print $config['url']; ?>?backup_action=settings">Settings</a></li>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace">&nbsp;</div>
        <a href="<? print $config['url']; ?>?backup_action=new" class="mw-ui-btn-rect" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>Make new backup</span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <? if(isset($_GET['backup_action'])): ?>
    <? include($_GET['backup_action'].'.php'); ?>
    <? else :?>
    <? include('manage.php'); ?>
    <? endif ;?>
  </div>
</div>
