<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Campaign settings</h1>
      </div>


  <div id="RU-help"> <a class="help" title="Help" href="javascript:void(0)"></a>


  </div>
  <div class="clr"></div>

</div>

<script type="text/javascript">

function add_content_to_group($id, $title){

$("#new_content_to_be_added_on_save").show();

$html = ''
  +'<td style="padding-left:7px;">'
   +'<h3 class="mwboxtitle">'
   + '<input type="hidden" name="content_id[]" value="'+$id+'" />'
   +  $title
   +'</h3>'
  +'</td>'
  +'<td>&nbsp;</td>'
  +'<td>&nbsp;</td>'
  +'<td>&nbsp;</td>'
  +'<td>&nbsp;</td>'
  +'<td>&nbsp;</td>'
  +'<td>&nbsp;</td>';



var new_row = document.createElement('tr');

$(new_row).hover(function(){
  $(this).addClass('hover');
}, function(){
  $(this).removeClass('hover');
});





Modal.close();

$("#RUCampaignBody").append(new_row);

$(new_row).html($html); // must be after the append


}


 $(document).ready(function(){
    	$(".add_content_to_campaign_btn").modal("iframe", 850, 500)
  });

function removeContentFromGroup($id){
	if (confirm("Are you sure you want to remove this content from the campaign?")) {
	$(".content-in-group-"+$id).fadeOut();
	$(".content-in-group-"+$id).remove();
	}
}



</script>

<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<div class="c" style="padding-bottom: 15px">&nbsp;</div>
<form action="<? print $controller_url ?>" method="post">
  <input type="hidden"  name="id" value="<? print $form_values['id']; ?>" />
  <input type="hidden"  name="taxonomy_type" value="group" />
  <div class="box-holder">
    <div class="box-top">&nbsp;</div>
    <div class="box-inside">
      <h2 class="box-title">Edit your campaign settings</h2>
      <div class="hr">&nbsp;</div>
      <label class="label-160">Title *</label>
      <span class="field">
      <input style="width: 550px;" class="required type-text" name="content_title" type="text" value="<? print $form_values['taxonomy_value']; ?>" />
      </span>
      <div class="hr">&nbsp;</div>
      <label class="label-160">Description: *</label>
      <span class="field">
      <textarea style="width: 550px;height: 50px;" class="required"  name="content_description" cols="" rows=""><? print $form_values['taxonomy_description']; ?></textarea>
      </span>
      <div id="new_content_to_be_added_on_save"  style="display:none">
        <div class="c" style="padding-bottom: 15px">&nbsp;</div>
      </div>
      <div class="c" style="padding-bottom: 15px">&nbsp;</div>
      <table cellspacing="0" cellpadding="0" class="campaign-table">
        <colgroup>
        <col width="240" />
        <col width="120" />
        <col width="100" />
        <col width="75" />
        <col width="75" />
        <col width="75" />
        <col width="75" />
        </colgroup>
        <thead>
          <tr valign="middle" class="campaign-head">
            <th colspan="7"  align="left">
            <a href="<? print site_url('users/user_action:posts/layout:small_posts') ?>"  class="nextprev left add_content_to_campaign_btn">Add page</a>
            <div style="padding-top:8px">Pages added to this campaign</div>
    </th>
          </tr>
        </thead>
        <tbody id="RUCampaignBody">
          <? $items = $this->content_model->taxonomyGetChildrenItems($form_values['id'], $taxonomy_type = 'group_item', $orderby = false); ?>
          <? if(!empty($items)): ?>
          <? foreach($items as $content_item):	 
	      $content_item_full  = array();
		  
		  if(!empty($content_item)){
	   $content_item_full = $this->content_model->contentGetByIdAndCache($content_item['to_table_id']) ;   } ?>
          <? if(!empty($content_item_full)): ?>
          <tr class="content-in-group-<? print $content_item_full['id'] ?>">
            <td style="padding-left: 7px;"><h3 class="mwboxtitle">
            <input type="hidden" name="content_id[]" value="<? print $content_item_full['id'] ?>" />
                <? // p( $content_item_full); ?>
                <a href="<? print $this->content_model->contentGetHrefForPostId($content_item_full['id']); ?>" target="_blank"><? print $content_item_full['content_title'] ?></a> </h3></td>
            <td><? ?></td>
            <td><? print $content_item_full['created_on'] ?></td>
            <td><!--<span class="statusN">&nbsp;</span>--></td>
            <td><a class="magnifier" target="_blank" href="<? print $this->content_model->contentGetHrefForPostId($content_item_full['id']); ?>">&nbsp;</a></td>
            <td><a class="campaign-edit" href="#">&nbsp;</a></td>
            <td><a class="revove-texted" href="javascript:removeContentFromGroup(<? print $content_item_full['id'] ?>);">Remove</a></td>
          </tr>
          <? endif; ?>
          <? endforeach; ?>
          <? else: ?>
          <tr>
            <td style="padding-left: 7px;"><a href="<? print site_url('users/user_action:posts/layout:small_posts') ?>"  class="add_content_to_campaign_btn">Add pages</a></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <? endif; ?>
        </tbody>
      </table>
      <br />
      <div class="c" style="padding-bottom: 15px">&nbsp;</div>
      
      <div class="hr">&nbsp;</div>
      <input name="save" type="submit" class="abshidden" value="save" />
      <a href="#" class="submit nextprev left">Save</a>

    </div>
    <div class="box-bottom">&nbsp;</div>
  </div>
</form>
