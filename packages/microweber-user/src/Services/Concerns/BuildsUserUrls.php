<?php

namespace MicroweberPackages\User\Services\Concerns;

use MicroweberPackages\Url\Facades\UrlManager;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait BuildsUserUrls
{
    public function register_url()
    {
        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'register.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'register';
        } else {
            $default_url = 'users/register';
        }

        $checkout_url = $this->app->option_manager->get('register_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('register_url');

        if ($checkout_url_sess == false) {
            return UrlManager::site($default_url);
        } else {
            return UrlManager::site($checkout_url_sess);
        }
    }

    public function logout_url()
    {

        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'logout.php';
        $logout_url_settings = $this->app->option_manager->get('logout_url', 'users');
        $logout_url_sess = $this->session_get('logout_url');

        if ($logout_url_sess) {
            return $logout_url_sess;
        } else if ($logout_url_settings) {
            return $logout_url_settings;
        } else if (is_file($file)) {
            return site_url('logout');
        } else {
            if(route_exists('logout')) {
                return route('logout');
            } else {
                return site_url('logout');
            }

            //return route('api.user.logout');
          //  return route('logout');
        }

    }

    public function login_url()
    {
        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'login.php';
        $default_url = false;
//        if (is_file($file)) {
//            $default_url = 'login';
//        } else {
//            $default_url = 'users/login';
//        }
        $default_url = 'login';
        $login_url = $this->app->option_manager->get('login_url', 'users');
        if ($login_url != false and trim($login_url) != '') {
            $default_url = $login_url;
        }

        $login_url_sess = $this->session_get('login_url');

        if ($login_url_sess == false) {
            return UrlManager::site($default_url);
        } else {
            return UrlManager::site($login_url_sess);
        }
    }

    public function profile_url()
    {
        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'profile.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'profile';
        } else {
            $default_url = 'users/profile';
        }

        $profile_url = $this->app->option_manager->get('profile_url', 'users');
        if ($profile_url != false and trim($profile_url) != '') {
            $profile_url = $profile_url;
        }

        $profile_url_sess = $this->session_get('profile_url');

        if ($profile_url_sess == false) {
            return UrlManager::site($default_url);
        } else {
            return UrlManager::site($profile_url_sess);
        }
    }

    public function forgot_password_url()
    {
        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'forgot_password.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'forgot_password';
        } else {
            $default_url = 'users/forgot_password';
        }
        $checkout_url = $this->app->option_manager->get('forgot_password_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }
        $checkout_url_sess = $this->session_get('forgot_password_url');
        if ($checkout_url_sess == false) {
            return UrlManager::site($default_url);
        } else {
            return UrlManager::site($checkout_url_sess);
        }
    }
}
