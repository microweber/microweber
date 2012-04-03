<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
</div>
<!-- #main -->

<div id="footer" >
  <div id="footer_nav">
    <div class="wrapper">
      <div id="soc"> <span>Socialize</span> <a href="https://www.facebook.com/pages/Digital-Connections-Inc/247969741895199" target="_blank" id="fb"></a> <a href="http://twitter.com/#!/Digital__TV" target="_blank" id="tw"></a> </div>
     
      <ul>
      
        
        <mw module="content/pages_tree" from="4805" />
        <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1065179-51']);
  _gaq.push(['_setDomainName', '.digital-connections.tv']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
       
        <?php /*
          <li><a href="#">Home</a></li>
          <li><a href="#">Installation</a></li>
          <li><a href="#">Coverage</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Contact Us</a></li>
          */ ?>
        <?php //wp_list_pages('child_of=15&sort_column=menu_order&title_li='); ?>
        <li><a href="http://www.probusinesstools.com/websites/digitalconn/index.asp" target="_blank">Partner login</a></li>
      </ul>
      <div class="c" style="height: 15px;">&nbsp;</div>
      <address>
      All Rights Reserved www.Digital-Connections.tv <?php echo date('Y'); ?>
      </address>
      <div id="i"><a href="http://ooyes.net" target="_blank" id="Website Design">Design</a> by <a id="Web Design Company" target="_blank" href="http://ooyes.net">ooYes</a>! | <a href="http://microweber.com/" target="_blank" title="CMS">CMS</a> by <a  target="_blank" href="http://microweber.com/">Microweber</a></div>
      <div class="c" style="height: 25px;">&nbsp;</div>
      <? 

$cities =  TEMPLATE_DIR. "cities.txt";

$data = file_get_contents($cities ); //read the file
$convert = explode("\n", $data ); //create array separate by new line

 

?>
      <strong>Proudly serving in those areas: </strong>
      <? foreach($convert as $city): ?>
      <a class="text" href="<?php echo page_link(3482); ?>/city:<? print $city ?>"><? print ucwords(strtolower($city)) ?></a>,
      <? endforeach; ?>
      <div class="c" style="height: 25px;">&nbsp;</div>
    </div>
  </div>
  <?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	//get_sidebar( 'footer' );
?>
</div>
<!-- #footer -->
</div>
<!-- #wrapper -->
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	//.wp_footer();
?>







<div id="goto_checkout" style="display:none">
  <div class="manitext">
     <h3 class="continue-shopping-after-add-title">You can now complete your order</h3>
    
    <br />
<br />

    <div style="float:left"> <a href="javascript:close_model()" class="continue-shopping-after-add">Continue shopping</a></div>
    
     <div style="float:right"> <a href="<? print site_url('shop'); ?>/view:cart"><img src="<? print TEMPLATE_URL ?>images/complete_the_order_but.jpg" /></a></div>
    <br />
 
  

  </div>
</div>












</body></html>