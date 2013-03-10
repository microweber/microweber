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




if(isset($params['content-id'])){
	$for_id = $for_module_id = $params['content-id'];
  $for = 'table_content';
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
         mw.$("#custom-field-editor").removeClass('mw-custom-field-created').hide();
         mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
       });
      }
    });
  }
  return sortable_holder;
}

createFieldPill = function(el){
  mw.custom_fields.create({
    selector:'.mw-admin-custom-field-edit-<? print $params['id']; ?>',
    type:$(el).dataset('type'),
    copy:false,
    table:'<? print $for  ?>',
    id:'<? print $module_id  ?>',
    onCreate:function(){
      mw.$(".custom-field-edit-title").html('<span class="'+el.querySelector('span').className+'"></span><strong>' + el.textContent + '</strong>');
      mw.$("#custom-field-editor").addClass('mw-custom-field-created').show();
      mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
      $(mw.tools.firstParentWithClass(el, 'custom_fields_selector')).slideUp('fast');
      __save();
    }
  });
}

$(document).ready(function(){
 __sort_fields();

 mw.$(".custom_fields_selector strong").click(function(){
  var el = this.getElementsByTagName('a')[0];
  createFieldPill(el);
});




});

</script>

<div class="<? print $config['module_class'] ?>-holder">





<module data-type="custom_fields/list" <? print $hide_preview  ?>
  for="<? print $for  ?>"
  for_module_id="<? print $module_id ?>"
  <? if(isset($params['to_table_id'])): ?> to_table_id="<? print $params['to_table_id'] ?>"  <? endif; ?>
  id="mw_custom_fields_list_<? print $params['id']; ?>"  <? if(isset($params['default-fields'])): ?> default-fields="<?  print $params['default-fields'] ?>" <? endif; ?>/>







  <div id="custom-field-editor" style="display: none">
    <label class="mw-ui-label"><small>Edit <b id="which_field"></b> Field</small></label>
    <div class="custom-field-edit">
      <div  class="custom-field-edit-header">
        <div class="custom-field-edit-title"></div>
      </div>
      <div class="mw-admin-custom-field-edit-item-wrapper">
        <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<? print $params['id']; ?> "></div>
      </div>
    </div>
  </div>



</div>


<div class="custom_fields_selector" style="display: none;">
  <ul class="mw-quick-links mw-quick-links-cols">


    <li><strong><a href="javascript:;" data-type="text"><span class="ico iSingleText"></span><span>Text Field</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="number"><span class="ico iNumber"></span><span>Number</span></a></strong></li>
    <li><strong id="field-type-price"><a href="javascript:;" data-type="price"><span class="ico iPrice"></span><span>Price</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="phone"><span class="ico iPhone"></span><span>Phone</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="site"><span class="ico iWebsite"></span><span>Web Site</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="email"><span class="ico iEmail"></span><span>E-mail</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="address"><span class="ico iAddr"></span><span>Adress</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="date"><span class="ico iDate"></span><span>Date</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="upload"><span class="ico iUpload"></span><span>File Upload</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="radio"><span class="ico iRadio"></span><span>Single Choice</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="dropdown"><span class="ico iDropdown"></span><span>Dropdown</span></a></strong></li>
    <li><strong><a href="javascript:;" data-type="checkbox"><span class="ico iChk"></span><span>Multiple choices</span></a></strong></li>
  </ul>
</div>


  <span class="mw-ui-btn mw-ui-btn-blue" id="smart_field_opener" onclick="mw.tools.toggle('.custom_fields_selector', this);" style="height: 15px;">
    <span class="ico iAdd"></span><span><?php _e("Add Custom Field"); ?></span>
  </span>



