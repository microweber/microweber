<?php

/**
 * Constructor function.
 *
 * @param null $class
 *
 * @return mixed|\MicroweberPackages\Application
 */
function mw($class = null)
{
    return app($class);
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

function mw_is_installed()
{
    return Config::get('microweber.is_installed');
}

if (!function_exists('d')) {
    function d($dump)
    {
        var_dump($dump);
    }
}


/**
 * Converts a path in the appropriate format for win or linux.
 *
 * @param string $path
 *                         The directory path.
 * @param bool $slash_it
 *                         If true, ads a slash at the end, false by default
 *
 * @return string The formatted string
 */
if (!function_exists('normalize_path')) {
function normalize_path($path, $slash_it = true)
{
    $path_original = $path;
    $s = DIRECTORY_SEPARATOR;
    $path = preg_replace('/[\/\\\]/', $s, $path);
    $path = str_replace($s . $s, $s, $path);
    if (strval($path) == '') {
        $path = $path_original;
    }
    if ($slash_it == false) {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
    } else {
        $path .= DIRECTORY_SEPARATOR;
        $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
    }
    if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
        $path = $path_original;
    }
    if ($slash_it == false) {
    } else {
        $path = $path . DIRECTORY_SEPARATOR;
        $path = reduce_double_slashes($path);
    }

    return $path;
}
}

if (!function_exists('reduce_double_slashes')) {
    /**
     * Removes double slashes from sting.
     *
     * @param $str
     *
     * @return string
     */
    function reduce_double_slashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }
}

if (!function_exists('lipsum')) {
function lipsum($number_of_characters = false)
{
    if ($number_of_characters == false) {
        $number_of_characters = 100;
    }

    $lipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et sapien varius gravida. Fusce porttitor consectetur risus ut tincidunt. Maecenas pellentesque nulla sodales enim consectetur commodo. Aliquam non dui leo, adipiscing posuere metus. Duis adipiscing auctor lorem ut pulvinar. Donec non magna massa, feugiat commodo felis. Donec ut nibh elit. Nulla pellentesque nulla diam, vitae consectetur neque.
        Etiam sed lorem augue. Vivamus varius tristique bibendum. Phasellus vitae tempor augue. Maecenas consequat commodo euismod. Aenean a lorem nec leo dignissim ultricies sed quis nisi. Fusce pellentesque tellus lectus, eu varius felis. Mauris lacinia facilisis metus, sed sollicitudin quam faucibus id.
        Donec ultrices cursus erat, non pulvinar lectus consectetur eu. Proin sodales risus a ante aliquet vel cursus justo viverra. Duis vel leo felis. Praesent hendrerit, sem vitae scelerisque blandit, enim neque pulvinar mi, vel lobortis elit dui vel dui. Donec ac sem sed neque consequat egestas. Curabitur pellentesque consequat ante, quis laoreet enim gravida eu. Donec varius, nisi non bibendum pellentesque, felis metus pretium ipsum, non vulputate eros magna ac sapien. Donec tincidunt porta tortor, et ornare enim facilisis vitae. Nulla facilisi. Cras ut nisi ac dolor lacinia tempus at sed eros. Integer vehicula arcu in augue adipiscing accumsan. Morbi placerat consectetur sapien sed gravida. Sed fringilla elit nisl, nec molestie felis. Nulla aliquet diam vitae diam iaculis porttitor.
        Integer eget tortor nulla, non dapibus erat. Sed ultrices consectetur quam at scelerisque. Nullam varius hendrerit nisl, ac cursus mi bibendum eu. Phasellus varius fermentum massa, sit amet ornare quam malesuada in. Quisque ac massa sem. Nulla eu erat metus, non tincidunt nibh. Nam consequat interdum nulla, at congue libero tincidunt eget. Sed cursus nulla eu felis faucibus porta. Nam sed lacus eros, nec pellentesque lorem. Sed dapibus, sapien mattis sollicitudin bibendum, libero augue dignissim felis, eget elementum felis nulla in velit. Donec varius, lectus non suscipit sollicitudin, urna est hendrerit nulla, vel vehicula arcu sem volutpat sapien. Ut nisi ipsum, accumsan vestibulum pulvinar eu, sodales id lacus. Nulla iaculis eros sit amet lectus tincidunt mattis. Ut eu nisl sit amet eros vestibulum imperdiet ut vel lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
        In hac habitasse platea dictumst. Aenean vehicula auctor eros non tincidunt. Donec tempor arcu ac diam sagittis mattis. Aenean eget augue nulla, non volutpat lorem. Praesent ut cursus magna. Mauris consequat suscipit nisi. Integer eu venenatis ligula. Maecenas leo risus, lacinia et auctor aliquet, aliquet in mi.
        Aliquam tincidunt dapibus augue, et vulputate dui aliquet et. Praesent pharetra mauris eu justo dignissim venenatis ornare nec nisl. Aliquam justo quam, varius eget congue vel, congue eget est. Ut nulla felis, luctus imperdiet molestie et, commodo vel nulla. Morbi at nulla dapibus enim bibendum aliquam non et ipsum. Phasellus sed cursus justo. Praesent sit amet metus lorem. Vivamus ut lorem dapibus turpis rhoncus pharetra. Donec in lacus sagittis nisl tempor sagittis quis a orci. Nam volutpat condimentum ante ac facilisis. Cras sem magna, vulputate id consequat rhoncus, suscipit non justo. In fringilla dignissim cursus.
        Nunc fringilla orci tellus, et euismod lorem. Ut quis turpis lacus, ac elementum lorem. Praesent fringilla, metus nec tincidunt consequat, sem sapien hendrerit nisi, nec feugiat libero risus a nisl. Duis arcu magna, ullamcorper et semper vitae, tincidunt nec libero. Etiam sed lacus ante. In imperdiet arcu eget elit commodo ut malesuada sem congue. Quisque porttitor porta sagittis. Nam porta elit sit amet mauris fermentum eu feugiat ipsum pretium. Maecenas sollicitudin aliquam eros, ut pretium nunc faucibus quis. Mauris id metus vitae libero viverra adipiscing quis ut nulla. Pellentesque posuere facilisis nibh, facilisis vehicula felis facilisis nec.
        Etiam pharetra libero nec erat pellentesque laoreet. Sed eu libero nec nisl vehicula convallis nec non orci. Aenean tristique varius nisl. Cras vel urna eget enim placerat vehicula quis sed velit. Quisque lacinia sagittis lectus eget sagittis. Pellentesque cursus suscipit massa vel ultricies. Quisque hendrerit lobortis elit interdum feugiat. Sed posuere volutpat erat vel lobortis. Vivamus laoreet mattis varius. Fusce tincidunt accumsan lorem, in viverra lectus dictum eu. Integer venenatis tristique dolor, ac porta lacus pellentesque pharetra. Suspendisse potenti. Ut dolor dolor, sollicitudin in auctor nec, facilisis non justo. Mauris cursus euismod gravida. In at orci in sapien laoreet euismod.
        Mauris purus urna, vulputate in malesuada ac, varius eget ante. Integer ultricies lacus vel magna dictum sit amet euismod enim dictum. Aliquam iaculis, ipsum at tempor bibendum, dolor tortor eleifend elit, sed fermentum magna nibh a ligula. Phasellus ipsum nisi, porta quis pellentesque sit amet, dignissim vel felis. Quisque condimentum molestie ligula, ac auctor turpis facilisis ac. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent molestie leo velit. Sed sit amet turpis massa. Donec in tortor quis metus cursus iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac habitasse platea dictumst. Proin leo nisl, faucibus non sollicitudin et, commodo id diam. Aliquam adipiscing, lorem a fringilla blandit, felis dui tristique ligula, vitae eleifend orci diam eget quam. Aliquam vulputate gravida leo eget eleifend. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
        Etiam et consectetur justo. Integer et ante dui, quis rutrum massa. Fusce nibh nisl, congue sit amet tempor vitae, ornare et nisi. Nulla mattis nisl ut ligula sagittis aliquam. Curabitur ac augue at velit facilisis venenatis quis sit amet erat. Donec lacus elit, auctor sed lobortis aliquet, accumsan nec mi. Quisque non est ante. Morbi vehicula pulvinar magna, quis luctus tortor varius et. Donec hendrerit nulla posuere odio lobortis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dapibus magna id ante sodales tempus. Maecenas at eleifend nulla.
        Sed eget gravida magna. Quisque vulputate diam nec libero faucibus vitae fringilla ligula lobortis. Aenean congue, dolor ut dapibus fermentum, justo lectus luctus sem, et vestibulum lectus orci non mauris. Vivamus interdum mauris at diam scelerisque porta mollis massa hendrerit. Donec condimentum lacinia bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam neque dolor, faucibus sed varius sit amet, vulputate vitae nunc.
        Etiam in lorem congue nunc sollicitudin rhoncus vel in metus. Integer luctus semper sem ut interdum. Sed mattis euismod diam, at porta mauris laoreet quis. Nam pellentesque enim id mi vestibulum gravida in vel libero. Nulla facilisi. Morbi fringilla mollis malesuada. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum sagittis consectetur auctor. Phasellus convallis turpis eget diam tristique feugiat. In consectetur quam faucibus purus suscipit euismod quis sed quam. Curabitur eget sodales dui. Quisque egestas diam quis sapien aliquet tincidunt.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam velit est, imperdiet ac posuere non, dictum et nunc. Duis iaculis lacus in libero lacinia ut consectetur nisi facilisis. Fusce aliquet nisl id eros dapibus viverra. Phasellus eget ultrices nisl. Nullam euismod tortor a metus hendrerit convallis. Donec dolor magna, fringilla in sollicitudin sit amet, tristique eget elit. Praesent adipiscing magna in ipsum vulputate non lacinia metus vestibulum. Aenean dictum suscipit mollis. Nullam tristique commodo dapibus. Fusce in tellus sapien, at vulputate justo. Nam ornare, lorem sit amet condimentum ultrices, ipsum velit tempor urna, tincidunt convallis sapien enim eget leo. Proin ligula tellus, ornare vitae scelerisque vitae, fringilla fermentum sem. Phasellus ornare, diam sed luctus condimentum, nisl felis ultricies tortor, ac tempor quam lacus sit amet lorem. Nunc egestas, nibh ornare dictum iaculis, diam nisl fermentum magna, malesuada vestibulum est mauris quis nisl. Ut vulputate pharetra laoreet.
        Donec mattis mauris et dolor commodo et pellentesque libero congue. Sed tristique bibendum augue sed auctor. Sed in ante enim. In sed lectus massa. Nulla imperdiet nisi at libero faucibus sagittis ac ac lacus. In dui purus, sollicitudin tempor euismod euismod, dapibus vehicula elit. Aliquam vulputate, ligula non dignissim gravida, odio elit ornare risus, a euismod est odio nec ipsum. In hac habitasse platea dictumst. Mauris posuere ultrices mattis. Etiam vitae leo vitae nunc porta egestas at vitae nibh. Sed pharetra, magna nec bibendum aliquam, dolor sapien consequat neque, sit amet euismod orci elit vitae enim. Sed erat metus, laoreet quis posuere id, congue id velit. Mauris ac velit vel ipsum dictum ornare eget vitae arcu. Donec interdum, neque at lacinia imperdiet, ante libero convallis quam, pellentesque faucibus quam dolor id est. Ut cursus facilisis scelerisque. Sed vitae ligula in purus malesuada porta.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vestibulum vestibulum metus. Integer ultrices ultricies pellentesque. Nulla gravida nisl a magna gravida ullamcorper. Vestibulum accumsan eros vel massa euismod in aliquam felis suscipit. Ut et purus enim, id congue ante. Mauris magna lectus, varius porta pellentesque quis, dignissim in est. Nulla facilisi. Nullam in malesuada mauris. Ut fermentum orci neque. Aliquam accumsan justo a lacus vestibulum fermentum. Donec molestie, quam id adipiscing viverra, massa velit aliquam enim, vitae dapibus turpis libero id augue. Quisque mi magna, mollis vel tincidunt nec, adipiscing sed metus. Maecenas tincidunt augue quis felis dapibus nec elementum justo fringilla. Sed eget massa at sapien tincidunt porta eu id sapien.';

    return character_limiter($lipsum, $number_of_characters, '');
}
}

/**
 * Returns the current microtime.
 *
 * @return bool|string $date The current microtime
 *
 * @category Date
 *
 * @link     http://www.webdesign.org/web-programming/php/script-execution-time.8722.html#ixzz2QKEAC7PG
 */
if (!function_exists('microtime_float')) {
function microtime_float()
{
    list($msec, $sec) = explode(' ', microtime());
    $microtime = (float)$msec + (float)$sec;

    return $microtime;
}
}

/**
 * Limits a string to a number of characters.
 *
 * @param        $str
 * @param int $n
 * @param string $end_char
 *
 * @return string
 *
 * @category Strings
 */
if (!function_exists('character_limiter')) {
function character_limiter($str, $n = 500, $end_char = '&#8230;')
{
    if(!is_string($str)){
        return $str;
    }
    if (strlen($str) < $n) {
        return $str;
    }
    $str = strip_tags($str);
    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

    if (strlen($str) <= $n) {
        return $str;
    }

    $out = '';
    foreach (explode(' ', trim($str)) as $val) {
        $out .= $val . ' ';

        if (strlen($out) >= $n) {
            $out = trim($out);

            return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
        }
    }
}
}



function api_url($str = '')
{

    if (\Illuminate\Support\Facades\Route::has($str)) {
        return route($str);
    }


    $str = ltrim($str, '/');

    return site_url('api/' . $str);
}

function api_nosession_url($str = '')
{
    $str = ltrim($str, '/');

    return site_url('api_nosession/' . $str);
}

function auto_link($text)
{
    return mw()->format->auto_link($text);
}

function prep_url($text)
{
    return mw()->format->prep_url($text);
}

function is_arr($var)
{
    return isarr($var);
}

function isarr($var)
{
    if (is_array($var) and !empty($var)) {
        return true;
    } else {
        return false;
    }
}

function array_search_multidimensional($array, $column, $key)
{
    return (array_search($key, array_column($array, $column)));
}

if (!function_exists('is_ajax')) {
    function is_ajax()
    {
        return mw()->url_manager->is_ajax();
    }
}
if (!function_exists('url_current')) {
    function url_current($skip_ajax = false, $no_get = false)
    {
        return mw()->url_manager->current($skip_ajax, $no_get);
    }
}
if (!function_exists('url_segment')) {
    function url_segment($k = -1, $page_url = false)
    {
        return mw()->url_manager->segment($k, $page_url);
    }
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
    mw()->template->clear_cache();
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





if (!function_exists('is_closure')) {
    function is_closure($t)
    {
        return is_object($t) or ($t instanceof \Closure);
    }
}

if (!function_exists('collection_to_array')) {
    function collection_to_array($data)
    {
        if (
            $data instanceof \Illuminate\Database\Eloquent\Collection
            or $data instanceof \Illuminate\Support\Collection
            or $data instanceof \Illuminate\Database\Eloquent\Model
        ) {
            return $data->toArray();
        }
        return $data;

    }
}


function str_replace_bulk($search, $replace, $subject, &$count = null)
{
    // Assumes $search and $replace are equal sized arrays
    $lookup = array_combine($search, $replace);
    $result = preg_replace_callback(
        '/' .
        implode('|', array_map(
            function ($s) {
                return preg_quote($s, '/');
            },
            $search
        )) .
        '/',
        function ($matches) use ($lookup) {
            return $lookup[$matches[0]];
        },
        $subject,
        -1,
        $count
    );
    if (
        $result !== null ||
        count($search) < 2 // avoid infinite recursion on error
    ) {
        return $result;
    }
    // With a large number of replacements (> ~2500?),
    // PHP bails because the regular expression is too large.
    // Split the search and replacements in half and process each separately.
    // NOTE: replacements within replacements may now occur, indeterminately.
    $split = (int)(count($search) / 2);
    $result = str_replace_bulk(
        array_slice($search, $split),
        array_slice($replace, $split),
        str_replace_bulk(
            array_slice($search, 0, $split),
            array_slice($replace, 0, $split),
            $subject,
            $count1
        ),
        $count2
    );
    $count = $count1 + $count2;
    return $result;
}



if (!function_exists('array_recursive_diff')) {
    function array_recursive_diff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = array_recursive_diff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }
}

function get_favicon_tag()
{
    $favicon_image = get_option('favicon_image', 'website');

    if (!$favicon_image) {
        $ui_favicon = mw()->ui->brand_favicon();
        if ($ui_favicon and trim($ui_favicon) != '') {
            $favicon_image = trim($ui_favicon);
        }
    }

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
