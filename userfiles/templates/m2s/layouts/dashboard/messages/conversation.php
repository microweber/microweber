
<div id="wall">
  <?php //require (ACTIVE_TEMPLATE_DIR.'dashboard/messages/messages_nav.php') ?>
  <br />
  <div class="bluebox" >
    <div class="blueboxcontent">
    <?php $your_id = $this->users_model->userId ();
 
 if(intval($your_id ) == intval($messages[0]['from_user'])){
	 $between1 = 'you';
	 $contevsation_with = $messages[0]['to_user'];
	  $between2 = $this->users_model->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	   } else {
		    $contevsation_with = $messages[0]['from_user'];
		   	 $between2 = 'you';
	  $between1 = $this->users_model->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	 
 }
 
 
   
  
  ?>
  
  <h2>Converstion between <?php print $between1 ?> and <?php print $between2 ?></h2><br />
    
     <?php if(!empty($messages)): ?>

<?php foreach ($messages as $message) { ?>

<?php require  ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_item.php';?>
<?php } ?>
    </div>
    
    
    <mw module="messages/compose" to="<? print $contevsation_with; ?>" conversation="<? print $messages[0]['id'] ?>" />
    
    
   <!-- <a href="#" onclick="mw.users.UserMessage.compose(<? print $contevsation_with; ?>, <? print $messages[0]['id'] ?>);">Send a message</a>-->
    
    
    
    
    
    
    
    
    
    
    <a name="message_form"><!--  --></a>
<?php $reply_txt = true ;?>


<?php endif; ?>
  </div>
  <?php //paging('divs') ; ?>

  <?php //require ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_form.php';?>
</div>





 
  

  
  

 