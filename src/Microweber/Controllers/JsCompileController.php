<?php

namespace Microweber\Controllers;

use Microweber\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class JsCompileController extends Controller
{

    /** @var \Microweber\Application */
    public $app;


    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
    }


    public function apijs()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }

        $ref_page = false;
        if (isset($_REQUEST['id'])) {
            $ref_page = $this->app->content_manager->get_by_id($_REQUEST['id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $_SERVER['HTTP_REFERER'];
            if ($ref_page != '') {
                $ref_page = $this->app->content_manager->get_by_url($ref_page);
                if (is_array($ref_page)) {
                    $page_id = $ref_page['id'];
                }
            }
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $cat_url = mw()->category_manager->get_category_id_from_url($_SERVER['HTTP_REFERER']);
            if ($cat_url != false) {
                if (!defined('CATEGORY_ID')) {
                    define('CATEGORY_ID', intval($cat_url));
                }
            }
        }
        $file = mw_includes_path() . 'api' . DS . 'api.js';

        $last_modified_time = $lastModified = filemtime($file);

        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        if (defined('MW_VERSION')) {
            $etag = md5(filemtime($file) . MW_VERSION);
        } else {
            $etag = filemtime($file);
        }

        $this->app->content_manager->define_constants($ref_page);
        $l = new \Microweber\View($file);

        $l = $l->__toString();
        $l = str_replace('{SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('{MW_SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('%7BSITE_URL%7D', $this->app->url_manager->site(), $l);


        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
             // it makes an error
           $l = $this->minify_js($l);

            $userfiles_dir = userfiles_path();
            $hash = md5(site_url());
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
            $userfiles_cache_filename = $userfiles_cache_dir . 'api.' . $hash . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }
        $response = \Response::make($l);
        $response->header('Content-Type', 'application/javascript');
        if (!$this->app->make('config')->get('app.debug')) {
            // enable caching if in not in debug mode
            $response->header('Etag', $etag);
            $response->header('Last-Modified', gmdate('D, d M Y H:i:s', $last_modified_time) . ' GMT');
            $response->setTtl(30);
        }

        return $response;
    }


    public function apijs_settings()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }
        $lastModified = time() - 120;
        $etagFile = md5(serialize($_REQUEST));
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

        //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        //set last-modified header
        // header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        // header('Cache-Control: public');
        // header("Etag: $etagFile");

        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || $etagHeader == $etagFile) {
            // header("HTTP/1.1 304 Not Modified");
            // return;
            // exit;
        }

        $ref_page = false;

        if (isset($_REQUEST['id'])) {
            $ref_page = $this->app->content_manager->get_by_id($_REQUEST['id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $_SERVER['HTTP_REFERER'];
            if ($ref_page != '') {
                $ref_page = $this->app->content_manager->get_by_url($ref_page);
                if (is_array($ref_page)) {
                    $page_id = $ref_page['id'];

                } else {
                    $page_id = 0;
                }
            }
        }

        $cat_url = false;
        if (isset($_REQUEST['category_id'])) {
            $cat_url = intval($_REQUEST['category_id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $cat_url = mw()->category_manager->get_category_id_from_url($_SERVER['HTTP_REFERER']);
            $cat_url = intval($cat_url);
        }

        if ($cat_url != false) {
            if (!defined('CATEGORY_ID')) {
                define('CATEGORY_ID', intval($cat_url));
            }
        }

        // header("Content-type: text/javascript");

        $file = mw_includes_path() . 'api' . DS . 'api_settings.js';

        $this->app->content_manager->define_constants($ref_page);
        $l = new \Microweber\View($file);

        $l = $l->__toString();

        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $l = $this->minify_js($l);

            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
            $userfiles_cache_filename = $userfiles_cache_dir . 'api_settings.' . md5(site_url() . template_dir()) . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        $response = \Response::make($l);

        $response->header('Content-Type', 'application/javascript');
        return $response;

    }


    public function apijs_liveedit()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }

        $file = mw_includes_path() . 'api' . DS . 'liveedit.js';

        $last_modified_time = $lastModified = filemtime($file);

        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        if (defined('MW_VERSION')) {
            $etag = md5(filemtime($file) . MW_VERSION);
        } else {
            $etag = filemtime($file);
        }


        $l = new \Microweber\View($file);

        $l = $l->__toString();
        $l = str_replace('{SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('{MW_SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('%7BSITE_URL%7D', $this->app->url_manager->site(), $l);


        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $l = $this->minify_js($l);

            $userfiles_dir = userfiles_path();
            $hash = md5(site_url());
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
            $userfiles_cache_filename = $userfiles_cache_dir . 'api.liveedit.' . $hash . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!defined('MW_NO_OUTPUT_CACHE')) {
                    define('MW_NO_OUTPUT_CACHE', true);
                }
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        $response = \Response::make($l);
        $response->header('Content-Type', 'application/javascript');
        if (!$this->app->make('config')->get('app.debug')) {
            // enable caching if in not in debug mode
            $response->header('Etag', $etag);
            $response->header('Last-Modified', gmdate('D, d M Y H:i:s', $last_modified_time) . ' GMT');
            $response->setTtl(30);
        }

        return $response;
    }


    public function get_apijs_url()
    {


        $url = $this->app->url_manager->site('apijs') . '?mwv=' . MW_VERSION;
        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
            $hash = md5(site_url());
            $userfiles_cache_filename = $userfiles_cache_dir . 'api.' . $hash . '.' . MW_VERSION . '.js';
            if (is_file($userfiles_cache_filename)) {
                $url = userfiles_url() . 'cache/apijs/' . 'api.' . $hash . '.' . MW_VERSION . '.js';
            }
        }

        return $url;
    }


    public function get_apijs_settings_url()
    {
        $url = $this->app->url_manager->site('apijs_settings') . '?mwv=' . MW_VERSION;;
        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $file = mw_includes_path() . 'api' . DS . 'api_settings.js';
            $mtime = false;
            if (is_file($file)) {
                $mtime = filemtime($file);
            }

            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
            $fn = 'api_settings.' . md5(site_url() . template_dir() . $mtime) . '.' . MW_VERSION . '.js';
            $userfiles_cache_filename = $userfiles_cache_dir . $fn;
            if (is_file($userfiles_cache_filename)) {
                if (is_file($userfiles_cache_filename)) {
                    $url = userfiles_url() . 'cache/apijs/' . $fn;
                }
            }
        }

        return $url;
    }


    public function get_liveeditjs_url()
    {
        $url = $this->app->url_manager->site('apijs_liveedit') . '?mwv=' . MW_VERSION;;
        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $file = mw_includes_path() . 'api' . DS . 'liveedit.js';
            $mtime = false;
            if (is_file($file)) {
                $mtime = filemtime($file);
            }

            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
            $fn = 'api.liveedit.' . md5(site_url() . template_dir() . $mtime) . '.' . MW_VERSION . '.js';
            $userfiles_cache_filename = $userfiles_cache_dir . $fn;
            if (is_file($userfiles_cache_filename)) {
                if (is_file($userfiles_cache_filename)) {
                    $url = userfiles_url() . 'cache/apijs/' . $fn;
                }
            }
        }

        return $url;
    }


    public function minify_js($layout)
    {
        return $layout;

       // has error on minifier
        $optimize_asset_loading = get_option('optimize_asset_loading', 'website');
        if ($optimize_asset_loading == 'y') {
            $minifier = normalize_path(MW_PATH . 'Utils/lib/JShrink/Minifier.php', false);
            if (is_file($minifier)) {
                include_once $minifier;


                $layout = \JShrink\Minifier::minify($layout);

            }
        }
        return $layout;
    }

}