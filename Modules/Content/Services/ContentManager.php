<?php

namespace Modules\Content\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Support\BreadcrumbLinks;
use Modules\Content\Support\ContentManagerCrud;
use Modules\Content\Support\ContentManagerHelpers;
use Modules\Content\Support\PagingLinks;
use Modules\Content\Support\PagesTree;
use Modules\Content\Support\PagingNav;

/**
 * Class ContentManager
 *
 * Manages content operations including CRUD operations, content relationships, and custom fields.
 *
 * @package Modules\Content\Services
 */
class ContentManager
{
    public $app;
    public $crud;
    public $helpers;
    public $contentRepository;
    public $pagingLinks;
    public $pagesTree;
    public $breadcrumbLinks;
    public $pagingNav;


    public $content_id = false;
    public $product_id = false;
    public $page_id = false;
    public $main_page_id = false;
    public $post_id = false;
    public $category_id = false;


    public function __construct($app = null)
    {
        $this->app = $app ?: app();
        $this->crud = new ContentManagerCrud($this->app);
        $this->helpers = new ContentManagerHelpers($this->app);
        $this->contentRepository = new ContentRepository();
        $this->pagingLinks = new PagingLinks($this->app);
        $this->pagesTree = new PagesTree($this->app);
        $this->breadcrumbLinks = new BreadcrumbLinks($this->app);
        $this->pagingNav = new PagingNav($this->app);

    }

    /**
     * Get paging links for content pagination
     */
    public function pagingLinks($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')
    {
        return $this->pagingLinks->get($base_url, $pages_count, $paging_param, $keyword_param);
    }

    /**
     * Get pages tree HTML
     */
    public function pagesTree($params)
    {
        return $this->pagesTree->get($params);
    }

    /**
     * Get content items based on parameters
     *
     * @param array|bool $params Query parameters for filtering content
     *                          Example params:
     *                          [
     *                              'single' => true,           // Return single item
     *                              'content_type' => 'page',   // Content type
     *                              'title' => 'Home',          // Content title
     *                              'is_active' => 1,           // Active status
     *                          ]
     * @return array|Collection Content items matching the parameters
     *
     * @example
     * // Get all active pages
     * $pages = $contentManager->get(['content_type' => 'page', 'is_active' => 1]);
     *
     * // Get single post by title
     * $post = $contentManager->get(['single' => true, 'content_type' => 'post', 'title' => 'My Post']);
     */
    public function get($params = false)
    {
        return $this->crud->get($params);
    }

    /**
     * Get content item by ID
     *
     * @param int $id Content ID
     * @return array|null Content item data or null if not found
     *
     * @example
     * // Get content with ID 123
     * $content = $contentManager->getById(123);
     */
    public function getById($id)
    {
        return $this->contentRepository->getById($id);
    }

    /**
     * Get content item by URL
     *
     * @param string $url Content URL/slug
     * @param bool $noRecursive If true, won't check parent URLs recursively
     * @return array|null Content item data or null if not found
     *
     * @example
     * // Get content for URL 'blog/my-post'
     * $content = $contentManager->getByUrl('blog/my-post');
     *
     * // Get content without recursive parent check
     * $content = $contentManager->getByUrl('my-page', true);
     */
    public function getByUrl($url = '', $noRecursive = false)
    {
        return $this->crud->get_by_url($url, $noRecursive);
    }

    /**
     * Get content item by title
     *
     * @param string $title Content title
     * @return array|null Single content item or null if not found
     *
     * @example
     * // Get content with title 'About Us'
     * $content = $contentManager->getByTitle('About Us');
     */
    public function getByTitle($title = '')
    {
        return $this->get(['single' => true, 'title' => $title]);
    }

    /**
     * Get content ID from URL
     *
     * @param string $url Content URL/slug
     * @return int|null Content ID or null if not found
     *
     * @example
     * // Get content ID for URL 'blog/my-post'
     * $contentId = $contentManager->getContentIdFromUrl('blog/my-post');
     */
    public function getContentIdFromUrl($url = '')
    {
        $content = $this->getByUrl($url);
        return $content['id'] ?? null;
    }

    /**
     * Save content item
     *
     * @param array $data Content data to save
     *                    Example data:
     *                    [
     *                        'title' => 'My Post',
     *                        'content_type' => 'post',
     *                        'content' => '<p>Post content</p>',
     *                        'is_active' => 1,
     *                        'add_content_to_menu' => [1, 2] // Menu IDs
     *                    ]
     * @param bool $deleteCache Whether to delete cache after save
     * @return int Saved content ID
     *
     * @example
     * // Create new page
     * $contentId = $contentManager->save([
     *     'title' => 'New Page',
     *     'content_type' => 'page',
     *     'content' => '<h1>Welcome</h1>',
     *     'is_active' => 1
     * ]);
     *
     * // Update existing content
     * $contentId = $contentManager->save([
     *     'id' => 123,
     *     'title' => 'Updated Title'
     * ]);
     */
    public function save($data)
    {
        $this->app->event_manager->trigger('content.manager.before.save', $data);

        $save = $this->crud->save($data);

        $afterSave = $data;
        $afterSave['id'] = $save;

        $this->app->event_manager->trigger('content.manager.after.save', $afterSave);
        event_trigger('mw_save_content', $save);

        return $save;
    }

    /**
     * Delete content item
     *
     * @param int $id Content ID to delete
     * @return bool True if deleted successfully
     *
     * @example
     * // Delete content with ID 123
     * $deleted = $contentManager->delete(123);
     */
    public function delete($id)
    {
        return $this->crud->delete($id);
    }

    /**
     * Get parent content items
     *
     * @param int $id Content ID to get parents for
     * @return array Array of parent content items
     *
     * @example
     * // Get parents of content with ID 123
     * $parents = $contentManager->getParents(123);
     */
    public function getParents($id = 0)
    {
        return $this->contentRepository->getParents($id);
    }

    /**
     * Get child content items
     *
     * @param int $id Content ID to get children for
     * @return array Array of child content items
     *
     * @example
     * // Get children of content with ID 123
     * $children = $contentManager->getChildren(123);
     */
    public function getChildren($id = 0)
    {
        return $this->contentRepository->getChildren($id);
    }

    /**
     * Get pages with optional filtering
     *
     * @param array|string|bool $params Query parameters for filtering pages
     *                                  Example params:
     *                                  [
     *                                      'is_active' => 1,
     *                                      'parent_id' => 0
     *                                  ]
     * @return array|Collection Array of pages
     *
     * @example
     * // Get all active pages
     * $pages = $contentManager->getPages(['is_active' => 1]);
     *
     * // Get pages as children of page ID 5
     * $pages = $contentManager->getPages(['parent_id' => 5]);
     */
    public function getPages($params = false)
    {
        $params = is_string($params) ? parse_params($params) : $params;
        $params = is_array($params) ? $params : [];
        $params['content_type'] = 'page';

        return $this->get($params);
    }

    /**
     * Get posts with optional filtering
     *
     * @param array|string|bool $params Query parameters for filtering posts
     *                                  Example params:
     *                                  [
     *                                      'is_active' => 1,
     *                                      'category_id' => 5
     *                                  ]
     * @return array|Collection Array of posts
     *
     * @example
     * // Get all active posts
     * $posts = $contentManager->getPosts(['is_active' => 1]);
     *
     * // Get posts from category ID 5
     * $posts = $contentManager->getPosts(['category_id' => 5]);
     */
    public function getPosts($params = false)
    {
        $params = is_string($params) ? parse_params($params) : $params;
        $params = is_array($params) ? $params : [];
        $params['content_type'] = 'post';
        $params['subtype'] = 'post';

        return $this->get($params);
    }

    /**
     * Get products with optional filtering
     *
     * @param array|string|bool $params Query parameters for filtering products
     *                                  Example params:
     *                                  [
     *                                      'is_active' => 1,
     *                                      'price_range' => [10, 100]
     *                                  ]
     * @return array|Collection Array of products
     *
     * @example
     * // Get all active products
     * $products = $contentManager->getProducts(['is_active' => 1]);
     *
     * // Get products in price range
     * $products = $contentManager->getProducts(['price_range' => [10, 100]]);
     */
    public function getProducts($params = false)
    {
        $params = is_string($params) ? parse_params($params) : $params;
        $params = is_array($params) ? $params : [];
        $params['content_type'] = 'product';

        return $this->get($params);
    }

    /**
     * Get custom fields for content
     *
     * @param int $contentId Content ID to get custom fields for
     * @param bool $returnFull Whether to return full field data
     * @param string|bool $fieldType Optional field type filter
     * @return array Array of custom fields
     *
     * @example
     * // Get all custom fields for content
     * $fields = $contentManager->getCustomFields(123);
     *
     * // Get only text fields
     * $textFields = $contentManager->getCustomFields(123, true, 'text');
     *
     * // Get basic field data
     * $basicFields = $contentManager->getCustomFields(123, false);
     */
    public function getCustomFields($contentId, $returnFull = true, $fieldType = false)
    {
        $filter = [
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
            'rel_id' => $contentId,
            'return_full' => $returnFull
        ];

        if ($fieldType) {
            $filter['type'] = $fieldType;
        }

        return $this->app->fields_manager->get($filter);
    }

    /**
     * Get tags for content
     *
     * @param int|bool $contentId Content ID to get tags for, false for all tags
     * @param bool $returnFullTagsData Whether to return full tag data
     * @return array Array of tags
     *
     * @example
     * // Get tags for content
     * $tags = $contentManager->getTags(123);
     *
     * // Get all tags with full data
     * $allTags = $contentManager->getTags(false, true);
     */
    public function getTags($contentId = false, $returnFullTagsData = false)
    {
        return $this->contentRepository->tags($contentId, $returnFullTagsData);
    }

    /**
     * Get media items for content
     *
     * @param int $contentId Content ID to get media for
     * @return array Array of media items
     *
     * @example
     * // Get all media for content
     * $media = $contentManager->getMedia(123);
     */
    public function getMedia($contentId)
    {
        return $this->contentRepository->getMedia($contentId);
    }

    /**
     * Get categories for content
     *
     * @param int $contentId Content ID to get categories for
     * @return array Array of categories
     *
     * @example
     * // Get categories for content
     * $categories = $contentManager->getCategories(123);
     */
    public function getCategories($contentId)
    {
        return $this->contentRepository->getCategories($contentId);
    }

    /**
     * Get additional data for content
     *
     * @param int $contentId Content ID to get data for
     * @return array Array of content data
     *
     * @example
     * // Get additional data for content
     * $contentData = $contentManager->getContentData(123);
     */
    public function getContentData($contentId)
    {
        return $this->contentRepository->getContentData($contentId);
    }

    /**
     * Get edit field HTML
     *
     * @param array $data Field data
     *                    Example data:
     *                    [
     *                        'field_type' => 'text',
     *                        'name' => 'title',
     *                        'value' => 'Current Title'
     *                    ]
     * @return string HTML for edit field
     *
     * @example
     * // Get text field HTML
     * $fieldHtml = $contentManager->getEditField([
     *     'field_type' => 'text',
     *     'name' => 'title',
     *     'value' => 'Current Title'
     * ]);
     */
    public function getEditField($data)
    {
        return $this->crud->get_edit_field($data);
    }

    /**
     * Save content field
     *
     * @param array $data Field data to save
     *                    Example data:
     *                    [
     *                        'field_type' => 'text',
     *                        'name' => 'title',
     *                        'value' => 'New Title',
     *                        'content_id' => 123
     *                    ]
     * @param bool $deleteCache Whether to delete cache after save
     * @return mixed Saved field ID or result
     *
     * @example
     * // Save text field
     * $result = $contentManager->saveContentField([
     *     'field_type' => 'text',
     *     'name' => 'title',
     *     'value' => 'New Title',
     *     'content_id' => 123
     * ]);
     */
    public function saveContentField($data, $deleteCache = true)
    {
        return $this->helpers->save_content_field($data, $deleteCache);
    }

    /**
     * Reorder content items
     *
     * @param array $data Reorder data
     *                    Example data:
     *                    [
     *                        'ids' => [1, 2, 3], // Content IDs in new order
     *                    ]
     * @return bool True if reordered successfully
     *
     * @example
     * // Reorder content items
     * $reordered = $contentManager->reorder([
     *     'ids' => [1, 2, 3]
     * ]);
     */
    public function reorder($data)
    {
        return $this->crud->reorder($data);
    }

    /**
     * Set content as published
     *
     * @param int $id Content ID to publish
     * @return int Content ID
     *
     * @example
     * // Publish content
     * $contentId = $contentManager->setPublished(123);
     */
    public function setPublished($id)
    {
        return $this->save([
            'id' => $id,
            'is_active' => 1
        ]);
    }

    /**
     * Set content as unpublished
     *
     * @param int $id Content ID to unpublish
     * @return int Content ID
     *
     * @example
     * // Unpublish content
     * $contentId = $contentManager->setUnpublished(123);
     */
    public function setUnpublished($id)
    {
        return $this->save([
            'id' => $id,
            'is_active' => 0
        ]);
    }


    function post_id()
    {
        return $this->app->template_manager->getPostId();
    }


    function product_id()
    {
        return $this->app->template_manager->getProductId();
    }

    function content_id()
    {
        return $this->app->template_manager->getContentId();

    }

    function category_id()
    {
        return $this->app->template_manager->getCategoryId();

    }

    function page_id()
    {
        return $this->app->template_manager->getPageId();
    }

    function main_page_id()
    {
        return $this->app->template_manager->getMainPageId();
    }


    /**
     * Get single content item by id from the content_table.
     *
     * @param int|string $id The id of the page or the url of a page
     *
     * @return array The page row from the database
     *
     * @category  Content
     * @function  get_page
     *
     * @example
     * <pre>
     * Get by id
     * $page = $this->get_page(1);
     * var_dump($page);
     * </pre>
     */
    public function get_page($id = 0)
    {
        if (intval($id) == 0) {
            return false;
        }

        $page = $this->get_by_id($id);
        return $page;


    }

    /**
     * Get single content item by id from the content_table.
     *
     * @param int $id The id of the content item
     *
     * @return array
     *
     * @category  Content
     * @function  get_content_by_id
     *
     * @example
     * <pre>
     * $content = $this->get_by_id(1);
     * var_dump($content);
     * </pre>
     */
    public function get_by_id($id)
    {

        return app()->content_repository->getById($id);

    }

    public function get_by_url($url = '', $no_recursive = false)
    {
        return $this->crud->get_by_url($url, $no_recursive);
    }

    public function get_by_title($title = '')
    {
        return $this->get(['single' => true, 'title' => $title]);
    }

    public function get_content_id_from_url($url = '')
    {
        $content = $this->get_by_url($url);
        if ($content && isset($content['id'])) {
            return $content['id'];
        }
    }


    /**
     * Get array of content items from the database.
     *
     * It accepts string or array as parameters. You can pass any db field name as parameter to filter content by it.
     * All parameter are passed to the get() function
     *
     * You can get and filter content and also order the results by criteria
     *
     * @function get_content
     *
     *
     * @desc     Get array of content items from the content DB table
     *
     * @param mixed|array|bool|string $params You can pass parameters as string or as array
     * @params
     *
     * *Some parameters you can use*
     *  You can use all defined database fields as parameters
     *
     * .[params-table]
     *|-----------------------------------------------------------------------------
     *| Field Name          | Description               | Values
     *|------------------------------------------------------------------------------
     *| id                  | the id of the content     |
     *| is_active           | published or unpublished  | "y" or "n"
     *| parent              | get content with parent   | any id or 0
     *| created_by          | get by author id          | any user id
     *| created_at          | the date of creation      |
     *| updated_at          | the date of last edit     |
     *| content_type        | the type of the content   | "page" or "post", anything custom
     *| subtype             | subtype of the content    | "static","dynamic","post","product", anything custom
     *| url                 | the link to the content   |
     *| title               | Title of the content      |
     *| content             | The html content saved in the database |
     *| description         | Description used for the content list |
     *| position            | The order position        |
     *| active_site_template   | Current template for the content |
     *| layout_file         | Current layout from the template directory |
     *| is_deleted          | flag for deleted content  |  "n" or "y"
     *| is_home             | flag for homepage         |  "n" or "y"
     *| is_shop             | flag for shop page        |  "n" or "y"
     *
     * @return array|bool|mixed Array of content or false if nothing is found
     *
     * @uses     get() You can use all the options of get(), such as limit, order_by, count, etc...
     *
     * @example
     * #### Get with parameters as array
     * <code>
     *
     * $params = array();
     * $params['is_active'] = 1; //get only active content
     * $params['parent'] = 2; //get by parent id
     * $params['created_by'] = 1; //get by author id
     * $params['content_type'] = 'post'; //get by content type
     * $params['subtype'] = 'product'; //get by subtype
     * $params['title'] = 'my title'; //get by title
     *
     * $data = $this->get($params);
     * var_dump($data);
     *
     * </code>
     * @example
     * #### Get by params as string
     * <code>
     *  $data = $this->get('is_active=1');
     *  var_dump($data);
     * </code>
     * @example
     * #### Ordering and sorting
     * <code>
     *  //Order by position
     *  $data = $this->get('content_type=post&is_active=1&order_by=position desc');
     *  var_dump($data);
     *
     *  //Order by date
     *  $data = $this->get('content_type=post&is_active=1&order_by=updated_at desc');
     *  var_dump($data);
     *
     *  //Order by title
     *  $data = $this->get('content_type=post&is_active=1&order_by=title asc');
     *  var_dump($data);
     *
     *  //Get content from last week
     *  $data = $this->get('created_at=[mt]-1 week&is_active=1&order_by=title asc');
     *  var_dump($data);
     * </code>
     */


    public function get_children($id = 0)
    {
        return app()->content_repository->getChildren($id);
    }

    public function get_data($params = false)
    {
        return $this->app->data_fields_manager->get($params);
    }

    public function data($content_id, $field_name = false)
    {
        $data = array();
        $data['content_id'] = intval($content_id);
        $values = $this->app->data_fields_manager->getValues($data);

        if ($field_name) {
            if (isset($values[$field_name])) {
                return $values[$field_name];
            } else {
                return false;
            }
        }

        return $values;
    }

    public function tags($content_id = false, $return_full = false)
    {
        return $this->app->content_repository->tags($content_id, $return_full);
    }

    public function attributes($content_id)
    {
        $data = array();
        $data['rel_type'] = morph_name(\Modules\Content\Models\Content::class);
        $data['rel_id'] = intval($content_id);

        return $this->app->attributes_manager->getValues($data);
    }

    /**
     * paging.
     *
     * paging
     *
     * @param $params ['num'] = 5; //the numer of pages
     *
     * @return array - html string with ul/li
     * @link
     *
     * @category  posts
     *
     * @internal  param $display =
     *            'default' //sets the default paging display with <ul> and </li>
     *            tags. If $display = false, the function will return the paging
     *            array which is the same as $posts_pages_links in every template
     *
     * @author    Microweber
     *
     */
    public function paging($params)
    {


        return $this->pagingNav->get($params);

    }

    public function paging_links($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')
    {
        return $this->pagingLinks->get($base_url, $pages_count, $paging_param, $keyword_param);
    }

    /**
     * Print nested tree of pages.
     *
     * @param int $parent
     * @param bool $link
     * @param bool $active_ids
     * @param bool $active_code
     * @param bool $remove_ids
     * @param bool $removed_ids_code
     * @param bool $ul_class_name
     * @param bool $include_first
     *
     * @example
     * <pre>
     * // Example Usage:
     * $pt_opts = array();
     * $pt_opts['link'] = "{title}";
     * $pt_opts['list_tag'] = "ol";
     * $pt_opts['list_item_tag'] = "li";
     * pages_tree($pt_opts);
     * </pre>
     * @example
     * <pre>
     * // Example Usage to make <select> with <option>:
     * $pt_opts = array();
     * $pt_opts['link'] = "{title}";
     * $pt_opts['list_tag'] = " ";
     * $pt_opts['list_item_tag'] = "option";
     * $pt_opts['active_ids'] = $data['parent'];
     * $pt_opts['active_code_tag'] = '   selected="selected"  ';
     * $pt_opts['ul_class'] = 'nav';
     * $pt_opts['li_class'] = 'nav-item';
     *  pages_tree($pt_opts);
     * </pre>
     * @example
     * <pre>
     * // Other opltions
     * $pt_opts['parent'] = "8";
     * $pt_opts['include_first'] =  true; //includes the parent in the tree
     * $pt_opts['id_prefix'] = 'my_id';
     * </pre>
     *
     */
    public function pages_tree($params)
    {
        return $this->pagesTree($params);
    }

    /**
     * Defines all constants that are needed to parse the page layout.
     *
     * It accepts array or $content that must have  $content['id'] set
     *
     * @param array|bool $content
     *
     * @option     integer  "id"   [description]
     * @option     string "content_type" [description]
     * @example
     * <code>
     *  Define constants for some page
     *  $ref_page = $this->get_by_id(1);
     *  $this->define_constants($ref_page);
     *  print PAGE_ID;
     *  print POST_ID;
     *  print CATEGORY_ID;
     *  print MAIN_PAGE_ID;
     *  print DEFAULT_TEMPLATE_DIR;
     *  print DEFAULT_TEMPLATE_URL;
     * </code>
     *
     * @const      PAGE_ID Defines the current page id
     * @const      POST_ID Defines the current post id
     * @const      CATEGORY_ID Defines the current category id if any
     * @const      ACTIVE_PAGE_ID Same as PAGE_ID
     * @const      CONTENT_ID current post or page id
     * @const      MAIN_PAGE_ID the parent page id
     * @const      DEFAULT_TEMPLATE_DIR the directory of the site's default template
     * @const      DEFAULT_TEMPLATE_URL the url of the site's default template
     *
     */
    public function define_constants($content = false)
    {
        return $this->app->template_manager->defineConstants($content);
    }


    /**
     *  Get the first parent that has layout.
     */
    public function get_inherited_parent($content_id)
    {

        return app()->content_repository->getInheritedParent($content_id);
    }


    public function get_parents($id = 0)
    {
        return app()->content_repository->getParents($id);

    }


    public function breadcrumb($params = false)
    {


        return $this->breadcrumbLinks->get($params);
    }

    /**
     * Gets a link for given content id.
     *
     * If you don't pass id parameter it will try to use the current page id
     *
     * @param int $id The $id The id of the content
     *
     * @return string The url of the content
     *
     * @see     post_link()
     * @see     page_link()
     * @see     content_link()
     *
     * @example
     * <code>
     * print $this->link($id=1);
     * </code>
     */
    public function link($id = 0)
    {
        if (is_array($id)) {
            extract($id);
        }

        if ($id == false or $id == 0) {
            $id = content_id();
        }

        if ($id == 0) {
            return $this->app->url_manager->site();
        }

        $link = $this->get_by_id($id);
        if (!$link) {
            return;
        }
         $site_url = $this->app->url_manager->site();

        if (isset($link['is_home']) and intval($link['is_home']) == 1) {
            return $site_url;
        }

        if (!isset($link['url']) or (is_string($link['url']) and $link['url'] == '')) {
            $link = $this->get_by_url($id);
        }
        if (!$link) {
            return;
        }

        $permalinkGenerated = $this->app->permalink_manager->link($link['id'], 'content');

        if ($permalinkGenerated) {
            $link['url'] = $permalinkGenerated;

            if (!stristr($link['url'], $site_url)) {
                $link = site_url($link['url']);
            } else {
                $link = ($link['url']);
            }
            return $link;
        }

    }

    public function create_link($contentType = 'page')
    {

        if ($contentType == 'product') {
            return admin_url('products/create');
        }

        if ($contentType == 'post') {
            return admin_url('posts/create');
        }

        if ($contentType == 'page') {
            return admin_url('pages/create');
        }

        return admin_url('content/create');
    }

    public function edit_link($id = 0)
    {
        $content = $this->get_by_id($id);

        if (isset($content['content_type']) && $content['content_type'] == 'product') {
            // return route('admin.product.edit', $id);
            return admin_url('products/' . $id . '/edit');
        }

        if (isset($content['content_type']) && $content['content_type'] == 'post') {
            // return route('admin.post.edit', $id);
            return admin_url('posts/' . $id . '/edit');
        }

        if (isset($content['content_type']) && $content['content_type'] == 'page') {
            return admin_url('pages/' . $id . '/edit');
            //  return route('admin.page.edit', $id);
        }

        // return route('admin.content.edit', $id);
        return admin_url('content/' . $id . '/edit');

    }

    public function save_edit($post_data)
    {
        return $this->helpers->save_from_live_edit($post_data);
    }

    /**
     * Returns the homepage as array.
     *
     * @category Content
     */
    public function homepage()
    {
        $get = array();
        $get['is_home'] = 1;
        $get['single'] = 1;

        $data = app()->content_repository->getByParams($get);

        return $data;
    }


    public function save_content($data, $delete_the_cache = true)
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }

        $this->app->event_manager->trigger('content.manager.before.save', $data);
        $data_to_save = $data;
        $save = $this->crud->save($data);
        $id = $save;
        if (isset($data_to_save['add_content_to_menu']) and is_array($data_to_save['add_content_to_menu'])) {
            foreach ($data_to_save['add_content_to_menu'] as $menu_id) {
                $ids_to_save = $save;
                if (!app()->menu_manager->is_in_menu($menu_id, $ids_to_save)) {
                    $this->helpers->add_content_to_menu($ids_to_save, $menu_id);
                }
            }
        }
        $after_save = $data_to_save;
        $after_save['id'] = $id;

        $this->app->event_manager->trigger('content.manager.after.save', $after_save);
        event_trigger('mw_save_content', $save);

        return $save;
    }

    public function custom_fields($content_id, $full = true, $field_type = false)
    {
        return $this->getCustomFields($content_id, $full, $field_type);
    }

    public function save_content_field($data, $delete_the_cache = true)
    {
        return $this->helpers->save_content_field($data, $delete_the_cache);
    }

    public function edit_field($data)
    {
        return $this->crud->get_edit_field($data);
    }

    public function prev_content($content_id = false)
    {
        return $this->next_content($content_id, $mode = 'prev');
    }

    public function next_content($content_id = false, $mode = 'next', $content_type = false)
    {
        if ($content_id == false) {
            if (defined('POST_ID') and POST_ID != 0) {
                $content_id = POST_ID;
            } elseif (defined('PAGE_ID') and PAGE_ID != 0) {
                $content_id = PAGE_ID;
            } elseif (defined('MAIN_PAGE_ID') and MAIN_PAGE_ID != 0) {
                $content_id = MAIN_PAGE_ID;
            }
        }
        $category_id = false;
        if (defined('CATEGORY_ID') and CATEGORY_ID != 0) {
            $category_id = CATEGORY_ID;
        }

        if ($content_id == false) {
            return false;
        } else {
            $content_id = intval($content_id);
        }
        $contentData = $this->get_by_id($content_id);
        if ($contentData == false) {
            return false;
        }

        if ($contentData['position'] == null) {
            $contentData['position'] = 0;
        }

        $query = \Modules\Content\Models\Content::query()->select('content.*');
        $categories = array();
        $params = array();

        $parent_id = false;
        if (isset($contentData['parent']) and $contentData['parent'] > 0) {
            $parent_id = $contentData['parent'];
        }

        if ($content_type) {
            if (defined('PAGE_ID') and PAGE_ID != 0) {
                $parent_id = PAGE_ID;
            }
        } elseif (isset($contentData['content_type'])) {
            $content_type = $contentData['content_type'];
        }

        if (isset($contentData['content_type']) and $contentData['content_type'] != 'page') {

            if (trim($mode) == 'prev') {
                $query->orderBy('position', 'desc');
                $query->where('position', '<', $contentData['position']);
            } else {
                $query->orderBy('position', 'asc');
                $query->where('position', '>', $contentData['position']);
            }

            $cats = $this->app->category_manager->get_for_content($content_id);
            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    $categories[] = $cat['id'];
                }
                $query->whereCategoryIds($categories);
            }

        } else {

            if (isset($contentData['position']) and $contentData['position'] > 0) {
                if (trim($mode) == 'prev') {
                    $query->where('position', '>', $contentData['position']);
                } else {
                    $query->where('position', '<', $contentData['position']);
                }
            }

            if (trim($mode) == 'prev') {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'asc');
            }
        }

        $params['exclude_ids'] = array($content_id);

        if ($parent_id) {
            $query->whereParent($parent_id);
        }

        $query->whereContentType($content_type);
        $query->whereIsActive(1);
        $query->whereIsDeleted(0);

        $response = [];
        $get = $query->first();
        if ($get != null) {
            $response = $get->toArray();
        }

        if (is_array($response)) {
            return $response;
        } else {
            return false;
        }
    }


    public function set_unpublished($params)
    {
        return $this->setUnpublished($params);
    }


    public function set_published($params)
    {
        return $this->setPublished($params);
    }

    public function save_content_data_field($data, $delete_the_cache = true)
    {
        return $this->helpers->save_content_field($data, $delete_the_cache);
    }

    public function get_pages($params = false)
    {
        return $this->getPages($params);
    }

    public function get_posts($params = false)
    {
        return $this->getPosts($params);
    }


    public function get_products($params = false)
    {
        return $this->getProducts($params);
    }

    public function title($id)
    {


        if ($id == false or $id == 0) {
            $id = $this->content_id();
        }
        $content = $this->get_by_id($id);
        if (isset($content['title'])) {
            return $content['title'];
        }
    }


    public function description($id)
    {
        $descr = false;

        if ($id == false or $id == 0) {
            $id = $this->content_id();
        }
        $meta = $this->get_by_id($id);
        if (!$meta) {
            return;
        }

        if (isset($meta['description']) and $meta['description'] != '') {
            $descr = $meta['description'];
        } else if (isset($meta['content_meta_description']) and $meta['content_meta_description'] != '') {
            $descr = $meta['content_meta_description'];
        } else if (isset($meta['content_body']) and $meta['content_body'] != '') {
            $descr = strip_tags($meta['content_body']);
        } else if (isset($meta['content']) and $meta['content'] != '') {
            $descr = strip_tags($meta['content']);
        }

        if ($descr) {
            $descr = trim($descr);
        }
        if ($descr) {
            if ($descr == 'mw_saved_inner_edit_from_parent_edit_field') {
                $descr_from_edit_field = $this->app->content_manager->edit_field("rel_type=content&rel_id=" . $id);
                if ($descr_from_edit_field) {
                    $descr_from_edit_field = trim(strip_tags($descr_from_edit_field));
                }
                if ($descr_from_edit_field) {
                    $descr = trim($descr_from_edit_field);
                }

            }
        }
        if ($descr) {
            return $descr;
        }
    }


    public function get_related_content_ids_for_content_id($content_id = false)
    {
        return $this->contentRepository->getRelatedContentIds($content_id);

    }


}
