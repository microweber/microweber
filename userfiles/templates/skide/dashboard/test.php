Links:

	<?php for ($i = 0; $i < 10; $i++) { ?>
	Status <?php echo $i;?>:<br />
	Votes: <span id="status-votes-<?php echo $i;?>">xxx</span>  Comments: <span id="status-comments-<?php echo $i;?>">xxx</span><br /><br />
	<?php } ?>

	<?php for ($i = 0; $i < 10; $i++) { ?>
	Content <?php echo $i;?>:<br />
	Votes: <span id="content-votes-<?php echo $i;?>">xxx</span>  Comments: <span id="content-comments-<?php echo $i;?>">xxx</span><br /><br />
	<?php } ?>

<div onclick="mw.users.Dashboard.getCounts(<?php echo $statuses_ids_json;?>, <?php echo $contents_ids_json;?>);">load voated and comments count</div>
	
	
<script>

//after vote/comment call mw.users.Dashboard.getCounts to update wanted items

var Dashboard = new function() {
	
	this.servicesUrl = '/ajax_helpers/',
	
	this.getCounts = function (statusesIds, contentsIds) {
    	$.post(
        	this.servicesUrl + 'dashboardCounts', 
        	{ statusesIds: statusesIds, contentsIds: contentsIds },
        	function(response){

        		// load votes stats
        		var stats = response['statuses'];
        		for(var i = 0; i < stats['votes'].length; i ++) {
                	var itemId = stats['votes'][i]['item_id'];
                	var etemValue = stats['votes'][i]['votes_total'];
					$('#status-votes-'+itemId).html(etemValue);
            	}
        		for(var i = 0; i < stats['comments'].length; i ++) {
                	var itemId = stats['comments'][i]['item_id'];
                	var etemValue = stats['comments'][i]['comments_total'];
					$('#status-comments-'+itemId).html(etemValue);
            	}
            	
            	// load comments stats
        		stats = response['contents'];
        		for(var i = 0; i < stats['votes'].length; i ++) {
                	var itemId = stats['votes'][i]['item_id'];
                	var etemValue = stats['votes'][i]['votes_total'];
					$('#content-votes-'+itemId).html(etemValue);
            	}
        		for(var i = 0; i < stats['comments'].length; i ++) {
                	var itemId = stats['comments'][i]['item_id'];
                	var etemValue = stats['comments'][i]['comments_total'];
					$('#content-comments-'+itemId).html(etemValue);
            	}
            	
        	},
        	"json"
        );
    },

    this.comment = function() {
		alert("Add comment");
    },

    this.vote = function() {
		alert("Vote item");
    }
        
}


$(document).ready(function(){
	//mw.users.Dashboard.getCounts(<?php echo $statuses_ids_json;?>, <?php echo $contents_ids_json;?>);
});

</script>