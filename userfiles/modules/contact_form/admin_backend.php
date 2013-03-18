<div id="mw_index_contact_form">


<div id="mw_edit_page_left">

  <?
$load_list = 'default';
if((url_param('load_list') != false)){
    $load_list = url_param('load_list');
}


   ?>
  <div class="mw-admin-side-nav left" style="width: 244px;margin-left: 12px;">

   <?php $info = module_info($config['module']);  ?>

     <h2 class="module-icon-title">
        <a href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($info['module']); ?>">
            <img style="top:6px;" src="<?php print $info['icon']; ?>" alt="" /><?php print $info['name']; ?>
        </a>
      </h2>
    <div class="vSpace"></div>
    <ul>
      <li><a   <?php if($load_list == 'default'){ ?> class="active" <?php } ?> href="<? print $config['url']; ?>/load_list:default" >Default list</a></li>
      <? $data = get_form_lists('module_name=contact_form'); ?>
      <? if(isarr($data )): ?>
      <? foreach($data  as $item): ?>

      <li><a <?php if($load_list == $item['id']){ ?> class="active" <?php } ?> href="<? print $config['url']; ?>/load_list:<? print $item['id']; ?>"><? print $item['title']; ?></a></li>


      <? endforeach ; ?>
      <? endif; ?>
    </ul>
    <div class="vSpace"></div>
    <div class="vSpace"></div>
    <a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn" style="width: 144px;margin-left: 12px;"> <span class="ico iplus"></span><span>Manage lists</span> </a></div>

</div>

 <div class="mw_edit_page_right" style="padding: 20px 0 0 20px;width: auto;">
    <?




 if($load_list): ?>
 <script type="text/javascript">


function mw_forms_data_to_excel(){
$('#forms_data_module').attr('export_to_excel',1 );
mw.reload_module('#forms_data_module');
}

mw.on.hashParam('search', function(){


  var field = mwd.getElementById('forms_data_keyword');

  if(!field.focused){
    field.value = this;
  }

  if(this  != ''){
  $('#forms_data_module').attr('keyword',this );

  } else {
  $('#forms_data_module').removeAttr('keyword' );
  }

  $('#forms_data_module').removeAttr('export_to_excel');


 mw.reload_module('#forms_data_module', function(){
    mw.$("#forms_data_keyword").removeClass('loading');
 });


});
 </script>

        <?php $def =  _e("Search for data", true);  ?>

         <?php $data = get_form_lists('single=1&id='.$load_list); ?>

        <h2 class="left"><?php print ($data['title']); ?></h2>

        <input
            name="forms_data_keyword"
            id="forms_data_keyword"
            autocomplete="off"
            class="right"
            type="search"
            value="<?php print $def; ?>"
            placeholder='<?php print $def; ?>'
            onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
          />

       <div class="export-label">
          <span>Export data:</span>
          <a href="javascript:;" onclick="mw_forms_data_to_excel()"><span class="ico iexcell"></span>Excel</a>
       </div>


       <div class="mw_clear"></div>
       <div class="vSpace"></div>




    <module type="forms/list" load_list="<? print $load_list ?>"  for_module="<? print $config["the_module"] ?>" id="forms_data_module" />




    <? endif; ?>
  </div>
</div>
