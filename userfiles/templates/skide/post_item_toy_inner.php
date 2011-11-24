<? $pics = post_pictures($post['id'], 500); ?>

<div id="main_side">
  <h2><?php print $post['content_title'] ?></h2>
  <br />
  <div id="toy_main_image">
    <? if($pics[0]): ?>
    <img src="<? print $pics[0] ?>" alt="<?php print addslashes($post['content_title']) ?>" />
    <? 
   unset( $pics[0]);
   endif; ?>
  </div>
  <div id="game_desc">
    <? if(!empty($pics)): ?>
    <? foreach($pics as $pic):  ?>
    <a href="<? print $pic ?>" class="image_thumb" style="background-image: url('<? print $pic ?>')"></a>
    <? endforeach; ?>
    <?  endif;  ?>
    <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
    <p><strong>Description:</strong> </p>
    <div class="richtext">
      <? //p($post); ?>
      <?php print $post['the_content_body'] ?></div>
  </div>
  <div class="c">&nbsp;</div>
  <br />
  <a  class="user_activity_likes"  href="<? print voting_link($post['id'], '#post-likes-'.$post['id']); ?>"> <strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id']); ?></strong> Like</a>
  <div class="c">&nbsp;</div>
  <br />
  <div class="hr">&nbsp;</div>
  <br />
  Price:&nbsp;<strong>$ <?php print $post["custom_fields"]['price'] ?></strong>
  <div class="bluebox right" style="width:300px;">
    <div class="blueboxcontent">
      <h3>Seller info</h3>
      <mw module="users/profile_box" user_id="<? print $post['created_by'] ?>">
    </div>
  </div>

  <? $seller = get_user($post['created_by']);
  //p($seller);
  ?>
  <? if($seller['custom_fields']['paypal']) : ?>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<? print $seller['custom_fields']['paypal'] ?>">
    <input type="hidden" name="amount" value="<?php print $post["custom_fields"]['price'] ?>">
    <input type="hidden" name="lc" value="US">
    <input type="hidden" name="item_name" value="Buy <?php print addslashes($post['content_title']) ?> from <?php print addslashes(user_name($seller['id'])) ?> - link <? print post_link($post['id']); ?>">
    <input type="hidden" name="item_number" value="<?php print addslashes($post['id']) ?>">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="0">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
  <? else :  ?>
  <a href="javascript:mw.users.UserMessage.compose(<?php echo $seller['id']; ?>);"  class="mw_btn_x"><span>Buy this toy</span></a> <br />
  <? endif; ?>
 <div class="c"></div><br />
  <?  $category = get_category($active_category); ?>
  <microweber module="comments/default" post_id="<? print $post['id']; ?>">
</div>
<!-- /#main_side -->
