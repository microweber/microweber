</div>
<!-- /#content -->











<div class="footer_sub">
  <div class="content_center">
  <script type="text/javascript">
// <![CDATA[




			function CheckMultiple1(frm, name) {
				for (var i=0; i < frm.length; i++)
				{
					fldObj = frm.elements[i];
					fldId = fldObj.id;
					if (fldId) {
						var fieldnamecheck=fldObj.id.indexOf(name);
						if (fieldnamecheck != -1) {
							if (fldObj.checked) {
								return true;
							}
						}
					}
				}
				return false;
			}
			
			
			
		function subForm(fform) {
			var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
			f = eval("document."+fform);
	if(f.email.value.match(emailExp)){
		f.submit();
		//return true;
	}else{
		helperMsg = "Please enter valid email"
		alert(helperMsg);
		f.email.focus();
	//	return false;
	}
			}
		
// ]]>
</script>

    <table width="98%" border="0" cellspacing="2" cellpadding="2" height="67">
      <tr valign="middle">
        <td><img src="<? print TEMPLATE_URL ?>img/mail.png" height="32" /></td>
        <td><h2 class="sub_h">Subscribe for updates</h2></td>
        <td> <form id="sub_form" name="sub_form" method="post" action="http://newsletter.microweber.com/form.php?form=1"  >
   <input type="hidden" name="format" value="h" /><input type="text"  name="email" id="email"  class="sub_input" /></form></td>
        <td><a class="btn_small_dark" href="javascript:subForm('sub_form')">Subscribe</a></td>
        <td><h2 class="sub_h">Join us now</h2></td>
        <td><div class="footer__h"></div></td>
        <td></td>
        <td><div class="foot_share">
            
            
            <table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td> <div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js#appId=225342984166233&amp;xfbml=1"></script>
            <fb:like href="https://www.facebook.com/Microweber" send="true" layout="button_count" width="50" show_faces="true" font=""></fb:like>
            </td>
    <td>   <!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<!-- Place this tag where you want the +1 button to render -->
<g:plusone  href="http://microweber.com" size="small"></g:plusone></td>
  </tr>
</table>

            
           
            <!--<a href="http://www.facebook.com/pages/Microweber/155325201170631" target="_blank"><img src="http://microweber.com/NotifyMe%20one%20page%20HTML%20template_files/SocialMediaBookmarkIcon/16/facebook.png" alt="facebook" border="0" /></a>--> 
<br />
            <a href="http://twitter.com/Microweber" class="twitter-follow-button">Follow @Microweber</a>
            <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
          </div></td>
      </tr>
    </table>
    
  </div>
</div>
<div class="content_wide_holder_white" style="background-color:#FFF">
   
  <div class="content_center" style="background-color:#FFF">
    <table width="100%" border="0" cellspacing="10" cellpadding="10">
      <tr>
        <td><a href="<? print site_url(); ?>" title="Microweber"><img src="<? print TEMPLATE_URL ?>img/logo.png" height="27" /></a></td>
        <td><!-- LiveZilla Chat Button Link Code (ALWAYS PLACE IN BODY ELEMENT) --><a href="javascript:void(window.open('http://microweber.com/live_chat/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img src="http://microweber.com/live_chat/image.php?id=01&amp;type=inlay" width="120" height="30" border="0" alt="LiveZilla Live Help" /></a><!-- http://www.LiveZilla.net Chat Button Link Code --><!-- LiveZilla Tracking Code (ALWAYS PLACE IN BODY ELEMENT) --><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
/* <![CDATA[ */
var script = document.createElement("script");script.type="text/javascript";var src = "http://microweber.com/live_chat/server.php?request=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);
/* ]]> */
</script><noscript><img src="http://microweber.com/live_chat/server.php?request=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt="" /></noscript><!-- http://www.LiveZilla.net Tracking Code -->
 </td>
        <td> See also: <span class="grey_small"><a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank" title="Creative Commons Share-Alike">License</a>    |   <a href="<? print site_url('sub.php') ?>" title="Microweber"><strong>Subscribe for updates</strong></a></span>   |   <a href="<? print site_url('docs') ?>" title="Documentation"><strong>Documentation</strong></a></span></td>
        <td><span class="grey_small">&copy; All rights reserved 2010-<? print date("Y") ?> Microweber.com</span></td>
      </tr>
    </table>
  </div>
</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1065179-29']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- Start of Woopra Code -->
<script type="text/javascript">
var woo_settings = {idle_timeout:'300000', domain:'microweber.com'};
(function(){
var wsc = document.createElement('script');
wsc.src = document.location.protocol+'//static.woopra.com/js/woopra.js';
wsc.type = 'text/javascript';
wsc.async = true;
var ssc = document.getElementsByTagName('script')[0];
ssc.parentNode.insertBefore(wsc, ssc);
})();
</script>
<!-- End of Woopra Code -->
<!-- Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];

(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();

_qevents.push({
qacct:"p-ca5yPJsgkZUuo"
});
</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-ca5yPJsgkZUuo.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->
<!-- /#footer -->
</div>
<!-- /#container -->
</body></html>