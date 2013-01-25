<div id="mw_index_contact_form">
  <div class="mw_edit_page_left"  style="width: 195px;">
    <h2 class="mw-side-main-title"><span>Contact form</span></h2>
    <div class="vSpace"></div>
    <div class="mw-admin-side-nav">
      <ul >
        <li><a href="#">Default list</a></li>
        <? $data = get_form_lists('module_name=contact_form'); ?>
        <? if(isarr($data )): ?>
        <? foreach($data  as $item): ?>
        <li><a href="#<? print $item['id'] ?>"><? print $item['title'] ?></a></li>
        <? endforeach ; ?>
        <? endif; ?>
      </ul>
    </div>
    <div class="vSpace"></div>
    <a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn-rect" style="width: 144px;margin-left: 12px;"> <span class="ico iplus"></span><span>Manage lists</span> </a> </div>
  <div class="mw_edit_page_right" style="padding: 20px 0 0 20px;width: 757px;">
    <div class="modules-index-bar"> <span class="mw-ui-label-help font-11 left">Sort modules:</span>
      <input name="module_keyword" class="mw-ui-searchfield right" type="text" default="Search for modules"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
    </div>
    <div class="vSpace"></div>
    <div  style="width: 757px">
      <div class='mw-simple-rotator-container' id="mw-contact_form-manage-edit-rotattor"> </div>
    </div>
  </div>
</div>
