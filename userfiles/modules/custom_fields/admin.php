  <script type="text/javascript">



    mw.require("custom_fields.js");




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
  
 

  if(isset($params['for_id'])){
	$for_id = $params['for_id'];
 } else  if(isset($params['id'])){
	$for_id = $params['id'];
 }
 
  //$for_id =$params['id'];
 if(isset($params['to_table_id'])){
$for_id =$params['to_table_id'];
 }
 
$module_id = $for_id;
$rand = rand();
 
?>


<div id="the_custom_fields">



<span class="mw-ui-btn-rect" onclick="mw.tools.toggle('.custom_fields_selector', this);"><span class="ico iAdd"></span><?php _e("Add New Custom Field"); ?></span>

<div class="vSpace"></div>

<div class="custom_fields_selector" style="display: none;">






    <ul class="mw-quick-links left">
        <li><a href="javascript:;" onclick="mw.custom_fields.create('text');"><span class="ico iSingleText"></span><span>Single Line Text</span></a></li>
        <li><a href="#"><span class="ico iPText"></span><span>Paragraph Text</span></a></li>
        <li><a href="#"><span class="ico iRadio"></span><span>Multiple Choice</span></a></li>
        <li><a href="#"><span class="ico iName"></span><span>Name</span></a></li>
    </ul>

    <ul class="mw-quick-links left">
      <li><a href="#"><span class="ico iPhone"></span><span>Phone</span></a></li>
      <li><a href="#"><span class="ico iWebsite"></span><span>Web Site</span></a></li>
      <li><a href="#"><span class="ico iEmail"></span><span>E-mail</span></a></li>
      <li><a href="#"><span class="ico iUpload"></span><span>File Upload</span></a></li>
    </ul>


    <ul class="mw-quick-links left">
      <li><a href="#"><span class="ico iNumber"></span><span>Number</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('checkbox');"><span class="ico iChk"></span><span>Checkbox</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('dropdown');"><span class="ico iDropdown"></span><span>Dropdown</span></a></li>
      <li><a href="#"><span class="ico iDate"></span><span>Date</span></a></li>
    </ul>

    <ul class="mw-quick-links left">
      <li><a href="#"><span class="ico iTime"></span><span>Time</span></a></li>
      <li><a href="#"><span class="ico iAddr"></span><span>Adress</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('price');"><span class="ico iPrice"></span><span>Price</span></a></li>
      <li><a href="#"><span class="ico iSpace"></span><span>Section Break</span></a></li>
    </ul>


  </div>



<table class="custom-field-table semi_hidden" id="create-custom-field-table" cellpadding="0" cellspacing="0">

<tr class="active">
    <td>&nbsp;</td>
    <td class="second-col">
        <div class="custom-fields-form-wrap custom-fields-form-wrap-<? print $rand ?>" id="custom-fields-form-wrap-<? print $rand ?>">
        </div>
    </td>

</tr>
</table>
<script type="text/javascript">


mw.custom_fields.create = function($type, $copy, callback){
      var copy_str = '';
      if($copy !== undefined){
        var copy_str = '/copy_from:'+ $copy;
      }
      mw.$('#custom-fields-form-wrap-<? print $rand ?>').load('<? print site_url('api_html/make_custom_field/settings:y/basic:y/for_module_id:') ?><? print $module_id; ?>/for:<? print $for  ?>/custom_field_type:'+$type + copy_str, function(){
        mw.is.func(callback) ? callback.call($type) : '';
        mw.$("#create-custom-field-table").removeClass("semi_hidden");
      });

  }




$(document).ready(function(){
<? if($copy_from != false): ?>
    mw.custom_fields.create('', '<? print $copy_from ?>');
<? endif; ?>
        //make_new_field()

    });
</script>
<module type="custom_fields" view="list" for="<? print $for  ?>" for_module_id="<? print $module_id ?>" id="mw_custom_fields_list_<? print $params['id']; ?>" />


</div>
