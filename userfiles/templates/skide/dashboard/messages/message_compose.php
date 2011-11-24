

<?php dbg(__FILE__); ?>
<h2 class="title" style="font-size:18px">Send message to <span id="send-msg-to-name"><?php $message_to_user['first_name']? print $message_to_user['first_name'] : print $message_to_user['username'] ;  ?></span></h2>

<form method="post" id="message-compose" class="form">

<input name="mk" type="hidden" value="<?php print (CI::model('core')->securityEncryptString ( CI::model('users')->userId () )) ?>" />
<input name="from_user" type="hidden" value="<?php print intval( CI::model('users')->userId () ) ?>" />
<input name="receiver" id="message-compose-receiver" type="hidden" value="<?php print intval( $message_to_user['id'] ) ?>" />

<label class="block" style="padding: 8px 0 5px">Receiver:</label>

<span class="linput"><input id="receiver_name" name="receiver_name" type="text" value="<?php $message_to_user['first_name']? print $message_to_user['first_name'] : print $message_to_user['username'] ;  ?>" style="width:370px" /></span>
 
<script type="text/javascript">

<?php $tempname =  'setReceiverId_'.rand(); ?>


function <?php print $tempname ?>(id, names){
	$('#message-compose-receiver').val(id);
	 $('#receiver_name').val(names);
	 $('#send-msg-to-name').html(names);
}




  $(document).ready(function(){
 
 var receiver_name_cache = {};

 	$.widget("custom.autocomplete", $.ui.autocomplete, {
		_renderMenu: function( ul, items ) {
			var self = this,
				currentCategory = "";
			$.each( items, function( index, item ) {
				//if ( item.category != currentCategory ) {
					//ul.append( "<li class='ui-autocomplete-category'>" + item.username + "</li>" );
					//currentCategory = item.category;
				//}
				self._renderItem( ul, item );
			});
		}
	});
	

		 
		
		$('#receiver_name').autocomplete({
			delay: 500,

			source: function(request, response) {
				if ( request.term in receiver_name_cache ) {
					response( receiver_name_cache[ request.term ] );
					return;
				}
				
				$.ajax({
					url: "<?php print site_url('api/user/search_by_name'); ?>",
					type: "POST",
					dataType: "json",
					data: request,
					success: function( data ) {
						receiver_name_cache[ request.term ] = data;
						response( data );
					}
				});
			}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a onclick='<?php print $tempname ?>(" + item.id + ",\"" + item.first_name + " " + item.last_name + "\")'>"+ " " + item.first_name + " " + item.last_name + "(" + item.username + ")</a>" )
				.appendTo( ul );
		};

 
 
 
	  
    });



</script>

 

<label class="block" style="padding: 8px 0 5px">Subject:</label>
<span class="linput"><input name="subject" type="text" style="width:370px" /></span>
<label class="block" style="padding: 8px 0 5px">Message</label>
<span class="larea" style="padding-right:5px;"><textarea name="message" cols="" rows="" style="width:363px;height:140px;"></textarea></span>
<?php /* <input name="send" value="send" type="button" onClick="mw.users.UserMessage.sendQuick(this)" /> */ ?>
<div class="c" style="padding-bottom: 12px;">&nbsp;</div>
<a href="javascript:;" onClick="mw.users.UserMessage.sendQuick(this)" class="btn right">Send</a>
</form>


<?php dbg(__FILE__, 1); ?>
 

