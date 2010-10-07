<?php dbg(__FILE__); ?>
<!--Dashboard index page      -->
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_top_nav.php') ?>
  
  
  
 <?php $statusRow = $this->users_model->statusesLastByUserId (); 
 //p($statusRow);
   ?>
  
  <form method="post" action="#" id="update-status" class="dashboard-update-status">
    <div class="status">
        <input name="status" type="text" value="<?php print $statusRow['status']; ?>" class="type-text" />
    </div>
    <small id="update-status-done" style="display:none">Status updated...</small>
    <a href="#" class="btn submit update-status-btn">Update status</a>
    <input type="submit" value="" class="Xsubmit right" />
  </form>

  <div class="c" style="padding-top: 17px;">&nbsp;</div>
  
  
  
  

  <?php if (!empty($dashboard_log)) : ?>
	
	<?php foreach ($dashboard_log as $log) { ?>
    
		<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/index_item.php') ?>
	<?php }?>
	
    
    
    
    
    
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_paging.php') ?>
 
 
 <?php else: ?>

 <div class="noposts">There is no content in your dashboard.</div>

   <?php endif; ?>
   


</div><!-- /#profile-main -->

<script type="text/javascript">

function getComments(toTable, toTableId, updateElementId)
{
    $.post(
        '/ajax_helpers/comments_get_for_dashboard', 
        { t: toTable, tt: toTableId },
        function(response){
			$('#'+updateElementId).prepend(response);
			
        }
	);
}
 /* moved to users/users.js.php
function voteUp(toTable, toTableId, updateElementId)
{
    $.post(
        '/ajax_helpers/votes_cast', 
        { t: toTable, tt: toTableId },
        function(response){
            if (response == 'yes') {
                // increase votes count
            	$('#'+updateElementId).html(parseInt($('#'+updateElementId).html()) + 1);
            }
        }
	);
}*/

var User1 = new function() {

	this.servicesUrl = '/ajax_helpers/',
	
	/*~~~ private methods ~~~*/
	
	this._beforeSend = function() {
	
		var isValid;
	    if($(".commentForm").hasClass("error")){
	    	isValid = false;
	    } else{
	    	isValid = true;
	    }
	
	    return isValid;
	}
	    
	this._afterSend = function(t, tt, updateElementId) {
		//getComments(t, tt, updateElementId);
	}
	
	/*~~~ public methods ~~~*/
	
	this.postComment = function (form, t, tt, updateElementId) {
		
		var requestOptions = {
				url:			this.servicesUrl + 'comments_post',
				clearForm: 		true,
				type:      		'post',
		        beforeSubmit:  	this._beforeSend, 
		        success:       	this._afterSend(t, tt, updateElementId)
		};
	
		form.ajaxSubmit(requestOptions);
	    return false;
	};
}


$(document).ready(function(){
    $('#update-status').submit(function(){
    	return mw.users.User.statusUpdate($(this));
    });
   /* $('.commentForm').submit(function(){
    	return mw.comments.postComment(
    	    	$(this), 
    	    	$(this).find("input[name='to_table']").val(), 
    	    	$(this).find("input[name='to_table_id']").val(),
    	    	$(this).find("input[name='related_list']").val()
    	    );
    });*/
 //   mw.users.Dashboard.getCounts(<?php echo $statuses_ids_json;?>, <?php echo $contents_ids_json;?>);
});
</script>
<?php dbg(__FILE__, 1); ?>