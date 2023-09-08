<div class="mw-user-reg-holder">

    <?php $form_btn_title = get_option('form_btn_title', $params['id']);
    if ($form_btn_title == false) {
        $form_btn_title = 'Register';
    }


    $enable_user_fb_registration = get_option('enable_user_fb_registration', $params['id']);
    if ($enable_user_fb_registration == 'y') {
        $enable_user_fb_registration = true;
    } else {
        $enable_user_fb_registration = false;
    }

    if ($enable_user_fb_registration == true) {
        $enable_user_fb_registration_site = get_option('enable_user_fb_registration', 'users');
        if ($enable_user_fb_registration_site == 'y') {
            $enable_user_fb_registration = true;

            $fb_app_id = get_option('fb_app_id', 'users');
            $fb_app_secret = get_option('fb_app_secret', 'users');

            if ($fb_app_id != false) {
                $fb_app_id = trim($fb_app_id);
            }

            if ($fb_app_secret != false) {
                $fb_app_secret = trim($fb_app_secret);
            }


            if ($fb_app_id == '') {
                $enable_user_fb_registration = false;
            }

        } else {
            $enable_user_fb_registration = false;

        }
    }

    $form_show_first_name = get_option('form_show_first_name', 'users') == 'y';

    $form_show_last_name = get_option('form_show_last_name', 'users') == 'y';

    $form_show_password_confirmation = get_option('form_show_password_confirmation', 'users') == 'y';

    $form_show_address = get_option('form_show_address', 'users') == 'y';

    $captcha_disabled = get_option('captcha_disabled', 'users') == 'y';


    # Login Providers
    $facebook = get_option('enable_user_fb_registration', 'users') == 'y';
    $twitter = get_option('enable_user_twitter_registration', 'users') == 'y';
    $google = get_option('enable_user_google_registration', 'users') == 'y';
    $windows = get_option('enable_user_windows_live_registration', 'users') == 'y';
    $github = get_option('enable_user_github_registration', 'users') == 'y';
    if ($facebook or $twitter or $google or $windows or $github) {
        $have_social_login = true;
    } else {
        $have_social_login = false;
    }


    $allow_socials_login = get_option('allow_socials_login', 'users');
    if ($allow_socials_login == 'n') {
        $have_social_login = false;
    }


	$require_terms = get_option('require_terms', 'users');
	$terms_and_conditions_name = 'terms_user';
	if($require_terms) {
		$user_id_or_email = (is_logged()? user_id():false);
		if(mw()->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email)){
			$require_terms = 'n';
		} else {
			$terms_label = get_option('terms_label', 'users');
			$terms_label_cleared = str_replace('&nbsp;', '', $terms_label);
			$terms_label_cleared = strip_tags($terms_label_cleared);
			$terms_label_cleared = mb_trim($terms_label_cleared);
			if ($terms_label_cleared == '') {
				$terms_label = 'I agree with the <a href="' . site_url() . 'terms" target="_blank">Terms and Conditions</a>';
			}
		}
	}

    $show_newsletter_subscription = get_option('form_show_newsletter_subscription', 'users');
    if($show_newsletter_subscription == 'y') {
		$newsletter_subscribed = false;
		$user_id_or_email = (is_logged()? user_id():false);
		if(mw()->user_manager->terms_check('terms_newsletter', $user_id_or_email)){
			$newsletter_subscribed = true;
		}
    }

    $module_template = get_option('data-template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }


    if ($module_template != false) {
        $template_file = module_templates($config['module'], $module_template);

    } else {
        $template_file = module_templates($config['module'], 'default');

    }

    if (isset($template_file) and ($template_file) != false and is_file($template_file) != false) {
        include($template_file);
    } else {
        $template_file = module_templates($config['module'], 'default');
        if (($template_file) != false and is_file($template_file) != false) {
            include($template_file);
        } else {
            $complete_fallback = dirname(__FILE__) . DS . 'templates' . DS . 'default.php';
            if (is_file($complete_fallback) != false) {
                include($complete_fallback);
            }

        }
        //print 'No default template for '.  $config['module'] .' is found';
    }

    ?>

</div>
