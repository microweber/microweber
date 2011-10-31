<!-- footer -->

<div id="footer_container">
  <div id="footer">
    <!-- footer nav -->
    <div id="nav-footer">
      <? $nav = CI::model ( 'content' )->getMenuItemsByMenuName('main_menu');
		
		//p($nav);
		?>
      <ul>
        <? foreach($nav as $n): ?>
        <li <? if($n["is_active"] == true) : ?>  class="active"  <? endif; ?> ><a href="<? print ucwords($n["url"]); ?>"><span><? print ucwords($n["title"]); ?></span></a></li>
        <? endforeach; ?>
        <!--        <li ><a href="index-2.html"><span>Home</span></a></li>
        <li><a href="portfolio.html"><span>Link Building Services</span></a></li>
        <li><a href="services.html"><span>SEO Blogs</span></a></li>
        <li><a href="contact.html"><span>Ranking Examples</span></a></li>
        <li><a href="contact.html"><span>Why Choose Us</span></a></li>
         <li><a href="contact.html"><span>Contact</span></a></li>-->
      </ul>
    </div>
    <!-- /footer nav -->
    <!-- copyright -->
    <!-- <br />

    Copyright &copy;<? print date("Y") ?>, <a href="<? print site_url(); ?>">Global Seos</a>. Website by  <a href="http://ooyes.net" target="_blank" title="web design company">ooYes.net</a>  -->
  </div>
</div>
</body></html>