<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SoapBox Login</title>
    </head>
    <body>
    	<!-- FB CODE -->
		<div id="fb-root"></div>
		<script type="text/javascript">
			window.fbAsyncInit = function() {
	        	FB.init({appId: '<?=$appkey?>', status: true, cookie: true, xfbml: true});
	 
	            /* All the events registered */
	            FB.Event.subscribe('auth.login', function(response) {
	    			// do something with response
	                login();
	        	});
	
	            FB.Event.subscribe('auth.logout', function(response) {
	            // do something with response
	                logout();
	          	});
	   		};
	
	        (function() {
		        var e = document.createElement('script');
	            e.type = 'text/javascript';
	            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		          e.async = true;
	            document.getElementById('fb-root').appendChild(e);
	   	 	}());
	 
	        function login(){
	        	document.location.href = "<?=$base_url?>";
	     	}
	
	        function logout(){
	        	document.location.href = "<?=$base_url?>";
	 		}
		</script>
		<!-- END OF FB CODE -->
		<?  CI::helper('form'); ?>
    	<form action="<?php echo site_url('login/validate_user/') . '/' . $uri_var;?>" method="post">
		<p class="login-p">username:<br /><?= form_input('user_id', '') ?></p>
		<p class="login-p">password:<br /><?= form_password('pwd', '') ?></p>
		
		<p id="buttons">
			<fb:login-button onlogin="login();" size="medium" perms="email,offline_access,user_birthday,status_update,publish_stream">Connect</fb:login-button>
			
			<?= form_submit('submit', 'Login') ?>
		</p>
		</form>
	</body>
</html>
