<div id="mw_index_contact_form">
  <?
  $load_list = 'default';
if(isset($_REQUEST['load_list'])){
$load_list = $_REQUEST['load_list'];	
}
  
   ?>
  <div class="mw-admin-side-nav left" style="width: 244px;margin-left: 12px;">
    <h2 class="mw-side-main-title"><span>Contact form</span></h2>
    <div class="vSpace"></div>
    <ul>
      <li><a href="?load_list=default">Default list</a></li>
      <? $data = get_form_lists('module_name=contact_form'); ?>
      <? if(isarr($data )): ?>
      <? foreach($data  as $item): ?>
      <li><a href="?load_list=<? print $item['id'] ?>"><? print $item['title'] ?></a></li>
      <? endforeach ; ?>
      <? endif; ?>
    </ul>
    <a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn-rect" style="width: 144px;margin-left: 12px;"> <span class="ico iplus"></span><span>Manage lists</span> </a></div>
  <div class="right" style="padding: 20px 0 0 20px;width: 657px;">
    <?

 


 if($load_list): ?>
    <module type="forms/list" load_list="<? print $load_list ?>"  for_module="<? print $config["the_module"] ?>" />
    <? endif; ?>
  </div>
</div>
