<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

$show = CI::model('core')->getParamFromURL ( 'show' );
$conversation = CI::model('core')->getParamFromURL ( 'conversation' );
$currentUser = CI::library('session')->userdata ( 'user' );
$userid = CI::model('core')->userId ();
$show_inbox = CI::model('core')->getParamFromURL ( 'show_inbox' );
if ($show == false and $conversation == false) {
	$show = 'read';
}

if ($show_inbox == 1 and $conversation == false) {
	$show = 'unread';
}

if ($show == 'unread') {
	$unreadedMessages = CI::model('messages')->messagesGetUnreadForUser ( CI::model('core')->userId () );
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
	$opts ['order'] = array('id', 'desc');
	
	 
	
	
	
	$conversations = CI::model('messages')->messagesGetByDefaultParams ( false, $opts );
	$opts = array ();
	$opts ['get_count'] = true;
	$opts ['items_per_page'] = 100;
	$conversations_count = CI::model('messages')->messagesGetByDefaultParams ( false, $opts );
	$results_count = intval ( $conversations_count );
	$pages_count = ceil ( $results_count / $some_items_per_page );
	
	$url = site_url ( 'dashboard/action:messages/show:read' );
	$paging = CI::model('content')->pagingPrepareUrls ( $url, $pages_count );
	$this->template ['posts_pages_links'] = $paging;
	
	$this->template ['conversations'] = $conversations;
	$content ['content_filename'] = 'dashboard/messages/conversations.php';
}

if ($show == 'sent') {
	
	$params = array ();
	
	$params [] = array ('from_user',  CI::model('core')->userId () );
	
	$this->template ['show'] = 'sent';
	$some_items_per_page = 1;
	$opts = array ();
	$opts ['get_count'] = false;
	$opts ['items_per_page'] = $some_items_per_page;
	$conversations = CI::model('messages')->messagesGetByDefaultParams ( $params, $opts );
	$opts = array ();
	$opts ['get_count'] = true;
	$opts ['items_per_page'] = 1;
	$conversations_count = CI::model('messages')->messagesGetByDefaultParams ( $params, $opts );
	$results_count = intval ( $conversations_count );
	$pages_count = ceil ( $results_count / $some_items_per_page );
	
	$url = site_url ( 'dashboard/action:messages/show:read' );
	$paging = CI::model('content')->pagingPrepareUrls ( $url, $pages_count );
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
		$q = CI::model('core')->dbQ ( $q );
	}
	
	$params = array ();
	$params [] = array ('id', $conversation );
	$parentMessage = CI::model('messages')->messagesGetByParams ( $params, $options = false );
	$parentMessage = $parentMessage [0];
	
	if ($parentMessage ['from_user'] == CI::model('core')->userId ()) {
		$receiver = $parentMessage ['to_user'];
	} elseif ($parentMessage ['to_user'] == CI::model('core')->userId ()) {
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
	
	$messages = CI::model('messages')->messagesThread ( $conversation );
	
	$this->template ['messages'] = $messages;
	
	$this->template ['message_parent'] = $conversation;
	$this->template ['message_receiver'] = $receiver;
	$content ['content_filename'] = 'dashboard/messages/conversation.php';

}

/*







if ($showUnreaded) {
	// show all unreaded messages


	$unreadedMessages = CI::model('messages')->messagesGetUnreadForUser ( CI::model('core')->userId () );
	$this->template ['messages'] = $unreadedMessages;
	$content ['content_filename'] = 'dashboard/messages/unreaded.php';

} elseif ($conversation) {
	$conversation = intval ( $conversation );
	if (intval ( $conversation ) > 0) {
		$q = "UPDATE " . TABLE_PREFIX . 'messages' . " SET is_read='y' where  (id = {$conversation} OR parent_id = {$conversation})
and to_user=$userid
		 ";
		$q = CI::model('core')->dbQ ( $q );
	}


	$params = array ();
	$params [] = array ('id', $conversation );
	 $parentMessage = CI::model('messages')->messagesGetByParams ( $params, $options = false );
	$parentMessage = $parentMessage [0];

	if ($parentMessage ['from_user'] == CI::model('core')->userId ()) {
		$receiver = $parentMessage ['to_user'];
	} elseif ($parentMessage ['to_user'] == CI::model('core')->userId ()) {
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


	$messages = CI::model('messages')->messagesThread ( $conversation );

	$this->template ['messages'] = $messages;
	$this->template ['message_parent'] = $conversation;
	$this->template ['message_receiver'] = $receiver;
	$content ['content_filename'] = 'dashboard/messages/conversation.php';

} else {
	// show all conversations
	$currentUser ['id'] = intval ( CI::model('core')->userId () );
	if (CI::model('core')->userId () == 0) {
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

	$conversations = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'messages', $q, $db_opts );
	//p ( $conversations );
	//$conversations = CI::model('messages')->messagesGetByParams ( $params, $options = false );


	//	$conversations = CI::model('core')->fetchDbData ( 'firecms_messages', "parent_id is NULL AND (from_user = {$currentUser['id']} AND deleted_from_sender = 'n' OR to_user = {$currentUser['id']} AND deleted_from_receiver = 'n')" );


	$this->template ['conversations'] = $conversations;
	$content ['content_filename'] = 'dashboard/messages/conversations.php';
}
*/
?>