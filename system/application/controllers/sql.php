<?php
class Sql extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
function dummy_posts() {
		
		header ( 'Content-type: text/plain; charset=utf-8' );
		$to_save = $_POST;
		$i = 0;
		
		for($i = 0; $i < 50; $i ++) {
			
		 	
			 
			//print $sentence . "\n\n";
			//print $vic . "\n\n";
			
			$to_save ['content_body'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis erat id erat rutrum euismod. Phasellus adipiscing sem quis augue tristique eu luctus enim condimentum. Curabitur nec sem id purus vulputate convallis. Nam adipiscing convallis augue ut porttitor. Nam eu nunc sagittis nibh volutpat porttitor. Sed euismod, eros eget tristique tempor, ante nisl venenatis orci, non euismod felis libero non nisi. Nulla auctor, lorem ut porta mollis, mauris metus adipiscing arcu, et posuere diam mauris non erat. Integer ut libero id orci rutrum elementum at nec lorem. In hac habitasse platea dictumst. Nullam porttitor augue nec augue vestibulum in convallis arcu dignissim. Nunc dapibus vehicula enim eget ornare. Mauris commodo aliquam felis, sed varius diam posuere at. Sed mattis iaculis mauris, eu adipiscing ligula facilisis ut. Pellentesque sit amet nibh nec velit convallis dictum consectetur id justo. Quisque eleifend convallis eros, mattis varius felis adipiscing non. Mauris in leo eget sem faucibus posuere a sit amet urna. Sed vel purus semper augue consequat imperdiet. In luctus porttitor ante ut iaculis.

Proin eget risus erat, a facilisis magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas eu diam sed nisi semper adipiscing. Pellentesque ut nisl purus. Etiam nec placerat leo. Mauris posuere accumsan ultricies. Suspendisse potenti. Aenean eget orci et leo viverra fermentum laoreet eget augue. Nulla facilisi. In iaculis adipiscing hendrerit. Nulla in enim diam. Pellentesque tempus, eros eget convallis egestas, nulla magna iaculis elit, eget ullamcorper orci massa nec lectus. Aliquam erat volutpat.

Ut sit amet odio ut urna dignissim posuere eu sit amet nisl. Aenean enim ante, tincidunt nec tincidunt in, pharetra quis ipsum. Maecenas sed leo odio. Ut elementum pretium purus at sodales. Etiam iaculis commodo purus a vehicula. Proin eu turpis nec dui tempus porttitor. Sed rhoncus ante tincidunt libero vulputate sit amet pellentesque arcu commodo. Quisque non condimentum quam. Proin diam ipsum, consequat vel ullamcorper ac, aliquet tristique ligula. Integer consequat malesuada lacinia. Nulla porttitor malesuada metus, ac tristique arcu consequat at.

Cras dui nisi, mollis ac lacinia sed, adipiscing quis massa. Proin ac feugiat sem. Maecenas consectetur ultricies purus, id tristique mauris suscipit lacinia. Mauris sit amet velit odio. Praesent a est enim, at varius enim. Pellentesque et mi ut nulla varius sollicitudin. In hac habitasse platea dictumst. Phasellus enim massa, pretium vitae ullamcorper pellentesque, convallis vel enim. Phasellus posuere aliquam ante, consectetur interdum tellus iaculis eu. Maecenas pretium, purus ut faucibus lobortis, nisl orci fringilla dui, eu ullamcorper orci nisl dictum eros. Nunc accumsan turpis nec felis consectetur hendrerit. Proin metus elit, ullamcorper sed gravida vel, tempor vel massa. Quisque rhoncus, lectus in facilisis mattis, nisl nisl condimentum metus, eget facilisis lacus risus et sapien. In quis felis ac velit pharetra condimentum vel a neque. Vivamus ut venenatis libero. Nullam at ipsum id nunc posuere mattis ac at libero. Etiam placerat diam non neque tempus vitae molestie justo posuere. Donec volutpat, purus at sollicitudin sagittis, purus nisi eleifend lacus, sit amet ultricies diam sapien nec ante. Donec et eros turpis. Vestibulum pharetra suscipit scelerisque.

Fusce in luctus orci. Aliquam non tellus id lorem ultricies elementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.';
			
			
			global $cms_db_tables;
		$table = $cms_db_tables ['table_taxonomy'];
		$q = " select id from $table where taxonomy_type = 'category'     order by RAND() limit 0,1   ";
		$q = $this->core_model->dbQuery($q);
		
		$to_save ['content_title'] = 'random post in '. $q[0]['taxonomy_value'] . ' on '.date("Y-m-d H:i:s");
			
			$errors = array ();
			$categories = array ($q[0]['id'] );
			
			if (! empty ( $categories )) {
				
				foreach ( $categories as $cat ) {
					$parrent_cats = $this->content_model->taxonomyGetParentItemsAndReturnOnlyIds ( $cat );
					
					foreach ( $parrent_cats as $par_cat ) {
						$categories [] = $par_cat;
					}
				
				}
				$categories = array_unique ( $categories );
			}
			
			$category = $categories [count ( $categories ) - 1];
			if (! empty ( $categories )) {
				$i = 0;
				foreach ( $categories as $cat ) {
					if (! empty ( $categories_ids_to_remove )) {
						if (in_array ( $cat, $categories_ids_to_remove ) == true) {
							unset ( $categories [$i] );
						}
					}
					$i ++;
				}
			}
			
			if ((! empty ( $categories_ids_to_remove )) and (in_array ( $category, $categories_ids_to_remove ) == true)) {
				exit ( 'WOW invalid category! How this can be?' );
				//error
			} else {
				$check_title = array ();
				if (trim ( strval ( $to_save ['content_title'] ) ) == '') {
					$errors ['content_title'] = "Please enter title";
				}
				
				$check_title ['content_title'] = $to_save ['content_title'];
				$check_title ['content_type'] = 'post';
				$check_title = $this->content_model->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
				if (! empty ( $check_title )) {
					$check_title_error;
				}
				
				if ($check_title_error == true) {
					print 'title exist' . $to_save ['content_title'];
					//errror
				} else {
					
					$taxonomy_categories = array ($category );
					$taxonomy = $this->content_model->taxonomyGetParentItemsAndReturnOnlyIds ( $category );
					if (! empty ( $taxonomy )) {
						foreach ( $taxonomy as $i ) {
							$taxonomy_categories [] = $i;
						}
					}
					
					$to_save ['content_type'] = 'post';
					
					if (empty ( $categories )) {
						$errors ['taxonomy_categories'] = "Please choose at least one category";
					}
					$categories = array_reverse ( $categories );
					$to_save ['taxonomy_categories'] = $categories;
					
					$parent_page = false;
					
					foreach ( $categories as $cat ) {
						if (empty ( $parent_page )) {
							$parent_page = $this->content_model->contentsGetTheLastBlogSectionForCategory ( $cat );
						}
					
					}
					
					if (! empty ( $categories )) {
						if (empty ( $parent_page )) {
						}
					}
					if (empty ( $errors )) {
						$to_save ['content_parent'] = $parent_page ['id'];
						$to_save ['is_home'] = 'n';
						$to_save ['content_type'] = 'post';
						var_dump ( $to_save );
						$saved = $this->content_model->saveContent ( $to_save );
 
					} else {
						var_dump ( $errors );
					}
				
				}
			
			}
		
		}
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		/*
		$this->load->vars ( $this->template );
		$layout = $this->load->view ( 'layout', true, true );
		$primarycontent = $this->load->view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$this->output->set_output ( $layout );
		*/
		
		
		$sql =<<<STR
	select u.id, u.username from firecms_users u
    	
STR;
		$users = $this->core_model->dbQuery($sql);
		
		for($i=0; $i<count($users);$i++){
			$sql_update = "UPDATE affiliate_users set parent_affil='{$users[$i]['username']}' WHERE parent_affil_id={$users[$i]['id']} ";
			$this->core_model->dbQ($sql_update);
		}
		
				$sql =<<<STR
	select u.id, u.parent_affil_id,u.parent_affil from affiliate_users u
    	
STR;
		$users = $this->core_model->dbQuery($sql);
	p($users);	
		
die;




		$sql =<<<STR
	select c1.username, c1.tier from cosmic_affiliates c1
    	
STR;
		$tiers = $this->core_model->dbQuery($sql);
$ids = array();
	for($i=0; $i < count($tiers);$i++){
		$res = array();
			$uname = htmlentities($tiers[$i]['tier'],ENT_QUOTES);
			$sql_update= "select id from firecms_users WHERE username='$uname' ";
			
			$res = $this->core_model->dbQuery($sql_update);
			
			if(!empty($res))
				$ids[$tiers[$i]['username']] = $res[0]['id'];
			
		}
		
	
$sql_update='';
		foreach($ids as $kol => $val){
			$uname = htmlentities($kol,ENT_QUOTES);
			$sql_update= "UPDATE firecms_users set parent_affil='{$val}' WHERE username='$uname' ";
			
			$this->core_model->dbQ($sql_update);
			
		}
		$sql = 'select id,username, parent_affil from firecms_users';
$res = $this->core_model->dbQuery($sql);
p($res);
	}
}

?>