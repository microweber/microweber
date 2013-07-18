<?php




/**
 *  Generates static pages navigation from directory of files
 * @category Content
 * @package Content
 * @subpackage Experimental
 * @internal not_tested
 * @uses get_content_by_url()
 * @param $params = array();
 * @param $params['dir_name'] = your dir; //path to the directory root
 * @return string <ul> with <li>
 */
function static_pages_tree($params = false)
{
    return \mw\content\StaticPages::tree($params);


}

/**
 *  Get a static page from a file
 * @category Content
 * @package Content
 * @subpackage Experimental
 * @internal not_tested
 * @uses get_content_by_url()
 */
function static_page_get($params = false)
{

    return \mw\content\StaticPages::get($params);


}



/**
 *  Get page by HTTP_REFERER
 * @category Content
 * @package Content
 * @subpackage Advanced
 * @uses get_content_by_url()
 */
function get_ref_page()
{
    $ref_page = $_SERVER ['HTTP_REFERER'];

    if ($ref_page != '') {
        $page = get_page_by_url($ref_page);
        return $page;
    }
    return false;

}



/**
 *  Get post by HTTP_REFERER
 * @category Content
 * @package Content
 * @subpackage Advanced
 * @uses get_content_by_url()
 */
function get_ref_post()
{
    $ref_page = $_SERVER ['HTTP_REFERER'];
    //p($ref_page);
    // $CI = get_instance ();
    if ($ref_page != '') {
        $page = get_content_by_url($ref_page);

        return $page;
    }
    return false;

}