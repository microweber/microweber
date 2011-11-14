 <div class="clear"></div>
<div class="wrapBottom">
  <div class="wrapContent">
    <div class="bottomDivider"></div>
    <div class="bottomContainer">
      <div class="leftHolder">
        <div class="bottomLogo">We are part of the society of will writers</div>
      </div>
      <div class="rightHolder"> <span class="span">Become our friend</span> <span class="facebook"></span> <span class="twitter"></span> <span class="youtube"></span> </div>
      <div class="clear"></div>
    </div>
    <div class="Footer"> <a href = "#" title="" class="logoBottom"><img src="<? print TEMPLATE_URL ?>images/logoBottom.png" alt="" /></a>
      <div class="menuBottom">
       <ul>
            <? $i=0; foreach($header_menu as $item): ?>
            <li><a class="<? if($item['is_active'] == true and $i > 0): ?> active<? endif; ?><? if($item['is_active'] == true and $i == 0): ?> <? endif; ?><? if($i == 0): ?> <? endif; ?>" href="<? print  $item['the_url']; ?>" title="<? print  $item['title']; ?>">
              
              <? print  $item['title']; ?>
          
              </a></li>
            <? $i++; endforeach; ?>
          </ul>
          
          
        
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div><div class="wrapBottom">
    <div class="finisher"> <span style="float:left">© All rights reserved 2002-2011 <a href="" title="">Global Wills.com</a> .</span> <span style="float:right">Powered by Microweber Webdesign by <a  href="http://ooyes.net" target="_blank" title="">ooYes!</a></span>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
 
</div>
  