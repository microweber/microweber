<? if(!is_admin()){error("must be admin");}; ?>
<script  type="text/javascript">
    mw.require("<? print $config['url_to_module']; ?>backup.js");
</script>

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
      <div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:mw.admin_backup.create('.mw_edit_page_right')" class="mw-ui-btn"><span class="ico iplus"></span><span>New Database Backup</span></a>
        
        
        <a href="javascript:mw.admin_backup.create_full('.mw_edit_page_right')" class="mw-ui-btn"><span class="ico iplus"></span><span>New FULL Backup</span></a> 
         </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <? if(isset($_GET['backup_action'])): ?>
    <module type="admin/backup/<? print $_GET['backup_action'] ?>" />
    <? else :?>
    <module type="admin/backup/manage" />
    <? endif ;?>
  </div>
</div>
