<? if(isset($params['backend']) == true): ?>
<? include('backend.php'); ?>
<? else : ?>


<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a class="active" href="javascript:;">New Comments</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li><a href="javascript:;" class="">Settings</a></li>
  </ul>
  <div class="tab semi_hidden">
        1
  </div>
  <div class="tab semi_hidden">
     <module type="admin/modules/templates"  />
  </div>
  <div class="tab semi_hidden">
        3
  </div>
</div>







<? endif; ?>
