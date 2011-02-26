
<div id="wall">
  <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/messages/messages_nav.php') ?>
  <br />
  <div class="bluebox" >
    <div class="blueboxcontent">
      <?php foreach ($conversations as $message) {
		require 'message_item.php';
	} ?>
    </div>
  </div>
  <?php paging('divs') ; ?>
</div>
