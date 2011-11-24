<!--<div class="right">
  <div class="short">
    <h2 class="tvtitle tvblue">Профил</h2>
    <? //var_Dump($user_action ); ?>
    <ul>
      <? if(strval($user_session['is_logged'] ) == 'yes'):  ?>
      <li><a  <? if(strval($user_action ) == ''):  ?>  class="active"  <? endif; ?>      href="<? print site_url('users') ?>">Твоето съдържание</a>
      <li ><a <? if(strval($user_action ) == 'post'):  ?>  class="active"  <? endif; ?>   href="<? print site_url('users/user_action:post') ?>">Публикувай</a>
      <li  ><a <? if(strval($user_action ) == 'profile'):  ?>  class="active"  <? endif; ?> href="<? print site_url('users/user_action:profile') ?>">Настройки</a>
        <? else: ?>
      <li ><a  <? if(         strval($user_action ) == '' or strval($user_action ) == 'login'   )  :  ?>  class="active"  <? endif; ?>   href="<? print site_url('users/user_action:login') ?>">Вход</a>
      <li ><a <? if(strval($user_action ) == 'register'):  ?>  class="active"  <? endif; ?>    href="<? print site_url('users/user_action:register') ?>">Регистрация</a>
        <? endif; ?>
    </ul>
    <? //  include_once(ACTIVE_TEMPLATE_DIR.'sidebar_ads.php'); ?>
  </div>
</div>
-->