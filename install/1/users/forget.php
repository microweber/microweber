<?php
include('../common.php');
/* not used !
$validation = array(
	'id'=>'email',
	'is'=>'not_email',
);
*/

if(isset($_REQUEST['action']) and $_REQUEST['action'] == t('Get Password')) {
	if(isset($_REQUEST['username']) and $_REQUEST['username']) {
		$user_details = $sql->getAssoc("SELECT username,name,password,email FROM ${config['db_prefix']}User WHERE username='$QUERY[username]'");

	} elseif(isset($_REQUEST['email']) and $_REQUEST['email']) {
		$user_details = $sql->getAssoc("SELECT username,name,password,email FROM ${config['db_prefix']}User WHERE email='$QUERY[email]'");

	} else {
		$QUERY['error'] = t('Please enter the Username or the Email address');
	}
	
	//If we get a valid user with the given details, send an email.
	if(isset($user_details)) {
		if(count($user_details)) {
			if($user_details['email']) {
				if (isset($config['locale']) && ($config['locale']='fr_FR')) {
					$email = <<<END
Bonjour $user_details[name],

Voici votre login et votre mot de passe pour votre compte Nexty.

Utilisateur : $user_details[username]
Mot de Passe : $user_details[password]

Pour vous connecter, allez sur
$config[url]users/login.php

Nexty.
END;
				} else {
					$email = <<<END
Hello $user_details[name],

This is the username and password for your nexty account.

Username : $user_details[username]
Password : $user_details[password]

To login, please go to
$config[url]users/login.php

Nexty.
END;
				}
				sendEMail('',$user_details['email'],$email,t('Account Details of Nexty'));
	
				$QUERY['success'] = t('An email containing your username and password was send to %s',$user_details['email']);
			} else {
				$QUERY['error'] = t('There is no email address associated with this account. Sorry!');
			}

		} else {
			$QUERY['error'] = t('No user was found with the given username/email');
		}
	}
}

render();