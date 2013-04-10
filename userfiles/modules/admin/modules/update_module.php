<? if(!empty($data)):  ?>
<? //$rand = uniqid().$data['id']; ?>
<script  type="text/javascript">



$(document).ready(function(){

	 mw.$('#module_admin_settings_form_{rand}').submit(function() {

     mw.form.post(mw.$('#module_admin_settings_form_{rand}') , '<? print site_url('api') ?>/save_settings_md', function(){

	 });

     return false;
 
 
 });

 


	 
	 mw.$('#module_uninstall_{rand}').click(function() {
       var for_module = {}
       for_module.id =  $(this).attr('data-module-id');
       $.post('<? print site_url('api') ?>/uninstall_module/', for_module, function(data) {

       });
       return false;
	 });
	 mw.$('#module_install_{rand}').click(function() {
         var for_module = {}
         for_module.for_module =  $(this).attr('data-module-name');
         $.post('<? print site_url('api') ?>/install_module/', for_module,  function(data) {

         });

		 return false;
	 });

});
</script>

<form class="admin-modules-list-form" id="module_admin_settings_form_{rand}">
  <div class="admin-modules-list-image"> <span class="ico iMove mw_admin_modules_sortable_handle"></span> 
    <? if(isset($data['icon'])):  ?>
    <img src="<? print $data['icon'] ?>" alt="<? if(isset($data['name'])){ print addslashes($data['name']); }; ?> icon." />
    <? endif; ?>
    <s class="mw_module_image_shadow"></s>  <!--<strong class="mw_module_new">new</strong>--> </div>
  <div class="admin-modules-list-description">
    <h2>
      <? if(isset($data['name'])):  ?>
      <? print $data['name'] ?>
      <? endif; ?>
    </h2>
    <small><? print $data['module'] ?></small>
   
      <? if(isset($data['description'])):  ?>
       <p>
      <? print $data['description'] ?>
      </p>
      <? endif; ?>
    
    <a href="<? print admin_url() ?>view:modules/add_module:<? print module_name_encode($data['module']) ?>" class="mw-ui-btn">
    <?php _e("Install"); ?>
    </a></div>
</form>
<? endif; ?>
