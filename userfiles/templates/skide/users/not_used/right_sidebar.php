<!--<div class="right">
  <div class="short">
    <h2 class="tvtitle tvblue">Профил</h2>
    <?php //var_Dump($user_action ); ?>
    <ul>
      <?php if(strval($user_session['is_logged'] ) == 'yes'):  ?>
      <li><a  <?php if(strval($user_action ) == ''):  ?>  class="active"  <?php endif; ?>      href="<?php print site_url('users') ?>">Твоето съдържание</a>
      <li ><a <?php if(strval($user_action ) == 'post'):  ?>  class="active"  <?php endif; ?>   href="<?php print site_url('users/user_action:post') ?>">Публикувай</a>
      <li  ><a <?php if(strval($user_action ) == 'profile'):  ?>  class="active"  <?php endif; ?> href="<?php print site_url('users/user_action:profile') ?>">Настройки</a>
        <?php else: ?>
      <li ><a  <?php if(         strval($user_action ) == '' or strval($user_action ) == 'login'   )  :  ?>  class="active"  <?php endif; ?>   href="<?php print site_url('users/user_action:login') ?>">Вход</a>
      <li ><a <?php if(strval($user_action ) == 'register'):  ?>  class="active"  <?php endif; ?>    href="<?php print site_url('users/user_action:register') ?>">Регистрация</a>
        <?php endif; ?>
    </ul>
    <?php //  include_once(ACTIVE_TEMPLATE_DIR.'sidebar_ads.php'); ?>
  </div>
</div>
-->