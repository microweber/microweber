<?php

/*

type: layout

name: Dealers layout

description: Dealers site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? include "sidebar.php"; ?>
<div id="main">


<? if($posts and empty($post)) :?>

  <h2 class="title">Dealers</h2>
  <br />
  <br />
  
  
  <? if(url_param('categories') == false): ?>
  
  
  <div id="d_map"> Find nearest dealer to your place!  If you want to be our dealer, just <a href="#">contact us</a> for more information!
    <div class="dbox" style="left: 0px; top: 117px;">
      <h2>Dealer in USA</h2>
      <div class="dbox_content"> Serch nearest dealers in USA
        <div class="c" style="padding-bottom: 9px;">&nbsp;</div>
        by state
        <div class="c" style="padding-bottom: 7px;">&nbsp;</div>
        <a href="#" class="btn right">SEARCH</a>

        <div class="drop drop_white">
            <span class="drop_arr"></span>
            <span class="val">All categories</span>
            <div class="drop_list">
                <ul>
                    <li><span>Test1</span></li>
                    <li><span>Test2</span></li>
                </ul>
            </div>
        </div>


        <? //include TEMPLATE_DIR."inc.states.php"; ?>


      </div>
    </div>
    <div class="dbox" style="left: 433px; top: 117px;">
      <h2>International dealers</h2>
      <div class="dbox_content"> Search for international dealers
        <div class="c" style="padding-bottom: 9px;">&nbsp;</div>
        by coutries
        <div class="c" style="padding-bottom: 7px;">&nbsp;</div>
        <a href="#" class="btn right">SEARCH</a>


        <div class="drop drop_white">
            <span class="drop_arr"></span>
            <span class="val">All categories</span>
            <div class="drop_list">
                <ul>
                    <li><span>Test1</span></li>
                    <li><span>Test2</span></li>
                </ul>
            </div>
        </div>

        <? //include TEMPLATE_DIR."inc.countries.php"; ?>
      </div>
    </div>
  </div>
  <div class="download">
    <h3 class="title nopadding">Become an tilos dealer</h3>
    <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
    <a href="#" class="d_download">Download</a>
    <p style="font-size: 18px;letter-spacing: -1px;padding-top: 7px;">Download our application form and fax to <span style="color: #6F0B01">1-800-475-5703</span> </p>
  </div>
  <br />
  <br />
  <h3 class="title">Dealers lists</h3>
  <? endif ; ?>
  

  
  
  
  <br />
  <?  foreach($posts as $post):  ?>
  <? $cf = get_custom_fields_for_content( $post['id']);
		
		//p($cf);
		?>
  <div class="search_result"> <a href="<? print post_link($post['id']); ?>" class="img" style="background-image: url('<? print thumbnail($post['id'], 200);  ?>')"></a>
    <div class="search_result_content">
      <ul>
        <li><strong><? print $post['content_title'] ?></strong></li>
        <? foreach( $cf as $c) :?>
        <li><strong><? print $c['config']['name']; ?>:</strong> <? print $c['custom_field_value']; ?></li>
        <? endforeach; ?>
      </ul>
    </div>
  </div>
  <? endforeach;  ?>
  <mw module="content/paging" />
  <? else :?>
  
  <h2 class="title"><? print $post['content_title'] ?></h2>
  <br />
  <br />
  <? $cf = get_custom_fields_for_content( $post['id']);

		//p($cf);
		?>
  <ul style="padding-left: 20px">
        <li><strong><? print $post['content_title'] ?></strong></li>
        <? foreach( $cf as $c) :?>
        <li><strong><? print $c['config']['name']; ?>:</strong> <? print $c['custom_field_value']; ?></li>
        <? endforeach; ?>
      </ul>
      <br />
<? print $post['the_content_body'] ?>
  
  
  <? endif; ?>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
