<?php
	/**
	 * GIT DEPLOYMENT SCRIPT
	 *
	 * Used for automatically deploying websites via github or bitbucket, more deets here:
	 *
	 *		https://gist.github.com/1809044
	 */

	// The commands
	chdir("mw_git/Microweber/");
	$commands = array(
		//'cd ~/public_html/service/update/mw_git/Microweber/', 
		'echo $PWD',
		'whoami',
		
		
		'git branch origin/master',
		'git checkout origin/master',
 		'git pull origin master',
		'git status',
		'git fetch --all',
		'git sync',
/*		
		'git submodule sync',
		'git submodule update',
		'git submodule status',
		'git reset --hard HEAD ',
		'git clean -f -d',
		'git pull'*/
	);
	
	$commands = array(
		//'cd ~/public_html/service/update/mw_git/Microweber/', 
		'echo $PWD',
		'whoami',
		
		'git stash',
		'git fetch origin',
		'git reset --hard origin/master',
 
		
		
		
		/*'git fetch --all',
		'git fetch origin/master',
		'git merge FETCH_HEAD',
		'git fetch origin +refs/heads/*:refs/remotes/origin/*',*/

		//'git checkout -b master origin/master',
		//'git checkout origin master',
		//'git fetch origin master',
		'git status',
		 
		// 'git reset --hard HEAD ',
		//'git clean -f -d',
		'git sync',
		'git pull'
	);

  
 
	// Run the commands for output
	$output = '';
	foreach($commands AS $command){
		// Run it
		$tmp = shell_exec($command);
		// Output
		$output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
		$output .= htmlentities(trim($tmp)) . "\n";
	}

	// Make it pretty for manual user access (and why not?)
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>GIT DEPLOYMENT SCRIPT</title>
</head>
<body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
<pre>
 .  ____  .    ____________________________
 |/      \|   |                            |
[| <span style="color: #FF0000;">&hearts;    &hearts;</span> |]  | Git Deployment Script v0.1 |
 |___==___|  /              &copy; oodavid 2012 |
              |____________________________|

<?php echo $output; ?>
</pre>
</body>
</html>