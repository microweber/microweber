<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

$show = $this->core_model->getParamFromURL ( 'show' );
$conversation = $this->core_model->getParamFromURL ( 'conversation' );
$currentUser = $this->session->userdata ( 'user' );
$userid = $this->core_model->userId ();
$show_inbox = $this->core_model->getParamFromURL  ( 'show_inbox' );
if ($show == false and $conversation == false) {
	$show = 'read';
}

if ($show_inbox == 1 and $conversation == false) {
	$show = 'unread';
}




if ($show == 'unread') {
	$unreadedMessages = $this->users_model->messagesGetUnreadForUser ( $this->core_model->userId () );
	$this->template ['conversations'] = $unreadedMessages;
	$this->template ['show'] = 'unread';
	$content ['content_filename'] = 'dashboard/messages/conversations.php';
}

if ($show == 'read') {
$this->template ['show'] = 'read';
	$some_items_per_page = 50;
	$opts = array ();
	$opts ['get_count'] = false;
	$opts ['items_per_page'] = $some_items_per_page;
	$conversations = $this->users_model->messagesGetByDefaultParams ( false, $opts );
	$opts = array ();
	$opts ['get_count'] = true;
	$opts ['items_per_page'] = 2000;
	$conversations_count = $this->users_model->messagesGetByDefaultParams ( false, $opts );
	$results_count = intval ( $conversations_count );
	$pages_count = ceil ( $results_count / $some_items_per_page );

	$url = site_url ( 'dashboard/action:messages/show:read' );
	$paging = $this->content_model->pagingPrepareUrls ( $url, $pages_count );
	$this->template ['posts_pages_links'] = $paging;

	$this->template ['conversations'] = $conversations;
	$content ['content_filename'] = 'dashboard/messages/conversations.php';
}

if ($conversation != false) {
	$this->template ['show'] = 'conversation';
	$conversation = intval ( $conversation );
	if (intval ( $conversation ) > 0) {
		$q = "UPDATE " . TABLE_PREFIX . 'messages' . " SET is_read='y' where  (id = {$conversation} OR parent_id = {$conversation})
and to_user=$userid
		 ";
		$q = $this->core_model->dbQ ( $q );
	}

	$params = array ();
	$params [] = array ('id', $conversation );
	$parentMessage = $this->users_model->messagesGetByParams ( $params, $options = false );
	$parentMessage = $parentMessage [0];

	if ($parentMessage ['from_user'] == $this->core_model->userId ()) {
		$receiver = $parentMessage ['to_user'];
	} elseif ($parentMessage ['to_user'] == $this->core_model->userId ()) {
		$receiver = $parentMessage ['from_user'];
	} else {
		//throw new Exception ( 'You have no permission to view this conversation.' );
		exit ( 'You have no permission to view this conversation.' );
	}

	$q = "(id = {$conversation}
	OR parent_id = {$conversation})
	AND ((from_user = {$currentUser['id']}
	AND deleted_from_sender = 'n') OR (to_user = {$currentUser['id']}
	AND deleted_from_receiver = 'n'))";

	$messages = $this->users_model->messagesThread ( $conversation );

	$this->template ['messages'] = $messages;



	$this->template ['message_parent'] = $conversation;
	$this->template ['message_receiver'] = $receiver;
	$content ['content_filename'] = 'dashboard/messages/conversation.php';

}

/*







if ($showUnreaded) {
	// show all unreaded messages


	$unreadedMessages = $this->users_model->messagesGetUnreadForUser ( $this->core_model->userId () );
	$this->template ['messages'] = $unreadedMessages;
	$content ['content_filename'] = 'dashboard/messages/unreaded.php';

} elseif ($conversation) {
	$conversation = intval ( $conversation );
	if (intval ( $conversation ) > 0) {
		$q = "UPDATE " . TABLE_PREFIX . 'messages' . " SET is_read='y' where  (id = {$conversation} OR parent_id = {$conversation})
and to_user=$userid
		 ";
		$q = $this->core_model->dbQ ( $q );
	}


	$params = array ();
	$params [] = array ('id', $conversation );
	 $parentMessage = $this->users_model->messagesGetByParams ( $params, $options = false );
	$parentMessage = $parentMessage [0];

	if ($parentMessage ['from_user'] == $this->core_model->userId ()) {
		$receiver = $parentMessage ['to_user'];
	} elseif ($parentMessage ['to_user'] == $this->core_model->userId ()) {
		$receiver = $parentMessage ['from_user'];
	} else {
		//throw new Exception ( 'You have no permission to view this conversation.' );
		exit ( 'You have no permission to view this conversation.' );
	}

	$q = "(id = {$conversation}
	OR parent_id = {$conversation})
	AND ((from_user = {$currentUser['id']}
	AND deleted_from_sender = 'n') OR (to_user = {$currentUser['id']}
	AND deleted_from_receiver = 'n'))";


	$messages = $this->users_model->messagesThread ( $conversation );

	$this->template ['messages'] = $messages;
	$this->template ['message_parent'] = $conversation;
	$this->template ['message_receiver'] = $receiver;
	$content ['content_filename'] = 'dashboard/messages/conversation.php';

} else {
	// show all conversations
	$currentUser ['id'] = intval ( $this->core_model->userId () );
	if ($this->core_model->userId () == 0) {
		exit ( "Error in " . __FILE__ . " on line " . __LINE__ );
	}

	//$q = "parent_id is NULL AND (from_user = {$currentUser['id']} AND deleted_from_sender = 'n' OR to_user = {$currentUser['id']} AND deleted_from_receiver = 'n')";
	$q = " (to_user = {$currentUser['id']}
	AND deleted_from_receiver = 'n'
	AND is_read = 'n'
	and from_user !={$currentUser['id']} )";

	$cache_group = 'users/messages';
	$db_opts = array ();
	$db_opts ['cache'] = true;
	//$db_opts ['debug'] = true;
	$db_opts ['cache_group'] = $cache_group;
	$db_opts ['order'] = array (array ('created_on', 'DESC' ) );

	$conversations = $this->core_model->fetchDbData ( TABLE_PREFIX . 'messages', $q, $db_opts );
	//p ( $conversations );
	//$conversations = $this->users_model->messagesGetByParams ( $params, $options = false );


	//	$conversations = $this->core_model->fetchDbData ( 'firecms_messages', "parent_id is NULL AND (from_user = {$currentUser['id']} AND deleted_from_sender = 'n' OR to_user = {$currentUser['id']} AND deleted_from_receiver = 'n')" );


	$this->template ['conversations'] = $conversations;
	$content ['content_filename'] = 'dashboard/messages/conversations.php';
}
*/
?>