<?php
	$HYBRIDAUTH_VERSION = "2.1.2";
?><html> 
<head>
<title>HybridAuth Installer</title>
<meta name="robots" content="NOINDEX, NOFOLLOW">  
<style type="text/css">
#content {
    background: none repeat scroll 0 0 #FFFFFF; 
    margin: 0 0 0 10px;
    padding: 0;
}
.inputgnrc, select {
    font-size: 15px;
    padding: 6px 3px; 
    border: 1px solid #CCCCCC;
    border-radius: 4px 4px 4px 4px;
    color: #444444;
    font-family: arial;
    font-size: 16px;
    margin: 0;
    padding: 3px;
    width: 300px;
} 
.inputsave {
    font-size: 15px;
    padding: 6px 3px;  
    color: #444444;
    font-family: arial;
    font-size: 18px;
    margin: 0;
    padding: 3px;
    width: 400px;
	height: 40px;
} 
ul {
    list-style: none outside none; 
}
.cgfparams ul {
    padding: 0;
	margin: 0;
}
ul li {
    color: #555555;
    font-size: 12px;
    margin-bottom: 10px;
    padding: 0;
}
ul li label {
    color: #000000;
    display: block;
    font-size: 14px;
    font-weight: bold;
	padding-bottom: 5px;
}
.cfg { 
	background: #f5f5f5;
	float: left;
	border-radius: 2px 2px 2px 2px;
	border: 1px solid #AAAAAA;
	margin: 0 0 0 30px;
}
.cgfparams {
   padding: 20px;
   float: left;
}
.cgftip {
   border-left: 1px solid #aaa;
   margin-left: 340px;
   padding: 20px;
   min-height: 202px; 
   width: 770px;
   width: 600px;
   
   padding-top: 1px;
} 
</style> 
</head>
<body>
<?php 
	$CONFIG_TEMPLATE                = "";

   /**
	* Utility function, return the current url 
	*/
	function getCurrentUrl() 
	{
		if(
			isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 )
		|| 	isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
		){
			$protocol = 'https://';
		}
		else {
			$protocol = 'http://';
		}

		$url = $protocol . $_SERVER['HTTP_HOST'];

		// use port if non default
		$url .= 
			isset( $_SERVER['SERVER_PORT'] ) 
			&&( ($protocol === 'http://' && $_SERVER['SERVER_PORT'] != 80) || ($protocol === 'https://' && $_SERVER['SERVER_PORT'] != 443) )
			? ':' . $_SERVER['SERVER_PORT'] 
			: ''
;
		$url .= $_SERVER['PHP_SELF'];

		// return current url
		return $url;
	}

	$GLOBAL_HYBRID_AUTH_URL_BASE    = getCurrentUrl();
	$GLOBAL_HYBRID_AUTH_URL_BASE    = str_ireplace( "install.php", "", $GLOBAL_HYBRID_AUTH_URL_BASE );
	$GLOBAL_HYBRID_AUTH_PATH_BASE   = realpath( dirname( __FILE__ ) ) . "/";
	$CONFIG_FILE_NAME               = $GLOBAL_HYBRID_AUTH_PATH_BASE . "config.php";

	// deault providers
	$PROVIDERS_CONFIG      = ARRAY(
								ARRAY( 
									"label"             => "Facebook",
									"provider_name"     => "Facebook", 
									"require_client_id" => TRUE, 
									"new_app_link"      => "https://www.facebook.com/developers/",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Facebook.html",
								)
								,
								ARRAY( 
									"label"             => "Google",
									"provider_name"     => "Google", 
									"callback"          => TRUE,
									"require_client_id" => TRUE, 
									"new_app_link"      => "https://code.google.com/apis/console/",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html",
								) 
								,
								ARRAY( 
									"label"             => "Twitter",
									"provider_name"     => "Twitter",  
									"new_app_link"      => "https://dev.twitter.com/apps",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Twitter.html",
								)
								,
								ARRAY( 
									"label"             => "Yahoo",
									"provider_name"     => "Yahoo!", 
									"require_client_id" => TRUE, 
									"new_app_link"      => "https://developer.apps.yahoo.com/dashboard/createKey.html",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Yahoo.html",
								)
								,
								ARRAY( 
									"label"             => "Live",
									"provider_name"     => "Windows Live", 
									"require_client_id" => TRUE, 
									"new_app_link"      => "https://manage.dev.live.com/ApplicationOverview.aspx",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Live.html",
								)
								,
								ARRAY( 
									"label"             => "MySpace",
									"provider_name"     => "MySpace", 
									"new_app_link"      => "http://www.developer.myspace.com/",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_MySpace.html",
								)
								,
								ARRAY( 
									"label"             => "Foursquare",
									"provider_name"     => "Foursquare", 
									"require_client_id" => TRUE, 
									"callback"          => TRUE,
									"new_app_link"      => "https://www.foursquare.com/oauth/",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_Foursquare.html",
								)
								,
								ARRAY( 
									"label"             => "LinkedIn",
									"provider_name"     => "LinkedIn",  
									"new_app_link"      => "https://www.linkedin.com/secure/developer",
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_LinkedIn.html",
								)
								,
								ARRAY( 
									"label"             => "OpenID",
									"provider_name"     => "OpenID", 
									"new_app_link"      => NULL,
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_OpenID.html",
								)
								,
								ARRAY( 
									"label"             => "AOL",
									"provider_name"     => "AOL", 
									"new_app_link"      => NULL,
									"userguide_section" => "http://hybridauth.sourceforge.net/userguide/IDProvider_info_AOL.html",
								)
							);

	if( count( $_POST ) ):
		$CONFIG_TEMPLATE = file_get_contents( "Hybrid/resources/config.php.tpl" );
 
		foreach( $_POST AS $k => $v ):
			$v = strip_tags( $v );
			$z = "#$k#";
			
			$CONFIG_TEMPLATE = str_replace( $z, $v, $CONFIG_TEMPLATE );
		endforeach;

		$CONFIG_TEMPLATE = str_replace( "<?php", "<?php\n\t#AUTOGENERATED BY HYBRIDAUTH $HYBRIDAUTH_VERSION INSTALLER - " . date("l jS \of F Y h:i:s A") . "\n", $CONFIG_TEMPLATE );

		$is_installed = file_put_contents( $GLOBAL_HYBRID_AUTH_PATH_BASE . "config.php",  $CONFIG_TEMPLATE );

		if( ! $is_installed ):
	?>
		<p style='background-color:#EE3322;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #C52F24 solid;'><strong>Installation Error: </strong> HybridAuth configuration file <span style='color:#000000;font-weight:normal;'><?php echo $CONFIG_FILE_NAME; ?></span> must be <b >WRITABLE</b> in order for the installer to work.</p>
		<br />
		Please try again!
	<?php
		else:
	?>
		<center>
		<table width="90%" border="0">
		<tr>
		<td align="left"> 
		<div id="content">
			<p style='background-color:#390;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #030 solid;'><strong>Installation completed: </strong> HybridAuth has been successfully installed on your web server.</p>

			<h1 style="margin-bottom: 15px;">HybridAuth <?php echo $HYBRIDAUTH_VERSION; ?> Installer</h1> 
			<hr />
			<br /> 

			<ul style="list-style:disc inside;"> 
				<li style="color: #000000;font-size: 14px;"><b style="color:red">Don't forget to delete</b> "<b>install.php</b>".</li>
				<li style="color: #000000;font-size: 15px;">Visit the <a href="../examples/">examples</a> directory to try some working demos.</li> 
				<li style="color: #000000;font-size: 15px;">Check out HybridAuth documentation at <a href="http://hybridauth.sourceforge.net">http://hybridauth.sourceforge.net</a>.</li> 
			</ul> 

			<br /> 			
			<b style="font-size: 17px;">and that is it!</b>
		<div>
		</td>
		</tr>
		</table> 
		</div>
	<?php
			endif;
		die();
	endif;
?>
<center>
<table width="90%" border="0">
<tr>
<td align="left">

<div id="content"> 
	<?php
		// check if php 5+. well donno the exact version to test, because it depend on which providers will be used..
		if ( version_compare( PHP_VERSION, '5.2', '<=' ) ):
	?>
		<p style='background-color:#EE3322;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #C52F24 solid;'><strong>Error: </strong> HybridAuth requires PHP 5.2 or higher</p>
	<?php
		endif;
	?> 

	<?php
		// check config file is writable
		if( ! is_writable( $CONFIG_FILE_NAME ) ):
	?>
		<p style='background-color:#EE3322;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #C52F24 solid;'><strong>Error: </strong> HybridAuth configuration file <span style='color:#000000;font-weight:normal;'><?php echo $CONFIG_FILE_NAME; ?></span> must be <b >WRITABLE</b> in order for the installer to work.</p>
	<?php
		endif;
	?> 

	<?php
		// check if curl is enabled
		if( ! in_array  ( 'curl', get_loaded_extensions() ) ):
	?>
		<p style='background-color:#EE3322;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #C52F24 solid;'><strong>Error: </strong>HybridAuth will require to use <a href="http://php.net/manual/en/book.curl.php" style="color:white" target="_blank"><b>CURL library</b></a>. Please install/enable it before continuing.</p>
	<?php
		endif;
	?>

	<?php
		// warn if we are local
		if( $_SERVER["SERVER_NAME"] == "localhost" || $_SERVER["SERVER_NAME"] == "127.0.0.1" ):
	?>
		<p style='background-color:#F90;color:#FFFFFF;margin:1em 0;padding:0.8em;border:1px #F00 solid;'><strong>NOTE: </strong> HybridAuth will not work properly in localhost, as some social networks DO NOT TRUST localhost requests</p>
	<?php
		endif;
	?>

<form method="post"> 
	<h1 style="margin-bottom: 15px;">HybridAuth <?php echo $HYBRIDAUTH_VERSION; ?> Installer</h1> 
	<hr />

	<h4>Important notices</h4> 

	<ul style="list-style:disc inside;">
		<li style="color: #000000;font-size: 14px;">For security reason, please delete ("<b>install.php</b>") file as soon as you complete the installation process,</li>
		<li style="color: #000000;font-size: 14px;">Using the HybridAuth installer will erase your existing configuration file. If you already have an old installation of HybridAuth you might want to keep a copy of <b>config.php</b>,</li>
		<li style="color: #000000;font-size: 14px;">HybridAuth includes by default <?php echo count( $PROVIDERS_CONFIG ) + 1 ?> providers. If you want even more, please go to to HybridAuth web site and download the <a href="http://hybridauth.sourceforge.net/download.html">Additional Providers Package</a>.</li>
		<li style="color: #000000;font-size: 14px;">Visit the <a href="http://hybridauth.sourceforge.net/#installer">HybridAuth</a> home page to make sure if there is a newer version.</li>
	</ul> 
 
	<h4>HybridAuth Endpoint</h4> 
 
	
	<ul style="list-style:circle inside;">
		<li style="color: #000000;font-size: 14px;">HybridAuth endpoint url is where the index.php is located.</li>
		<li style="color: #000000;font-size: 14px;">HybridAuth enpoint should be set to <b>+rx mode</b> (read and execute permissions)</li>
	</ul>
	
	<div> 
		<div class="cfg">
		   <div class="cgfparams"> 
			  <ul>
				<li><label>HybridAuth Endpoint URL</label><input type="text" class="inputgnrc" value="<?php echo $GLOBAL_HYBRID_AUTH_URL_BASE; ?>" name="GLOBAL_HYBRID_AUTH_URL_BASE" style="min-width:600px;"></li>
			  </ul>
		   </div> 
		   <div class="cgftip" style="margin-left: 646px;padding: 20px;min-height: 60px;width: 300px;">
				Set the complete url to hybridauth core library on your website.  
				This URL will be used for many providers as the <a href="http://hybridauth.sourceforge.net/userguide/HybridAuth_endpoint_URL.html" target="_blank">Endpoint</a> for your website. 
		   </div>
		</div>   
	</div> 
	<br style="clear:both;"/> 
	<br />

	<h4>Providers setup</h4> 

	<ul style="list-style:circle inside;">
		<li style="color: #000000;font-size: 14px;">To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
		<li style="color: #000000;font-size: 14px;">If <b>Provider Adapter Satus</b> is set to <b style="color:red">Disabled</b> then users will not be able to login with this provider on you website.</li>
	</ul>

<?php
	$nb_provider = 0;
	
	foreach( $PROVIDERS_CONFIG AS $item ):
		$provider                   = @ $item["label"];
		$provider_name              = @ $item["provider_name"];
		$require_client_id          = @ $item["require_client_id"];
		$provider_new_app_link      = @ $item["new_app_link"];
		$provider_userguide_section = @ $item["userguide_section"];
		$provider_callback_url      = "" ;

		if( isset( $item["callback"] ) && $item["callback"] ){
			$provider_callback_url  = '<span style="color:green">' . $GLOBAL_HYBRID_AUTH_URL_BASE . '?hauth.done=' . $provider . '</span>';
		}

		$setupsteps = 0;
?>
	<h3 style="margin-left:30px;"><?php echo $provider_name ?></h3> 
	<div> 
		<div class="cfg">
		   <div class="cgfparams">
			  <ul>
				 <li><label><?php echo $provider_name ?> Adapter Satus</label>
					<select name="<?php echo strtoupper( $provider ) ?>_ADAPTER_STATUS">
						<option selected="selected" value="true">Enabled</option>
						<option value="false">Disabled</option>
					</select>
				</li>
				<?php if ( $provider_new_app_link ) : ?>
					<?php if ( $require_client_id ) : ?>
						<li><label>Application ID</label><input type="text" class="inputgnrc" value="" name="<?php echo strtoupper( $provider ) ?>_APPLICATION_APP_ID"    ></li>
					<?php else: ?>	
						<li><label>Application Key</label><input type="text" class="inputgnrc" value="" name="<?php echo strtoupper( $provider ) ?>_APPLICATION_KEY"    ></li>
					<?php endif; ?>	 
					<li><label>Application Secret</label><input type="text" class="inputgnrc" value="" name="<?php echo strtoupper( $provider ) ?>_APPLICATION_SECRET" ></li>
				<?php endif; ?>
			  </ul> 
		   </div>
		   <div class="cgftip">
				<?php if ( $provider_new_app_link  ) : ?> 
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Go to <a href="<?php echo $provider_new_app_link ?>" target ="_blanck"><?php echo $provider_new_app_link ?></a> and <b>create a new application</b>.</p>

					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Fill out any required fields such as the application name and description.</p>

					<?php if ( $provider == "Google" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</p>
					<?php endif; ?>	

					<?php if ( $provider_callback_url ) : ?>
						<p>
							<?php echo "<b>" . ++$setupsteps . "</b>." ?> Provide this URL as the Callback URL for your application:
							<br />
							<?php echo $provider_callback_url ?>
						</p>
					<?php endif; ?> 

					<?php if ( $provider == "MySpace" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider == "Live" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Redirect Domain</b> field. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider == "Facebook" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Site Url</b> field. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider == "LinkedIn" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Integration URL</b> field. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Application Type</b> to <em style="color:#CB4B16;">Web Application</em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider == "Yahoo" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Application URL</b> and <b>Application Domain</b> fields. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Kind of Application</b> to <em style="color:#CB4B16;">Web-based</em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider == "Twitter" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. It should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Default Access Type</b> to <em style="color:#CB4B16;">Read, Write, & Direct Messages</em>.</p> 
					<?php endif; ?>	
					
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Once you have registered, copy and past the created application credentials into this setup page.</p>  
				<?php else: ?>	
					<p>No registration required for OpenID based providers</p> 
				<?php endif; ?> 
		   </div>
		</div>   
	</div> 
	<br style="clear:both;"/> 
	<br />
<?php
	endforeach;
?>
	<br /> 
	<div style="text-align:center">
		Thanks for scrolling this far down! Now click the big button to complete the installation.
		<br />
		<br />
		<input type="submit" class="inputsave" value="Setup HybridAuth" /> 
	</div> 
</div>
</form>

 
</td>
</tr>
</table>

</div>

</body>
</html>
