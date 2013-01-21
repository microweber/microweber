<?  $rand = $params['id']; ?>
<div id="mw_index_menus">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;
      <?php _e("Menus"); ?>
    </h2>
    
    <div class="mw-admin-side-nav" id="menus_categories_tree_<? print $rand  ?>" >
      <div id="menus_admin_categories_<? print $rand  ?>">
      <? $menus = get_menu(); ?>

        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a onclick="mw.url.windowHashParam('data-option_group', '<? print $item ?>');return false;" href="#data-option_group=<? print $item ?>"><? print $item ?></a></li>
          <? endforeach; ?>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn-rect" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>
        <?php _e("Add new menus"); ?>
        </span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <div class="menus-index-bar"> asdasdasd </div>
    <div class="vSpace"></div>
    <div id="menus_admin_<? print $rand  ?>" > </div>
  </div>
</div>
