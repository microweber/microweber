<br />
<br />
</div>
<!-- /.richtext -->
</div>
<!-- /#content -->
<script type="text/javascript">

// prepare the form when the DOM is ready 

/*$(document).ready(function() { 

    var options = { 

      //  target:        '#output1',   // target element(s) to be updated with server response 

        beforeSubmit:  subscibe_formshowRequest,  // pre-submit callback 

        success:       subscibe_formshowResponse  , // post-submit callback 

 

        // other available options:   

        url:       'http://omnitom.com/s.php'    ,     // override for form's 'action' attribute 

        type:      'get'   ,      // 'get' or 'post', override for form's 'method' attribute 

        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 

        clearForm: true        // clear all form fields after successful submit 

        //resetForm: true        // reset the form after successful submit 

 

        // $.ajax options can be used here too, for example: 

        //timeout:   3000 

    };

 



  $('#subscibe_form').submit(function() { 

        // inside event callbacks 'this' is the DOM element so we first 

        // wrap it in a jQuery object and then invoke ajaxSubmit 

        $(this).ajaxSubmit(options); 

 

        // !!! Important !!! 

        // always return false to prevent standard browser submit and page navigation 

        return false; 

    }); 



     

}); 

 

// pre-submit callback 

function subscibe_formshowRequest(formData, jqForm, options) { 

    // formData is an array; here we use $.param to convert it to a string to display it 

    // but the form plugin does this for you automatically when it submits the data 

    var queryString = $.param(formData); 

 

    // jqForm is a jQuery object encapsulating the form element.  To access the 

    // DOM element for the form do this: 

    // var formElement = jqForm[0]; 

 

    //alert('About to submit: \n\n' + queryString); 

 

    // here we could return false to prevent the form from being submitted; 

    // returning anything other than false will allow the form submit to continue 

    return true; 

} 

 

// post-submit callback 

function subscibe_formshowResponse(responseText, statusText)  { 

alert('Thank you for your subscription!');

} 





*/

function newsletter_sub(){

	

$('#subscibe_form').submit();	

}

</script>
<div id="footer" class="wrap">
  <div id="subscribe" class="wrap">
    <p><strong><strong>JOIN NOW</strong> The Omnitom Newsletter</strong></p>
    <?php /*

    <form method="get" action="http://omnitom.com/s.php" id="subscibe_form" target="_blank">



      <div class="subscibe_form_input">

        <input type="text" value="Your email here" name="email" class="blurfocus bi" />

      </div>

      <a href="javascript:newsletter_sub()" class="subscibe_form_submit">SEND</a>

    </form>

     */ ?>
    <form method="post" action="http://omnitom.us1.list-manage.com/subscribe/post?u=3d490ad0ba00c4be3312bf8c4&amp;id=b2a1c44d4c" id="subscibe_form" name="mc-embedded-subscribe-form" target="_blank">
      <div class="subscibe_form_input">
        <input type="text" value="Your email here" name="EMAIL" class="blurfocus bi" />
      </div>
      <a href="javascript:newsletter_sub()" class="subscibe_form_submit">SEND</a>
    </form>
    <p><strong class="frss"><a href="<?php print site_url('main/rss'); ?>">Get RSS feed from here</a></strong></p>
  </div>
  <div class="flist">
    <h2>OMNITOM</h2>
    <ul>
      <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_menu');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
  </div>
  <div class="flist">
    <h2>ONLINE BOUTIQUE</h2>
    <ul>
      <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('on_line_shop_menu');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
  </div>
  <div class="flist">
    <h2><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(194); ?>">COLLECTIONS</a>
      <!--VIEW LATEST-->
       </h2>

      <? $colls = CI::model ( 'taxonomy' )->getChildrensRecursive(2060, 'category');
	  
	//  p($colls);
	  ?>
      
    <ul>
     <?php foreach($colls as $item):	 $item1 = CI::model ( 'taxonomy' )->getSingleItem($item);
	 ?>
      <?php if($item != 2060): ?>
        <li><a  name="<?php print addslashes( $item1['taxonomy_value'] ) ?>" href="<?php print CI::model ( 'taxonomy' )->getUrlForIdAndCache($item) ?>"><?php print ( $item1['taxonomy_value'] ) ?></a></li>
        <?php endif; ?>
      <?php endforeach ;  ?>
    
    </ul>
  </div>

  <div class="flist">
    <h2>OMNITOM WORLD</h2>
    <ul>
      <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_world');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
      <!--        <li><a href="#">Charity</a></li>

                    <li><a href="#">Friends</a></li>

                    <li><a href="#">Upload picture</a></li>

                    <li><a href="#">Yoga off the mat</a>

                        <ul>

                          

                            <li><a href="#">Articles</a></li>

                            <li><a href="#">Quotes</a></li>

                            <li><a href="#">Enviormental tips</a></li>-->
    </ul>
    </li>
    </ul>
  </div>
  <!--  <div class="flist">

    <h2>OMNITOM SALE</h2>

    <?php $link = false;

$link = CI::model ( 'content' )->getContentURLById(38).'/category:{taxonomy_value}' ;

$active = '  class="active"   ' ;

$actve_ids = $active_categories;

if( empty($actve_ids ) == true){

$actve_ids = array($page['content_subtype_value']);

}

//CI::model ( 'content' )->content_helpers_getCaregoriesUlTree(87, "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false); ?>

  </div>



-->
  <div class="flist">
    <h2>GET IN TOUCH</h2>
    <ul>
      <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('get_in_touch');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
  </div>
  <div class="clear">
    <!--  -->
  </div>
  <address>
  &copy; All rights reserved <?php print date('Y'); ?> OMNITOM
  </address>
  <div class="clear">
    <!--  -->
  </div>
  <div id="web-design-company"> <a href="http://ooyes.net" title="web design company">Website Design</a> by <a href="http://ooyes.net" title="web design company">OoYes.net</a> <a title="Web Design Company" href="http://ooyes.net">OOYES.NET</a> | Powered by <a href="http://microweber.com/" title="CMS">Microweber</a> </div>
</div>
<!-- /#footer -->
</div>
<!-- /#wrapper -->
</div>
<!-- /#container -->
<div id="overlay" onclick="close_modal();">
  <!--  -->
</div>
<div id="modal"> </div>
<?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>
<div id="TheOverlay">
  <!--  -->
  <div id="preloader" style="display: none; top: 0px; left: 0px;"><span>&nbsp;</span></div>
</div>
</body></html>