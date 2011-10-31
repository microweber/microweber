<div id="footer">
    <div class="wrap"> <span id="footerlogo">&nbsp;</span>
     <? $nav = CI::model ( 'content' )->getMenuItemsByMenuName('main_menu');
		
		//p($nav);
		?>
      <ul>
        <? foreach($nav as $n): ?>
        <li <? if($n["is_active"] == true) : ?>  class="active"  <? endif; ?> ><a class="<? if($n["is_active"] == true) : ?> active<? endif; ?>" href="<? print ucwords($n["url"]); ?>"><? print ucwords($n["title"]); ?></a></li>
        <? endforeach; ?>
     
      </ul>
      
     
      <address>
      Всички права запазени <? print date("Y"); ?> &copy; Димитър Божанов. <a href="http://ooyes.net" title="Уеб дизайн" class="color">Уеб дизайн</a> - <a class="color" href="http://ooyes.net" title="Уеб дизайн">ooyes.net</a>&nbsp;
      Powered by:&nbsp; <a class="color" href="http://microweber.com" title="CMS">Microweber</a>
      </address>
      <div id="order-bottom"> <span id="bottombook">&nbsp;</span>
        <h2 class="titlesm right" style="width:220px;">Познание  </h2>
        <div style="width: 220px" class="right">
          <h2 class="titlesm left" style="width: 280px">на една ръка</h2>
            <h2 class="titlesm left" style="width: 280px">разстояние</h2>
       
        <div id="footerbtn"> <span id="footerbtnitem">&nbsp;</span> <a href="<? print page_link_to_layout('shop') ?>" title="Поръчай он-лайн">Поръчай он-лайн</a> </div>
        <a href="<? print page_link_to_layout('shop') ?>" id="order-big">&nbsp;</a> </div>
    </div>
  </div>
</div>
<!-- /#container -->
<script type="text/javascript" src="js/before.ready.js"></script>
<div id="overlay" onclick="zoomboxClose()">&nbsp;</div>
<div id="zoombox" style="top: 0px;left: 0px;">&nbsp;</div>
<span id="loading"><span id="loading-img">&nbsp;</span></span>
<!-- NACHALO NA TYXO.BG BROYACH -->
<script  type="text/javascript">
<!--
d=document;
d.write('<a href="http://www.tyxo.bg/?103081" title="Tyxo.bg counter" target=" blank"><img width="1" height="1" border="0" alt="Tyxo.bg counter"');
d.write(' src="http://cnt.tyxo.bg/103081?rnd='+Math.round(Math.random()*2147483647));
d.write('&sp='+screen.width+'x'+screen.height+'&r='+escape(d.referrer)+'" /><\/a>');
//-->
</script>
<noscript>
<a href="http://www.tyxo.bg/?103081" title="Tyxo.bg counter" target="_blank"><img src="http://cnt.tyxo.bg/103081" width="1" height="1" border="0" alt="Tyxo.bg counter" /></a>
</noscript>
<!-- KRAI NA TYXO.BG BROYACH -->
</body></html> 