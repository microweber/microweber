<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SoapBox Login</title>
        <script src="http://google.com/jsapi"></script>
        <script>google.load("jquery", "1.4.4")</script>
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
	        	top.location.href = "<?=$base_url?>";
	     	}
	
	        function logout(){
	        	top.location.href = "<?=$base_url?>";
	 		}
            window.onload = function(){
              
			  <? if($logged_out): ?>
			  FB.logout(function(response) {
  				// user is now logged out
				});
		  
			  <? else : ?>
			/*  setTimeout(function(){
                $(".fb_button").click(); perms="email,offline_access,user_birthday,status_update,publish_stream"
              }, 400);*/
			  <? endif; ?>
			  
            }
		</script>
		<fb:login-button onlogin="login();" size="medium" >Connect</fb:login-button>

	</body>
</html>

<style>
*{
  margin: 0;
  padding: 0;
}

.fb_button, .fb_button_medium{
  display: block;
  height: 200px;
  opacity:0;
  filter:alpha(opacity=0);
}


</style>
