<?php
$username = CI::model('core')->getParamFromURL ( 'username' );

//print $username;


$users_list = array ();

$users_list ['username'] = $username;

$users_list = CI::model('users')->getUsers ( $users_list );

$currentUser = $users_list [0];

$this->template ['author'] = $currentUser;

//     p($currentUser);


/*~~~~~~ Following and followers ~~~~~~~*/

// get all relations that user take part
/*$relations = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'followers', array (array ('user_id', $currentUser ['id'] ), array ('follower_id', $currentUser ['id'], '=', 'OR' ) ) );

				$followingIds = array ();
				$followersIds = array ();
				$circleOfInfluenceIds = array ();

				foreach ( $relations as $relation ) {
					if ($currentUser ['id'] == $relation ['user_id']) {
						$followingIds [] = $relation ['follower_id'];
						if ($relation ['is_special'] == 'y') {
							$circleOfInfluenceIds [] = $relation ['follower_id'];
						}
					} else {
						$followersIds [] = $relation ['user_id'];
					}

				}

				$following = array ();
				if ($followingIds) {
					$following = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'users', array (array ('id', '(' . implode ( ',', $followingIds ) . ')', 'IN' ) ) );
				}

				$followers = array ();
				if ($followersIds) {
					$followers = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'users', array (array ('id', '(' . implode ( ',', $followersIds ) . ')', 'IN' ) ) );
				}

				$circleOfInfluence = array ();
				foreach ( $following as $user ) {
					if (in_array ( $user ['id'], $circleOfInfluenceIds )) {
						$circleOfInfluence [] = $user;
					}
				}

				$circleIds = array ();
				foreach ( $circleOfInfluence as $user ) {
					$circleIds [] = $user ['id'];
				}

				//				$r = json_encode($circleIds);
				//				p(addslashes($r), 1);


				$this->template ['following'] = $following;
				$this->template ['followers'] = $followers;
				$this->template ['circle'] = $circleOfInfluence;
				$this->template ['circle_ids_json'] = json_encode ( $circleIds );

			 */

$this->load->vars ( $this->template );

$content ['content_filename'] = 'users/userbase/user_profile.php';
