<div id="mw_index_contact_form">
  <?
  $load_list = 'default';
if((url_param('load_list') != false)){
$load_list = url_param('load_list');
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
      <li><a href="<? print $config['url'] ?>/load_list:<? print $item['id'] ?>"><? print $item['title'] ?></a></li>
      <? endforeach ; ?>
      <? endif; ?>
    </ul>
    <a href="javascript:mw.url.windowHashParam('edit-user', 0)" class="mw-ui-btn" style="width: 144px;margin-left: 12px;"> <span class="ico iplus"></span><span>Manage lists</span> </a></div>
  <div class="right" style="padding: 20px 0 0 20px;width: 657px;">
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

  $('#forms_data_module').removeAttr('export_to_excel' );


 mw.reload_module('#forms_data_module', function(){
  mw.$("#forms_data_keyword").removeClass('loading')

 });


});
 </script>

        <?php $def =  _e("Search for data", true);  ?>

        <input
        name="forms_data_keyword"
        id="forms_data_keyword"
        autocomplete="off"
        class="mw-ui-searchfield right"
        type="text"
        value="<?php print $def; ?>"
        data-default='<?php print $def; ?>'
        onfocus='mw.form.dstatic(event);'
        onblur='mw.form.dstatic(event);'
        onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
          />

<button onClick="mw_forms_data_to_excel()">excel</button>



    <module type="forms/list" load_list="<? print $load_list ?>"  for_module="<? print $config["the_module"] ?>" id="forms_data_module" />
    <? endif; ?>
  </div>
</div>
