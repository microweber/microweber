</div>
<!-- /front content -->

<div id="footer">
  <ul id="footer-nav">
     <?	$menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu_front'); ?>
    <? foreach($menu_items as $item): ?>

    <li><a   <? if($item['content_id'] == $page['id']): ?>  class="active"  <? endif; ?>    href="<? print $item['the_url'] ?>"><? print ucfirst( $item['item_title'] ) ?></a></li>
    <? endforeach ;  ?>
  </ul>
  <address>
   <?	$menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('footer_menu_small'); ?>
    <? foreach($menu_items as $item): ?>
    <a   <? if($item['content_id'] == $page['id']): ?>  class="active"  <? endif; ?>    href="<? print $item['the_url'] ?>"><? print ucfirst( $item['item_title'] ) ?></a>
    <? endforeach ;  ?>
 
<!--  <a href="#">Copyright</a><a href="#">Terms of use</a><a href="#">Privacy</a>-->
 
  </address>
</div>
<div id="login">
  <form method="post" action="<? print site_url('users/user_action:login') ?>">
    <label>User Name</label>
    <span class="login-input">
    <input type="text" name="username" />
    </span>
    <label>Password</label>
    <span class="login-input">
    <input type="password" name="password" />
    </span>
    <input type="submit" value="Login" class="abshidden" />
    <a href="#" class="submit">Login</a> <a class="login-lost-pass" href="<? print site_url('users/user_action:forgotten_pass ') ?>">Forgot your password?</a>
  </form>
</div>
</div>
<!-- /#front-wrapper -->
</div>
<!-- /#front-container --> 

<span id="title-tip" style="left: 0px;top: 0px;"> <span id="ttl">&nbsp;</span> <span id="ttm"><span id="ttm-content">&nbsp;</span></span> <span id="ttr">&nbsp;</span> </span>
</body></html>