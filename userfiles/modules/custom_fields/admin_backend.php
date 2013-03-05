<? //  d($params) ?>
<script type="text/javascript">



    mw.require("custom_fields.js");

    mw.require("options.js");



</script>
<?
 $for = 'module';
 
  $copy_from = false;
 if(isset($params['for'])){
	$for = $params['for'];
 }
 
 if(isset($params['copy_from'])){
	$copy_from = $params['copy_from'];
 }
  $hide_preview = '';
  if(isset($params['live_edit'])){
	 $hide_preview = " live_edit=true " ;
 }



 
  if(isset($params['for_id'])){
	$for_id = $params['for_id'];
 } elseif (isset($params['data-id'])){
	 $for_id = $params['data-id'];
 }
  else  if(isset($params['id'])){
	$for_id = $params['id'];
 }

  //$for_id =$params['id'];
 if(isset($params['to_table_id'])){
$for_id =$module_id = $params['to_table_id'];

 }
 
$module_id = $for_id;
//$rand = rand();
 
?>
<script type="text/javascript">
    __sort_fields = function(){
        var sortable_holder = mw.$(".mw-custom-fields-tags").eq(0);
        if(!sortable_holder.hasClass('ui-sortable') && sortable_holder.find('a.mw-ui-btn-small').length>1){
          sortable_holder.sortable({
            items:'a.mw-ui-btn-small',
            update:function(event,ui){
                var obj = {ids:[]};
                $(this).find("a.mw-ui-btn-small").each(function(){
                    var id = $(this).dataset("id");
                    obj.ids.push(id);
                });
                $.post(mw.settings.api_url+"reorder_custom_fields", obj, function(){
            if(window.parent != undefined && window.parent.mw != undefined){
				 window.parent.mw.reload_module('custom_fields');
			 }
                });
            },
            containment: "parent"
          });
        }
        return sortable_holder;
    }

    $(document).ready(function(){
       __sort_fields();

       mw.$(".custom_fields_selector a").click(function(){
            var el = this;
            mw.custom_fields.create({
              selector:'.mw-admin-custom-field-edit-<? print $params['id']; ?>',
              type:$(el).dataset('type'),
              copy:false,
              table:'<? print $for  ?>',
              id:'<? print $module_id  ?>',
              onCreate:function(){
                mw.$(".custom-field-edit-title").html(el.textContent);
                mw.$("#custom-field-editor").addClass('mw-custom-field-created').show();
                mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
              }
            });
       });

    });

</script>

<div class="<? print $config['module_class'] ?>-holder">
    <span class="mw-ui-btn mw-ui-btn-blue" onclick="mw.tools.toggle('.custom_fields_selector', this);" style="height: 15px;">
        <span class="ico iAdd"></span><span><?php _e("Add  New Custom Field"); ?></span>
    </span>
  <div class="vSpace"></div>
  <div class="custom_fields_selector" style="display: none;">
    <ul class="mw-quick-links mw-quick-links-cols">
      <li><a href="javascript:;" data-type="text"><span class="ico iSingleText"></span><span>Text Field</span></a></li>
      <li><a href="javascript:;" data-type="number"><span class="ico iNumber"></span><span>Number</span></a></li>
      <li><a href="javascript:;" data-type="price"><span class="ico iPrice"></span><span>Price</span></a></li>
      <li><a href="javascript:;" data-type="phone"><span class="ico iPhone"></span><span>Phone</span></a></li>
      <li><a href="javascript:;" data-type="site"><span class="ico iWebsite"></span><span>Web Site</span></a></li>
      <li><a href="javascript:;" data-type="email"><span class="ico iEmail"></span><span>E-mail</span></a></li>
      <li><a href="javascript:;" data-type="address"><span class="ico iAddr"></span><span>Adress</span></a></li>
      <li><a href="javascript:;" data-type="date"><span class="ico iDate"></span><span>Date</span></a></li>
      <li><a href="javascript:;" data-type="upload"><span class="ico iUpload"></span><span>File Upload</span></a></li>
      <li><a href="javascript:;" data-type="radio"><span class="ico iRadio"></span><span>Single Choice</span></a></li>
      <li><a href="javascript:;" data-type="dropdown"><span class="ico iDropdown"></span><span>Dropdown</span></a></li>
      <li><a href="javascript:;" data-type="checkbox"><span class="ico iChk"></span><span>Multiple choices</span></a></li>
    </ul>
  </div>

  <module
        data-type="custom_fields/list" <? print $hide_preview  ?>
        for="<? print $for  ?>"
        for_module_id="<? print $module_id ?>"
        <? if(isset($params['to_table_id'])): ?> to_table_id="<? print $params['to_table_id'] ?>"  <? endif; ?>
        id="mw_custom_fields_list_<? print $params['id']; ?>"
  />
  <div class="custom-field-edit" id="custom-field-editor" style="display:none;">
    <div  class="custom-field-edit-header">
      <div class="custom-field-edit-title"></div>
    </div>
    <div class="mw-admin-custom-field-edit-item-wrapper">
      <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<? print $params['id']; ?>"></div>
    </div>
  </div>
</div>
