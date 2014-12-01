
<?php
    if(is_logged() == true){
        return mw()->url_manager->redirect(site_url().'profile/#section=profile');
    }
?>

<script>
    $(document).ready(function(){
      EqualHeight('.sign-in .item-box');
    });
    $(window).load(function(){
      EqualHeight('.sign-in .item-box');
    });
</script>
<div class="mw-wrapper">




    <?php
        # Login Providers
        $facebook = get_option('enable_user_fb_registration','users') =='y';
        $twitter = get_option('enable_user_twitter_registration','users') =='y';
        $google = get_option('enable_user_google_registration','users') =='y';
        $windows = get_option('enable_user_windows_live_registration','users') =='y';
        $github = get_option('enable_user_github_registration','users') =='y';

        if($facebook or $twitter or $google or $windows or $github){
           $have_social_login = true;
        }
        else{
          $have_social_login = false;
        }
    ?>

    <?php if($have_social_login){ ?>
             <h3 class="pad2 text-center" style="font-weight: 400">SOCIAL LOGIN WITH</h3>
            <div class="pad social-login-top">
    <?php } ?>

    <?php if($have_social_login){ ?><ul class="mw-ui-inline-list"><?php } ?>
        <?php if($facebook): ?>
        <li class="tip" data-tip="Facebook" data-tipposition="top-center"><a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-icon-facebook"></a></li>
        <?php endif; ?>
        <?php if($twitter): ?>
        <li class="tip" data-tip="Twitter" data-tipposition="top-center"><a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-icon-twitter"></a></li>
        <?php endif; ?>
        <?php if($google): ?>
        <li class="tip" data-tip="Google" data-tipposition="top-center"><a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-icon-googleplus"></a></li>
        <?php endif; ?>
        <?php if($windows): ?>
        <li class="tip" data-tip="Windows Live" data-tipposition="top-center"><a href="<?php print api_link('user_social_login?provider=live') ?>" class="mw-icon-social-windows"></a></li>
        <?php endif; ?>
        <?php if($github): ?>
        <li class="tip" data-tip="Github" data-tipposition="top-center"><a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-icon-social-github"></a></li>
        <?php endif; ?>
    <?php if($have_social_login){ ?></ul></div> <?php } ?>




    <div class="mw-ui-row mw-ui-row-drop-on-1024 sign-in">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container"><div class="item-box"><module type="users/login" /></div></div>
        </div>
        <div class="mw-ui-col">
             <div class="mw-ui-col-container"><div class="item-box"><module type="users/register" /></div></div>
        </div>
    </div>
</div>