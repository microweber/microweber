<?php

/*

type: layout

name:  layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left">
      <div class="howit_works_left_img"><img src="<? print TEMPLATE_URL ?>images/How_it_work_left_img.jpg" /></div>
      <mw module="content/pages_tree" />
      <div class="sponsored_tit">&nbsp;</div>
      <div class="sponsor_logo"><script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("http://money2study.co.uk/openads/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:2&amp;target=_blank");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script><noscript><a href='http://money2study.co.uk/openads/adclick.php?n=a15bc10b' target='_blank'><img src='http://money2study.co.uk/openads/adview.php?what=zone:2&amp;n=a15bc10b' border='0' alt=''></a></noscript></div>
    </div>
    <div class="inner_rt">
      <div class="page_tit"><? print $page['content_title'] ?></div>
      <!--      <div class="page_logo"><img src="<? print TEMPLATE_URL ?>images/page_logo.jpg" alt="money study" /></div>
-->
      <div class="howit_content">
        <editable rel="page" field="content_body"> <? print $page['content_body'] ?></editable>
      </div>
       <div class="inner_container_bot">
    <div class="fb-like" data-href="<? print url() ?>" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>
  </div>
    </div>
  </div>
 
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
