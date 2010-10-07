<?  $reports_for  = $this->core_model->getParamFromURL ( 't' ); ?>

<div id="subheader">
  <ul id="TagTabcontrol">
    <li><a <? if(!$reports_for): ?> class="active" <?  endif;?>  href="<?php print site_url('admin/reports/index')  ?>">All reports</a></li>
    <li><a <? if($reports_for =='table_content'): ?> class="active" <?  endif;?>   href="<?php print site_url('admin/reports/index/t:table_content')  ?>">For content</a></li>
    <li><a <? if($reports_for =='table_comments'): ?> class="active" <?  endif;?> href="<?php print site_url('admin/reports/index/t:table_comments')  ?>">For comments</a></li>
  </ul>
</div>
<div id="tab_tag">
  <div id="tab1" class="ctable">
    <? if(!empty($reports)) :   ?>
    <? 
$funcname_approve = 'approve_report_'.md5(serialize($reports));
$funcname_delete = 'delete_report_'.md5(serialize($reports));
?>
    <script type="text/javascript">
function <? print $funcname_approve ?>($to_table, $to_table_id){
	
	$.post("<?php print site_url('admin/reports/approve')  ?>", { to_table: $to_table, to_table_id: $to_table_id },
  function(data){
    $("#report_row_id_"+$to_table+$to_table_id).remove();
  });

	
	
}


function <? print $funcname_delete ?>($to_table, $to_table_id){
	var answer = confirm("Are you sure? There is no turning back!")
	if (answer){
	$.post("<?php print site_url('admin/reports/delete')  ?>",{ to_table: $to_table, to_table_id: $to_table_id },
  function(data){
   $("#report_row_id_"+$to_table+$to_table_id).remove();
  });
	
	
	}
	else{
	//	alert("Thanks for sticking around!")
	}

	
	
	

	
	
}
</script>
    <table border="0" class="tablesorter ctab_le" id="sortedtable" cellspacing="0" cellpadding="0"  width="70%" >
      <thead id="sortable">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">To</th>
          <th scope="col">Delete content</th>
          <th scope="col">Delete all reports</th>
        </tr>
      </thead>
      <tbody>
        <? foreach($reports as $item):  ?>
        <tr id="report_row_id_<? print $item['to_table']; ?><? print $item['to_table_id']; ?>">
          <td><? print $item['id']; ?></td>
          <td><? if($item['to_table'] == 'table_content') : ?>
            <div> <img src="<? print   $thumb = $this->content_model->contentGetThumbnailForContentId($item['to_table_id'], 64); ?>" height="64" align="left" />
              <ul>
                <li><a href="<? print $this->content_model->contentGetHrefForPostId($item['to_table_id']) ; ?>"  target="_blank" ><? print $this->content_model->contentGetHrefForPostId($item['to_table_id']) ; ?></a></li>
                <li>
                  <? $content = $this->content_model->contentGetById($item['to_table_id']) ; print $content['content_title'] ?>
                </li>
              </ul>
            </div>
            <? endif;  ?>
            
            <? if($item['to_table'] == 'table_comments') : ?>
            <? $comment = $this->content_model->commentGetById($item['to_table_id']); ?>
            <? // p($comment) ?>
            <?  print($comment['comment_body']) ?>
            

            <? endif;  ?>
            
            </td>
          <td><span class="btn"> <a href="javascript:;" onclick="<? print $funcname_delete ?>('<? print $item['to_table']; ?>', '<? print $item['to_table_id']; ?>')">Delete the reported content</a> </span></td>
          <td>
            
      
            <span class="btn"> <a href="javascript:;" onclick="<? print $funcname_approve ?>('<? print $item['to_table']; ?>', '<? print $item['to_table_id']; ?>')" >Mark all reports for this as false</a> </span></td>
        </tr>
        <? endforeach; ?>
      </tbody>
    </table>
    <? if($content_pages_count > 1) :   ?>
    <div align="center" id="admin_content_paging">
      <? for ($i = 1; $i <= $content_pages_count; $i++) : ?>
      <a href="<? print  $content_pages_links[$i]  ?>" <? if($content_pages_curent_page == $i) :   ?> class="active" <? endif; ?> ><? print $i ?></a>
      <?  endfor; ?>
    </div>
    <? endif; ?>
    <? else : ?>
    <p><br />
      <br />
      No reports here!<br />
      <br />
    </p>
    <? endif; ?>
  </div>
</div>
<div class="clear"></div>
<br />
