<script type="text/javascript">
	   //sortable table
  $(document).ready(function(){
      //

	$('.the_posts_sortable').sortable({
					opacity: '0.5',
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('api/content/options_sort')  ?>",
							type: "POST",
							data: serial,
							// complete: function(){},
							success: function(feedback){
							//alert(feedback);
								//$('#data').html(feedback);
							}
							// error: function(){}
						});
					}
				});

	

  });
  </script>
<?


 
 

$options = array();
if($params['for_module'] != ''){
	
	$options['module']= $params['for_module'];
	
}

if($params['option_group'] != ''){
	
	$options['option_group']= $params['option_group'];
	
}

if($params['keyword'] != ''){
	
	$options['keyword']= $params['keyword'];
	
}

//$options['keyword']= 'keywords';
//$options['debug']= 'keywords';
 $options2 =  $options;

$all_options = CI::model('core')->optionsGet ( $options );
if($all_options == false){
$all_options = array();	
}
//p($all_options);
$all_options[] = array();
?>
<script type="text/javascript">
function show_opt_edit_form($form_id){
$(''+$form_id).toggle();
}
</script>

<div class="the_posts_sortable ui-sortable">
  <? foreach($all_options as $item): ?>
  <script type="text/javascript">
function save_option<? print $item['id']; ?>($form_id){
	data123 = $('#'+$form_id).serialize();
	$.post("<? print site_url('api/content/save_option') ?>",  data123 ,	function(data1){
																					 
										$('#'+$form_id).fadeOut().fadeIn();											 
																					 });
}
</script>
  <? //p($item); ?>
  <div id="optitem_<? print $item['id']; ?>" class="post item">
    <h4><? print ucwords(  str_replace('_', ' ', $item['option_key'])); ?></h4>
    <div style="float:left; width:500px;"> <small class="gray"> option_group: <? print $item['option_group']?> |
      
      option_key: <? print $item['option_key']?> </small> <br />
      <? if($item['help'] != ''):?>
      <br />
      <p><em><? print $item['help']?></em></p>
      <br />
      <? endif; ?>
      option_value: <? print $item['option_value']?>
      <form class="order_options_form admin_options_item_edit_form" id="opt<? print $item['id']; ?>" style="display:none">
        <br />
        <table width="100%" border="0" class="order_options_table">
          <? if(intval($item['id']) > 0): ?>
          <tr>
            <td>id</td>
            <td><input name="id" value="<? print $item['id']?>" type="text" /></td>
          </tr>
          <? endif; ?>
          <tr>
            <td>Option Key</td>
            <td><input name="option_key" value="<? print $item['option_key']?>" type="text" /></td>
          </tr>
          <tr>
            <td>Option Value</td>
            <td><?  //p($item)?>
              <textarea name="option_value"><? print ($item['option_value'])?></textarea></td>
          </tr>
          <tr>
            <td>Help text</td>
            <td><?  //p($item)?>
              <textarea name="help"><? print ($item['help'])?></textarea></td>
          </tr>
          <tr>
            <td>Option Group</td>
            <td><input name="option_group" value="<? print $item['option_group']?>" type="text" /></td>
          </tr>
          <tr>
            <td>Option Key 2</td>
            <td><input name="option_key2" value="<? print $item['option_key2']?>" type="text" /></td>
          </tr>
          <tr>
            <td>Option Value 2</td>
            <td><input name="option_value2" value="<? print $item['option_value2']?>" type="text" /></td>
          </tr>
        </table>
        <input name="save" class="btn" type="button" onClick="save_option<? print $item['id']; ?>('opt<? print $item['id']; ?>')" value="save">
      </form>
    </div>
    <div class="post_info">
      <div class="post_comments post_info_inner"></div>
      <div class="post_author post_info_inner"> <a href="javascript:show_opt_edit_form('#opt<? print $item['id']; ?>');" class="xbtn">Edit</a> <br />
        <br />
        <br />
        <small class="gray"> <a class="xbtn" href="#" onclick="mw.options.del('<? print $item['id'] ?>','#optitem_<? print $item['id']; ?>');">Delete</a> </small> </div>
      <!-- 
   
     <div class="post_views post_info_inner"></div>
   <div class="post_title">Cardiology Revenue Cycle Report</div>
    <div class="post_id">3477</div>-->
    </div>
  </div>
  <? endforeach; ?>
</div>
<div class="paging">
  <? //$paging =  $this->content_model->pagingPrepareUrls($base_url = false, $all_options_c, $paging_param = 'curent_page_options', $keyword_param = 'keyword') ;
//paging($display = 'divs', $paging);
//p($paging);
?>
</div>
