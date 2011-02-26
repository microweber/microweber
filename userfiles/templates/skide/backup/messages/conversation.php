
<div id="wall">
  <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/messages/messages_nav.php') ?>
  <br />
  <div class="bluebox" >
    <div class="blueboxcontent">
    <?php $your_id = CI::model('users')->userId ();
 
 if(intval($your_id ) == intval($messages[0]['from_user'])){
	 $between1 = 'you';
	  $between2 = CI::model('users')->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	   } else {
		   
		   	 $between2 = 'you';
	  $between1 = CI::model('users')->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	 
 }
 
 
   
  
  ?>
  
  <h2>Converstion between <?php print $between1 ?> and <?php print $between2 ?></h2><br />
    
     <?php if(!empty($messages)): ?>

<?php foreach ($messages as $message) { ?>

<?php require  ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_item.php';?>
<?php } ?>
    </div>
    
    <a name="message_form"><!--  --></a>
<?php $reply_txt = true ;?>


<?php endif; ?>
  </div>
  <?php paging('divs') ; ?>

  <?php require ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_form.php';?>
</div>





 
  

  
  

 