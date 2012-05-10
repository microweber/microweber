<?php

/**
 * dashboard_items 
 *
 * @desc gets the user's dashboard_items
 * @access      public
 * @category    users
 * @author      Microweber 
 * @link        http://microweber.com
 * @param  $user_id - the is of the user. If false it will use the curent user (you) 
 * @param  $items_per_page - items per page
 */
function dashboard_items($params) {
	$CI = get_instance ();
	if (! is_array ( $params )) {
		$params = array ();
		$numargs = func_num_args ();
		if ($numargs > 0) {
			
			$params ['user_id'] = func_get_arg ( 0 );
			$params ['items_per_page'] = func_get_arg ( 1 );
			$params ['hide_friends'] = func_get_arg ( 2 );
			$params ['page'] = func_get_arg ( 3 );
		}
	} else {
	
	}
	
	 // $CI = get_instance ();
	if ($params ['user_id'] == false) {
		$user_id = $CI->core_model->userId ();
	} else {
		$user_id = $params ['user_id'];
	}
	
	if (strstr ( $user_id, ',' )) {
		$user_id = explode ( ',', $user_id );
	}
	
	$url = url ();
	if (! $params ['items_per_page']) {
		$some_items_per_page = 40;
	} else {
		$some_items_per_page = $params ['items_per_page'];
	}
	
	$query_options = array ();
	
	$query_options ['get_params_from_url'] = true;
	$query_options ['items_per_page'] = $some_items_per_page;
	$query_options ['debug'] = false;
	$query_options ['group_by'] = 'to_table, to_table_id';
	
	//$user_action = get_instance()->core_model->getParamFromURL ( 'action' );
	

	$log_params = array ();
	//$log_params [] = array ("notifications_parsed", 'y' );
	

	$followed = false;
	if (intval($params ['hide_friends']) == 0) {
		if ($user_action != 'live') {
			if ($user_id == user_id ()) {
				
				$followed = $CI->users_model->realtionsGetFollowedIdsForUser ( $aUserId = $user_id, false, false );
				
				if (empty ( $followed )) {
					//	$log_params [] = array ("id", '0' );
					//$followed [] = $user_id;
					//$log_params ["for_user_ids"] = $followed;
					$log_params [] = array ("user_id", $user_id );
				} else {
					if ($user_id == $CI->core_model->userId ()) {
						$followed [] = $user_id;
						$log_params ["for_user_ids"] = $followed;
					} else {
						$log_params [] = array ("user_id", $user_id );
						//$log_params ["user_id"] = $user_id;
					}
				}
			} else {
				if (is_array ( $user_id )) {
					$log_params ["for_user_ids"] = $user_id;
				} else {
					$log_params [] = array ("user_id", $user_id );
				}
				
			//$log_params ["user_id"] = $user_id;
			}
		
		}
	} else {
		//$log_params [] = array ("user_id", $user_id );
		if (is_array ( $user_id )) {
			$log_params ["for_user_ids"] = $user_id;
		} else {
			$log_params [] = array ("user_id", $user_id );
		}
	}
	if (intval ( $params ['page'] ) > 1) {
		$log_params ['page'] = $params ['page'];
	}
	//$log_params [] = array ("is_read", 'n' );
	
	
	  $CI->load->model ( 'Notifications_model', 'notifications_model' );

	
	$to_return = array ();
	
	$log = $CI->notifications_model->logGetByParams ( $log_params, $query_options );
	
	$query_options ['group_by'] = false;
	$query_options ['debug'] = false;
	$query_options ['get_count'] = true;
	$query_options ['items_per_page'] = false;
	$log_count = $CI->notifications_model->logGetByParams ( $log_params, $query_options );
	//p($log_count);
	$results_count = intval ( $log_count );
	$log_count = ceil ( $results_count / $some_items_per_page );
	
	$paging = get_instance()->content_model->pagingPrepareUrls ( $url, $log_count );
	
	$to_return ['paging'] = $paging;
	$to_return ['log'] = $log;
	
	return $to_return;
}

function get_log_item($log_id) {
	 // $CI = get_instance ();
		$CI = get_instance ();

	$log = $CI->notifications_model->logGetById ( $log_id );
	//var_dump($log);
	

	if (($log ['to_table'] == 'table_comments') and ($log ['rel_table'] == 'table_users_statuses')) {
		//$log_item = $CI->notifications_model->logDeleteById ( $log ['id'] );
		return false;
	}
	
	return $log;
}

function get_dashboard_action($log_id) {
	
	 // $CI = get_instance ();
	
	$log = get_log_item ( $log_id );
	
	$data = $log;
	
	$to_return = array ();
	
	switch ($data ['to_table']) {
		case 'table_users_statuses' :
			
			$stat = CI::model('statuses')->statusGetById ( intval ( $data ['to_table_id'] ) );
			
			$stat = html_entity_decode ( ($stat ['status']) );
			if (stristr ( $stat, 'http://' ) == true) {
				$term = 'shared a link';
				$stat = auto_link ( $stat );
				$embedly = true;
			} else {
				$term = 'said ';
				$embedly = false;
			}
			$to_return ['allow_comments'] = true;
			$to_return ['allow_likes'] = true;
			if ($embedly == false) {
				$to_return ['msg'] = "$term " . $stat;
			} else {
				$to_return ['msg'] = "<div class='embedly'>$term " . $stat . "</div>";
			}
			break;
		
		case 'table_users' :
			$to_return ['msg'] = "updated profile";
			$to_return ['allow_comments'] = false;
			$to_return ['allow_likes'] = false;
			
			break;
		
		case 'table_messages' :
			
			$to_return ['msg'] = "send a message to " . $data ['to_table_id'] . ".. must be implemented";
			$to_return ['allow_comments'] = false;
			$to_return ['allow_likes'] = false;
			break;
		
		case 'table_comments' :
			
			$to_return ['allow_comments'] = true;
			$to_return ['allow_likes'] = true;
			
			$comm = get_instance()->comments_model->commentGetById ( $data ['to_table_id'] );
			//p($comm);
			if ($comm ['to_table'] == 'table_content') {
				$content_data = get_instance()->content_model->contentGetByIdAndCache ( $comm ['to_table_id'] );
				$url = get_instance()->content_model->getContentURLByIdAndCache ( $comm ['to_table_id'] );
				$comm_txt = $comm ['comment_body'];
				$comm_txt = html_entity_decode ( $comm_txt );
				$comm_txt = auto_link ( $comm_txt );
				$thumb = thumbnail ( $content_data ['id'], 90 );
				
				//p($comm);
				$to_return ['msg'] = "commented on <a href='{$url}'>{$content_data['content_title']}</a><br> {$comm_txt}";
				//$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><a href='{$url}'><img class='embed_img' src='{$thumb}' border='0'></a><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
							$to_return ['msg'] .= "<div class='post_item'><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
				
			}
			
			if ($comm ['rel_table'] == 'table_content') {
				$content_data = get_instance()->content_model->contentGetByIdAndCache ( $comm ['rel_table_id'] );
				$url = get_instance()->content_model->getContentURLByIdAndCache ( $comm ['rel_table_id'] );
				$comm_txt = $comm ['comment_body'];
				$comm_txt = html_entity_decode ( $comm_txt );
				$comm_txt = auto_link ( $comm_txt );
				$thumb = thumbnail ( $content_data ['id'], 90 );
				
				//p($comm);
				$to_return ['msg'] = "commented on <a href='{$url}'>{$content_data['content_title']}</a><br> {$comm_txt}";
				//$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><a href='{$url}'><img class='embed_img' src='{$thumb}' border='0'></a><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
										$to_return ['msg'] .= "<div class='post_item'><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
				
			
			}
			
			if ($comm ['to_table'] == 'table_users_statuses') {
			
			}
			
			break;
		
		case 'table_votes' :
			$to_return ['allow_comments'] = false;
			$to_return ['allow_likes'] = false;
			
			$vote = get_instance()->votes_model->voteGetById ( $data ['to_table_id'] );
			if ($vote ['to_table'] == 'table_content') {
				$more = get_instance()->core_model->getCustomFields ( 'table_content', $vote ['to_table_id'] );
				$content_data = get_instance()->content_model->contentGetByIdAndCache ( $vote ['to_table_id'] );
				$url = get_instance()->content_model->getContentURLByIdAndCache ( $vote ['to_table_id'] );
				
				$to_return ['msg'] = "liked <a href='{$url}'>{$content_data['content_title']}</a>";
				
				if ($more ['embed_code']) {
					$emded_txt = "<textarea>" . html_entity_decode ( $more ['embed_code'] ) . "</textarea>";
				} else {
					$emded_txt = false;
				}
				$thumb = thumbnail ( $content_data ['id'], 90 );
				
				$desc = codeClean ( $content_data ['content_body'] );
				$desc = character_limiter ( $desc, 140, '...' );
				$desc = str_ireplace ( "\n", ' ', $desc );
				$desc = str_ireplace ( '\n', ' ', $desc );
				
				//$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><a href='{$url}'><img class='embed_img' src='{$thumb}' border='0'></a><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
								$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
				
				break;
			} else {
			
			}
		
		case 'table_content' :
			
			$content_data = get_instance()->content_model->contentGetById ( $data ['to_table_id'] );
			if (empty ( $content_data )) {
				$CI->notifications_model->logDeleteById ( $data ['id'] );
			} else {
				$more = get_instance()->core_model->getCustomFields ( 'table_content', $content_data ['id'] );
				
				$url = post_link ( $data ['to_table_id'] );
				$to_return ['allow_comments'] = true;
				$to_return ['allow_likes'] = true;
				
				$to_return ['msg'] = "published <a href='{$url}'>{$content_data['content_title']}</a>";
				if ($more ['embed_code']) {
					$emded_txt = "<textarea>" . html_entity_decode ( $more ['embed_code'] ) . "</textarea>";
				} else {
					$emded_txt = false;
				}
				$thumb = thumbnail ( $content_data ['id'], 90 );
				
				$desc = codeClean ( $content_data ['content_body'] );
				$desc = character_limiter ( $desc, 140, '...' );
				$desc = str_ireplace ( "\n", ' ', $desc );
				$desc = str_ireplace ( '\n', ' ', $desc );
				
				//$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><a href='{$url}'><img class='embed_img' src='{$thumb}' border='0'></a><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
				
								$to_return ['msg'] .= "<div class='post_item'><div style='height:10px;'>&nbsp;</div><h3 style='padding-bottom:5px;'><a href='{$url}'>{$content_data['content_title']}</a></h3> $desc  $emded_txt 	</div>";
				
				break;
			}
		
		case 'table_followers' :
			
			$data = get_instance()->core_model->getById ( $data ['to_table'], $data ['to_table_id'] );
			//p($data);  
			if (! empty ( $data )) {
				$to_return ['allow_comments'] = false;
				$to_return ['allow_likes'] = false;
				
				$data2 = get_instance()->users_model->getUserById ( $data ['follower_id'] );
				$url = profile_link ( $data ['follower_id'] );
				$name = get_instance()->users_model->getPrintableName ( $data ['follower_id'] );
				$thumb = user_thumbnail ( $data ['follower_id'], 70 );
				
				$to_return ['msg'] = "is friend with <a href='{$url}' class='user_photo' style='background-image:url({$thumb})'></a><a href='{$url}'>{$name}</a>";
			}
			break;
		
		default :
			p ( $data );
			
			break;
	
	}
	$to_return ['allow_votes'] = $to_return ['allow_likes'];
	
	return $to_return;
}

?>