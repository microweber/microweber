<?php

/*

type: layout

name: inner pages layout

description: inner pages site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="wrapMiddle">
  <div class="wrapContent">
    <div class="contentLeft">
      <!--left box 1-->
      <div class="Box">
        <!--TreeMenu-->
        <div class="menuTreeHolder">
          <ul class="menuTree">
            <li class="title">
              <label><? $mpage = get_page(MAIN_PAGE_ID); 
			//  p($mpage);
			  ?><?  print $mpage['content_title'] ?></label>
            </li>
            <?
			  if(intval( $from) == 0){
	$par =  CI::model('content')->getParentPagesIdsForPageIdAndCache($page['id']);
$last =  end($par); // last

if($last == 0){
$from = 	$page['id'];
} else {
	$from = 	$last;
}

}
			  
		
		
		 CI::model('content')->content_helpers_getPagesAsUlTree($from , "<a href='{link}' class='panel {active_code}'  {removed_ids_code}    value='{id}' ><span class='leftCorner'></span><span class='rightCorner'></span>{content_title}</a></a>", array(PAGE_ID), 'selected', array($page['id']) , 'hidden' , false, false, $params['ul_class'],1 );
		?>
            <!--   <li><a href=".item1" title="" class="panel selected"><span class="leftCorner"></span><span class="rightCorner"></span>Why you need a Will?</a></li>
              <li><a href=".item2" title="" class="panel "><span class="leftCorner"></span><span class="rightCorner"></span>Rules of Intestacy</a></li>
              <li><a href=".item3" title="" class="panel "><span class="leftCorner"></span><span class="rightCorner"></span>Forced Heirship</a></li>
              <li><a href=".item4" title="" class="panel "><span class="leftCorner"></span><span class="rightCorner"></span>Inheritance Tax Planning</a></li>
              <li><a href=".item5" title="" class="panel"><span class="leftCorner"></span><span class="rightCorner"></span>Why Should I assign a Power of Attorney?</a></li>-->
          </ul>
        </div>
        <!--end TreeMenu-->
        <div class="paragraph">If you have any problems with the filing the form plase contact us in our Live chat agent online.</div>
        <div class="leftForm">
          <form action="?">
            <div class="title">Type your details for FREE consultation</div>
            <label>Please write us an e-mail on the form below.  Thank you!</label>
            <span class="inputHolder">
            <input type="text" value = "Your Name" onclick="value=''" onblur="this.value=!this.value?'Your Name':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Your E-mail" onclick="value=''" onblur="this.value=!this.value?'Your E-mail':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Phone Number" onclick="value=''" onblur="this.value=!this.value?'Phone Number':this.value;" name="" />
            </span> <span class="textarea">
            <textarea></textarea>
            </span> <span class="note">* Your personal information is strictly confidential</span>
            <div class="clear"></div>
            <a href="#" title="" class="bttnHolder"> <span class="bttn">Read More</span> </a>
            <div class="clear"></div>
          </form>
        </div>
      </div>
      <!--end left box 1-->
    </div>
    <div class="contentMain">
      <div id="wrapper">
        <div class="item1">
          <div class="content">
            <div class="prices">
              <h1> <? print $page['content_title'] ?></h1>
              <editable  rel="page" field="content_body">
              <? print $page['the_content_body'] ?>
              </editable>
            </div>
          </div>
        </div>
        <div class="item2">
          <div class="content"> </div>
        </div>
        <div class="item3">
          <div class="content"> </div>
        </div>
        <div class="item4">
          <div class="content"> </div>
        </div>
        <div class="item5">
          <div class="content"> </div>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
