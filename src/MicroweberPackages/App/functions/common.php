<?php


use Illuminate\Support\Facades\Config;

if (! function_exists('mw')) {
    function mw($class = null)
    {
        return app($class);
    }
}

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     * @return mixed|\MicroweberPackages\App\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return empty($parameters)
            ? Container::getInstance()->make($abstract)
            : Container::getInstance()->makeWith($abstract, $parameters);
    }
}

function mw_is_installed() : bool
{
    return (bool) Config::get('microweber.is_installed');
}





function api_url($str = '')
{

    if (\Illuminate\Support\Facades\Route::has($str)) {
        return route($str);
    }


    $str = ltrim($str, '/');

    return site_url('api/' . $str);
}
/*
function api_nosession_url($str = '')
{
    $str = ltrim($str, '/');

    return site_url('api_nosession/' . $str);
}*/

function auto_link($text)
{
    return mw()->format->auto_link($text);
}

function prep_url($text)
{
    return mw()->format->prep_url($text);
}








/**
 * Returns the curent url path, does not include the domain name.
 *
 * @return string the url string
 */
function url_path($skip_ajax = false)
{
    return mw()->url_manager->string($skip_ajax);
}

/**
 * Returns the curent url path, does not include the domain name.
 *
 * @return string the url string
 */
function url_string($skip_ajax = false)
{
    return mw()->url_manager->string($skip_ajax);
}

function url_title($text)
{
    return mw()->url_manager->slug($text);
}

function url_param($param, $skip_ajax = false)
{
    return mw()->url_manager->param($param, $skip_ajax);
}

function url_set_param($param, $value)
{
    return site_url(mw()->url_manager->param_set($param, $value));
}

function url_unset_param($param)
{
    return site_url(mw()->url_manager->param_unset($param));
}

/**
 *  Gets the data from the cache.
 *
 *  If data is not found it return false
 *
 *
 * @example
 * <code>
 *
 * $cache_id = 'my_cache_'.crc32($sql_query_string);
 * $cache_content = cache_get_content($cache_id, 'my_cache_group');
 *
 * </code>
 *
 * @param string $cache_id id of the cache
 * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
 * @param bool $expiration_in_seconds You can pass custom cache object or leave false.
 *
 * @return mixed returns array of cached data or false
 */
function cache_get($cache_id, $cache_group = 'global', $expiration = false)
{
    return mw()->cache_manager->get($cache_id, $cache_group, $expiration);
}

/**
 * Stores your data in the cache.
 * It can store any value that can be serialized, such as strings, array, etc.
 *
 * @example
 * <code>
 * //store custom data in cache
 * $data = array('something' => 'some_value');
 * $cache_id = 'my_cache_id';
 * $cache_content = cache_save($data, $cache_id, 'my_cache_group');
 * </code>
 *
 * @param mixed $data_to_cache
 *                                      your data, anything that can be serialized
 * @param string $cache_id
 *                                      id of the cache, you must define it because you will use it later to
 *                                      retrieve the cached content.
 * @param string $cache_group
 *                                      (default is 'global') - this is the subfolder in the cache dir.
 * @param int $expiration_in_seconds
 *
 * @return bool
 */
function cache_save($data_to_cache, $cache_id, $cache_group = 'global', $expiration_in_seconds = false)
{
    return mw()->cache_manager->save($data_to_cache, $cache_id, $cache_group, $expiration_in_seconds);
}

/**
 * Clears all cache data.
 *
 * @example
 *          <code>
 *          //delete all cache
 *          clearcache();
 *          </code>
 *
 * @return bool
 */
function clearcache()
{
    mw()->cache_manager->clear();
    app()->template_manager->clear_cache();
    mw()->ui->clear_cache();

    $empty_folder = userfiles_path() . 'cache' . DS;

    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, true);
    }

    if (!is_dir($empty_folder)) {
        @mkdir_recursive($empty_folder);
    }

    $empty_folder = mw_cache_path().'composer';
    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, false);
    }
    if (!is_dir($empty_folder)) {
        @mkdir_recursive($empty_folder);
    }


    //remove blade cache
    $empty_folder = storage_path() . DS . 'framework' . DS . 'views' . DS;
    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, false);
    }

    $env = app()->environment();
    $env = sanitize_path($env);
    //remove framework cache
    $empty_folder = storage_path() . DS . 'framework' . DS . 'cache' . DS .$env . DS;
    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, false);
    }

    //remove composer-download cache
    $empty_folder = storage_path() . DS .  'cache' . DS . 'composer-download' . DS;
    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, false);
    }

    //remove updates_temp cache
    $empty_folder = storage_path() . DS .  'cache' . DS . 'updates_temp' . DS;
    if (is_dir($empty_folder)) {
        @rmdir_recursive($empty_folder, false);
    }

    if (isset($_GET['redirect_to'])) {
        return app()->url_manager->redirect($_GET['redirect_to']);
    }

    if(function_exists('opcache_reset')){
        @opcache_reset();
    }

    return true;
}

/**
 * Prints cache debug information.
 *
 * @return array
 *
 * @example
 * <code>
 * //get cache items info
 *  $cached_items = cache_debug();
 * print_r($cached_items);
 * </code>
 */
function cache_debug()
{
    return mw()->cache_manager->debug();
}

/**
 * Deletes cache for given $cache_group recursively.
 *
 * @param string $cache_group
 *                            (default is 'global') - this is the subfolder in the cache dir.
 *
 * @return bool
 *
 * @example
 * <code>
 * //delete the cache for the content
 *  cache_clear("content");
 *
 * //delete the cache for the content with id 1
 *  cache_clear("content/1");
 *
 * //delete the cache for users
 *  cache_clear("users");
 *
 * //delete the cache for your custom table eg. my_table
 * cache_clear("my_table");
 * </code>
 */
function cache_clear($cache_group = 'global')
{
    return mw()->cache_manager->delete($cache_group);
}

//same as cache_clear
function cache_delete($cache_group = 'global')
{
    return mw()->cache_manager->delete($cache_group);
}







function get_favicon_image()
{
    $favicon_image = get_option('favicon_image', 'website');

    if (!$favicon_image) {
        $ui_favicon = mw()->ui->brand_favicon();
        if ($ui_favicon and trim($ui_favicon) != '') {
            $favicon_image = trim($ui_favicon);
        }
    }
    return $favicon_image;
}
function get_favicon_tag()
{
    $favicon_image = get_favicon_image();

    if ($favicon_image) {
        echo '<link rel="shortcut icon" href="' . $favicon_image . '" />';
    }
}

function multilanguage_route_prefix($prefix) {

    if (is_module('multilanguage')) {
        if (get_option('is_active', 'multilanguage_settings') == 'y') {
            $language = mw()->lang_helper->current_lang_display();
            $prefix = $language . '/' . $prefix;
        }
    }

    return $prefix;
}

function set_cookie($key, $value, $time = false)
{
    if ($time == false) {
        $time = (86400 * 30);
    }

    setcookie($key, $value, time() + $time, "/");
    $_COOKIE[$key] = $value;
    \Illuminate\Support\Facades\Cookie::queue($key, $value, $time);

}

function get_asset($path = null) {
    if(!$path) {
        return '';
    }
    $path =  base_path() . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);

    if(!file_exists($path)) {
        return '';
    }
    return file_get_contents($path);
}
