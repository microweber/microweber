<?php

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use MicroweberPackages\App\Http\Middleware\ApiAuth;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;
use MicroweberPackages\App\Managers\Helpers\VerifyCsrfTokenHelper;
use MicroweberPackages\App\Traits\LiveEditTrait;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Install\Http\Controllers\InstallController;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;


class FrontendController extends Controller
{
    use LiveEditTrait;

    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;
    public $websiteOptions = [];
    public $return_data = false;
    public $content_data = false;
    public $page_url = false;
    public $create_new_page = false;
    public $render_this_url = false;
    public $isolate_by_html_id = false;
    public $functions = array();
    public $page = array();
    public $params = array();
    public $vars = array();


    public $debugbarEnabled = false;


    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        event_trigger('mw.init');

        $this->websiteOptions = app()->option_repository->getWebsiteOptions();


    }

    public function index($request_params = [])
    {
        $is_installed = mw_is_installed();
        if (!$is_installed) {
            $installer = new InstallController($this->app);
            return $installer->index();
        } elseif (defined('MW_VERSION')) {
            $config_version = Config::get('microweber.version');
            $app_version = false;
            if (isset($this->websiteOptions['app_version']) and $this->websiteOptions['app_version']) {
                $app_version = $this->websiteOptions['app_version'];
            }
            if ($config_version != MW_VERSION) {
                $this->app->update->post_update(MW_VERSION);
            } else if ($app_version != MW_VERSION) {
                $this->app->update->post_update(MW_VERSION);
            }
        }

        $this->debugbarEnabled = false;
        if (class_exists('\Debugbar', false)) {
            $this->debugbarEnabled = \Debugbar::isEnabled();;
        }

        if (\Config::get('microweber.force_https') && !is_cli() && !is_https()) {
            $https = str_ireplace('http://', 'https://', url_current());
            return mw()->url_manager->redirect($https);
        }

        return $this->frontend($request_params);
    }



    public function frontend($request_params = [])
    {
        if (isset($_GET['debug'])) {
            if ($this->app->make('config')->get('app.debug')) {
                DB::enableQueryLog();
            }
        }

        if(empty($request_params) and (!empty($_REQUEST))){
            $request_params = $_REQUEST;
        }

        event_trigger('mw.controller.index');

        $isAjax = app()->url_manager->is_ajax();
        $urlString = app()->url_manager->string();

        if ($this->render_this_url == false and $isAjax == false) {
            $page_url = $urlString;

        } elseif ($this->render_this_url == false and $isAjax == true) {
            $page_url = $urlString;
        } else {
            $page_url = $this->render_this_url;
            $this->render_this_url = false;
        }
        if ($this->page_url != false) {
            $page_url = $this->page_url;
        }

        if (strtolower($page_url) == 'index.php') {
            $page_url = '';
        }

        if ($this->create_new_page == true and $this->page_url != false) {
            $page_url = $this->page_url;
        }

        $favicon_image = false;

        if (isset($this->websiteOptions['favicon_image'])) {
            $favicon_image = $this->websiteOptions['favicon_image'];
        }

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }
        if ($favicon_image) {
            mw()->template->head('<link rel="shortcut icon" href="' . $favicon_image . '" />');
        }


        $animations = get_option( 'animations-global', 'template');
        if ($animations) {
            // adds the animations definitions to the head
            mw()->template->head('<script id="template-animations-data">mw.__pageAnimations = ' . $animations . '</script>');
        }




        $page = false;

        if ($page == false and !empty($this->page)) {
            $page = $this->page;
        }

        $page_url = rtrim($page_url, '/');
        $is_admin = app()->user_manager->is_admin();
        $page_url_orig = $page_url;
        $simply_a_file = false;
        $show_404_to_non_admin = false;
        $enable_full_page_cache = false;

        // if this is a file path it will load it
        if (isset($request_params['view'])) {
            $is_custom_view = $request_params['view'];
        } else {
            $is_custom_view = app()->url_manager->param('view');
            if ($is_custom_view and $is_custom_view != false) {
                $is_custom_view = sanitize_path($is_custom_view);
                $page_url = app()->url_manager->param_unset('view', $page_url);
            }
        }

        $is_editmode = app()->url_manager->param('editmode');

        $is_no_editmode = app()->url_manager->param('no_editmode');
        $is_quick_edit = app()->url_manager->param('mw_quick_edit');
        $back_to_editmode = app()->user_manager->session_get('back_to_editmode');
        if (!$back_to_editmode) {
            if (isset($_COOKIE['mw-back-to-live-edit']) and $is_admin) {
                $back_to_editmode = $_COOKIE['mw-back-to-live-edit'];
            }
        }


        if ($is_quick_edit != false) {
            $page_url = app()->url_manager->param_unset('mw_quick_edit', $page_url);
        }
        $is_preview_template = app()->url_manager->param('preview_template');
        if (!$is_preview_template) {
            $is_preview_template = false;
            if ($this->return_data == false) {
                if (!defined('MW_FRONTEND')) {
                    define('MW_FRONTEND', true);
                }
            }

            if (mw()->user_manager->session_id() and $is_editmode and $is_no_editmode == false) {
                if ($is_editmode == 'n') {
                    $is_editmode = false;
                    $page_url = app()->url_manager->param_unset('editmode', $page_url);
                    app()->user_manager->session_set('back_to_editmode', true);
                    app()->user_manager->session_set('editmode', false);

                    return app()->url_manager->redirect(app()->url_manager->site_url($page_url));
                } else {
                    $editmode_sess = app()->user_manager->session_get('editmode');
                    $page_url = app()->url_manager->param_unset('editmode', $page_url);

                    if ($is_admin == true) {
                        if ($editmode_sess == false) {
                            app()->user_manager->session_set('editmode', true);
                            app()->user_manager->session_set('back_to_editmode', false);
                            $is_editmode = false;
                        }

                        return app()->url_manager->redirect(app()->url_manager->site_url($page_url));
                    } else {

                        $is_editmode = false;
                    }
                }
            }

            if (mw()->user_manager->session_id() and !$is_no_editmode) {
                $is_editmode = app()->user_manager->session_get('editmode');

            } else {
                $is_editmode = false;
                $page_url = app()->url_manager->param_unset('no_editmode', $page_url);
            }
        } else {
            $is_editmode = false;
            $page_url = app()->url_manager->param_unset('preview_template', $page_url);
        }
        if ($is_quick_edit == true) {
            $is_editmode = true;
        }


        $preview_module = false;
        $preview_module_template = false;
        $is_preview_module_skin = false;
        $preview_module_id = false;
        $template_relative_layout_file_from_url = false;
        $is_preview_module = app()->url_manager->param('preview_module');

        if ($is_preview_module != false) {
            if (app()->user_manager->is_admin()) {
                $is_preview_module = module_name_decode($is_preview_module);
                if (is_module($is_preview_module)) {
                    $is_preview_module_skin = app()->url_manager->param('preview_module_template');
                    $preview_module_id = app()->url_manager->param('preview_module_id');
                    $preview_module = $is_preview_module;
                    if ($is_preview_module_skin != false) {
                        $preview_module_template = module_name_decode($is_preview_module_skin);
                        $is_editmode = false;
                    }
                }
            }
        }

        $is_layout_file = app()->url_manager->param('preview_layout');
        if (!$is_layout_file) {
            $is_layout_file = false;
        } else {
            $page_url = app()->url_manager->param_unset('preview_layout', $page_url);
        }

        if (isset($request_params['content_id']) and intval($request_params['content_id']) != 0) {
            $page = $this->app->content_manager->get_by_id($request_params['content_id']);
        }

        $output_cache_timeout = false;


        if ($is_quick_edit or $is_preview_template == true or isset($request_params['isolate_content_field']) or $this->create_new_page == true) {
            if (isset($request_params['content_id']) and intval($request_params['content_id']) != 0) {
                $page = $this->app->content_manager->get_by_id($request_params['content_id']);
            } else {

                $page['id'] = 0;
                $page['content_type'] = 'page';
                if (isset($request_params['content_type'])) {
                    $page['content_type'] = app()->database_manager->escape_string($request_params['content_type']);
                }

                if (isset($request_params['subtype'])) {
                    $page['subtype'] = app()->database_manager->escape_string($request_params['subtype']);
                }
                template_var('new_content_type', $page['content_type']);
                $page['parent'] = '0';

                if (isset($request_params['parent_id']) and $request_params['parent_id'] != 0) {
                    $page['parent'] = intval($request_params['parent_id']);
                }

                //$page['url'] = app()->url_manager->string();
                if (isset($is_preview_template) and $is_preview_template != false) {
                    $page['active_site_template'] = $is_preview_template;
                } else {
                }
                if (isset($is_layout_file) and $is_layout_file != false) {
                    $page['layout_file'] = $is_layout_file;
                }
                if (isset($request_params['inherit_template_from']) and $request_params['inherit_template_from'] != 0) {
                    $page['parent'] = intval($request_params['inherit_template_from']);
                    $inherit_from = $this->app->content_manager->get_by_id($request_params['inherit_template_from']);

                    //$page['parent'] =  $inherit_from ;
                    if (isset($inherit_from['layout_file']) and $inherit_from['layout_file'] == 'inherit') {
                        $inherit_from_id = $this->app->content_manager->get_inherited_parent($inherit_from['id']);
                        $inherit_from = $this->app->content_manager->get_by_id($inherit_from_id);
                    }

                    if (is_array($inherit_from) and isset($inherit_from['active_site_template'])) {
                        $page['active_site_template'] = $inherit_from['active_site_template'];
                        $is_layout_file = $page['layout_file'] = $inherit_from['layout_file'];
                    }
                }
                if (isset($request_params['content_type']) and $request_params['content_type'] != false) {
                    $page['content_type'] = $request_params['content_type'];
                }

                if ($this->content_data != false) {
                    $page = $this->content_data;
                }
                template_var('new_page', $page);
            }
        } else {

            $enable_full_page_cache = $this->websiteOptions['enable_full_page_cache'] == 'y';
            //   $enable_full_page_cache = 1;
            if ($is_editmode == false
                and !$is_preview_template
                and !$is_no_editmode
                and !$is_preview_module
                and $this->isolate_by_html_id == false
                and !isset($request_params['isolate_content_field'])
                and !isset($request_params['content_id'])
                and !isset($request_params['embed_id'])
                and !is_cli()
                and !defined('MW_API_CALL')
                and !defined('MW_NO_SESSION')
            ) {


                if (!$back_to_editmode and !$is_editmode and empty($_GET)) {
                    if ($enable_full_page_cache) {
                        $output_cache_timeout = 12000;
                    }
                }

            }
        }
        if (isset($is_preview_template) and $is_preview_template != false) {
            if (!defined('MW_NO_SESSION')) {
                define('MW_NO_SESSION', true);
            }
        }

        if (isset($request_params['recart']) and $request_params['recart'] != false) {
            event_trigger('recover_shopping_cart', $request_params['recart']);
        }
        if (!defined('MW_NO_OUTPUT_CACHE')) {
            if (!$back_to_editmode and !$is_editmode and $enable_full_page_cache and $output_cache_timeout != false and isset($_SERVER['REQUEST_URI']) and $_SERVER['REQUEST_URI']) {
                $compile_assets = \Config::get('microweber.compile_assets');

                $output_cache_content = false;
                $output_cache_id = 'full_page_cache_' . __FUNCTION__ . crc32(MW_VERSION . intval($compile_assets) . intval(is_https()) . $_SERVER['REQUEST_URI'] . current_lang() . site_url());
                $output_cache_group = 'global';
                $output_cache_content_data = $this->app->cache_manager->get($output_cache_id, $output_cache_group, $output_cache_timeout);

                if ($output_cache_content_data and isset($output_cache_content_data['layout']) and isset($output_cache_content_data['time'])) {
                    $output_cache_content = $output_cache_content_data['layout'];
                }

                if ($output_cache_content != false and !str_contains($output_cache_content, 'image-generate-tn-request')) {
                    return \Response::make($output_cache_content)
                        ->header('Cache-Control', 'public, max-age=10800, pre-check=10800')
                        ->header('Last-Modified', \Carbon::parse($output_cache_content_data['time'])->toRfc850String())
                        ->header('Pragma', 'public')
                        ->setEtag(md5($output_cache_id))
                        ->header('X-App-Full-Page-Cache', true);
                }

            }
        }
        $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
         if($is_preview_template){
             $the_active_site_template = $is_preview_template;

             if (!defined('ACTIVE_SITE_TEMPLATE')) {
                 $content['active_site_template'] = $is_preview_template;

                 $this->app->content_manager->define_constants($content);
             }
         }
        $date_format = $this->websiteOptions['date_format'];
        if ($date_format == false) {
            $date_format = 'Y-m-d H:i:s';
        }

        $maintenance_mode = $this->websiteOptions['maintenance_mode'];


        if ($maintenance_mode == 'y' && !is_admin()) {
            if (!defined('ACTIVE_SITE_TEMPLATE')) {
                $this->app->content_manager->define_constants();
            }
            $maintenance_template = TEMPLATES_DIR . ACTIVE_SITE_TEMPLATE . DS . '503.php';
            $maintenance_mode_text = $this->websiteOptions['maintenance_mode_text'];
            $content_503 = 'Error 503 The website is under maintenance.';

            if ($maintenance_mode_text and trim($maintenance_mode_text) != '') {
                $content_503 = $maintenance_mode_text;
            }

            if (is_file($maintenance_template)) {
                $content_503 = new View($maintenance_template);
                $content_503 = $content_503->__toString();
            }
            $response = \Response::make($content_503);
            $response->setStatusCode(503);
            return $response;
        }

        if ($page == false or $this->create_new_page == true) {
            if (trim($page_url) == '' and $preview_module == false) {
                $page = $this->app->content_manager->homepage();
            } else {

                $page_exact = false;
                $slug_page = $this->app->permalink_manager->slug($page_url, 'page');
                $slug_post = $this->app->permalink_manager->slug($page_url, 'post');
                $slug_category = $this->app->permalink_manager->slug($page_url, 'category');

                $found_mod = false;
                $try_content = false;
                $redirectLink = false;
                $multilanguageIsActive = MultilanguageHelpers::multilanguageIsEnabled();

                if ($slug_post) {
                    $page = $this->app->content_manager->get_by_url($slug_post);
                    $page_exact = $this->app->content_manager->get_by_url($slug_post, true);
                    if ($page) {
                        if ($multilanguageIsActive) {
                            if ($page['url'] !== $slug_post) {
                                $redirectLink = content_link($page['id']);
                            }
                        }
                    }
                }

                if ($slug_page and !$page) {

                    $page = $this->app->content_manager->get_by_url($page_url);
                    $page_exact = $this->app->content_manager->get_by_url($page_url, true);

                }

                if ($slug_category) {
                    $cat = $this->app->category_manager->get_by_url($slug_category);
                    if ($cat) {
                        if ($slug_category and !$page) {
                            $content_for_cat = $this->app->category_manager->get_page($cat['id']);
                            if ($content_for_cat) {
                                $page = $page_exact = $content_for_cat;
                            }
                        }
                        if ($multilanguageIsActive) {
                            if ($redirectLink == false) {
                                if ($cat['url'] !== $slug_category) {
                                    $redirectLink = category_link($cat['id']);
                                }
                            }
                        }
                    }
                }

                if ($redirectLink && $multilanguageIsActive) {
                    if (urldecode(url_current(true)) !== $redirectLink) {
                        return redirect($redirectLink);
                    }
                }

                $page_url_segment_1 = app()->url_manager->segment(0, $page_url);
                if ($preview_module != false) {
                    $page_url = $preview_module;
                }
                if ($the_active_site_template == false or $the_active_site_template == '') {
                    $the_active_site_template = 'default';
                }

                if ($page_exact == false and $found_mod == false and $this->app->module_manager->is_installed($page_url) and $page_url != 'settings' and $page_url != 'admin') {
                    $found_mod = true;
                }

                if (!$page_exact and !$page and stristr($page_url, 'index.php')) {
                    // prevent loading of non exisitng page at index.php/somepage
                    $response = \Response::make('Error 404 The webpage cannot be found');
                    $response->setStatusCode(404);
                    return $response;
                }


                // if ($found_mod == false) {
                if (empty($page)) {
                    $the_new_page_file = false;
                    $page_url_segment_1 = app()->url_manager->segment(0, $page_url);

                    $td = templates_path() . $page_url_segment_1;
                    $td_base = $td;

                    $page_url_segment_2 = app()->url_manager->segment(1, $page_url);
                    $directly_to_file = false;
                    $page_url_segment_3 = $all_url_segments = app()->url_manager->segment(-1, $page_url);
                    if (!$page_url_segment_1) {
                        $page_url_segment_1 = $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                    }
                    $td_base = templates_path() . $the_active_site_template . DS;

                    $page_url_segment_3_str = implode(DS, $page_url_segment_3);

                    if ($page_url_segment_3_str != '') {
                        $page_url_segment_3_str = rtrim($page_url_segment_3_str, DS);
                        $page_url_segment_3_str = rtrim($page_url_segment_3_str, '\\');
                        $page_url_segment_3_str_copy = $page_url_segment_3_str;

                        $is_ext = get_file_extension($page_url_segment_3_str);
                        if ($is_ext == false or $is_ext != 'php') {
                            $page_url_segment_3_str = $page_url_segment_3_str . '.php';
                        }

                        $td_f = $td_base . DS . $page_url_segment_3_str;
                        $td_fd = $td_base . DS . $page_url_segment_3_str_copy;
                        $td_fd2 = $td_base . DS . $page_url_segment_3[0];
                        $td_fd2_file = $td_fd2 . '.php';
                        //

                        if (is_file($td_fd2_file)) {
                            $the_new_page_file = $td_fd2_file;
                            $simply_a_file = $directly_to_file = $td_fd2_file;
                        } else if (is_file($td_f)) {
                            $the_new_page_file = $page_url_segment_3_str;
                            $simply_a_file = $directly_to_file = $td_f;
                        } else {
                            if (is_dir($td_fd)) {
                                $td_fd_index = $td_fd . DS . 'index.php';
                                if (is_file($td_fd_index)) {
                                    $the_new_page_file = $td_fd_index;
                                    $simply_a_file = $directly_to_file = $td_fd_index;
                                }
                            } else {
                                $is_ext = get_file_extension($td_fd);
                                if ($is_ext == false or $is_ext != 'php') {
                                    $td_fd = $td_fd . '.php';
                                }
                                $is_ext = get_file_extension($td_fd2);
                                if ($is_ext == false or $is_ext != 'php') {
                                    $td_fd2 = $td_fd2 . '.php';
                                }
                                if (is_file($td_fd)) {
                                    $the_new_page_file = $td_fd;
                                    $simply_a_file = $directly_to_file = $td_fd;
                                } elseif (is_file($td_fd2)) {
                                    $the_new_page_file = $td_fd2;
                                    $simply_a_file = $directly_to_file = $td_fd2;
                                } else {
                                    $td_basedef = templates_path() . 'default' . DS . $page_url_segment_3_str;
                                    if (is_file($td_basedef)) {
                                        $the_new_page_file = $td_basedef;
                                        $simply_a_file = $directly_to_file = $td_basedef;
                                    }
                                }
                            }
                        }
                    }
                    $fname1 = 'index.php';
                    $fname2 = $page_url_segment_2 . '.php';
                    $fname3 = $page_url_segment_2;


                    $tf1 = $td . DS . $fname1;
                    $tf2 = $td . DS . $fname2;
                    $tf3 = $td . DS . $fname3;


                    if ($directly_to_file == false and is_dir($td)) {
                        if (is_file($tf1)) {
                            $simply_a_file = $tf1;
                            $the_new_page_file = $fname1;
                        }

                        if (is_file($tf2)) {
                            $simply_a_file = $tf2;
                            $the_new_page_file = $fname2;
                        }
                        if (is_file($tf3)) {
                            $simply_a_file = $tf3;
                            $the_new_page_file = $fname3;
                        }

                        if (($simply_a_file) != false) {
                            $simply_a_file = sanitize_path($simply_a_file);
                            $simply_a_file = normalize_path($simply_a_file, false);
                        }
                    }


                    if ($simply_a_file == false) {

                        //$page = $this->app->content_manager->homepage();
                        $page = false;
                        if (!is_array($page)) {


                            $page = array();

                            $page['id'] = 0;
                            $page['content_type'] = 'page';
                            $page['parent'] = '0';
                            $page['url'] = app()->url_manager->string();
                            //  $page['active_site_template'] = $page_url_segment_1;
                            $page['simply_a_file'] = 'clean.php';
                            $page['layout_file'] = 'clean.php';
                            $show_404_to_non_admin = true;

                            $enable_full_page_cache = false;


                            if ($show_404_to_non_admin) {
//                                $content_from_event = event_trigger('mw.frontend.404', $page);
//                                if($content_from_event and !empty($content_from_event)){
//                                    foreach ($content_from_event as $content_from_event_item){
//                                        $page = array_merge($page,$content_from_event_item);
//                                      //  $page = $content_from_event_item;
//                                        //$content = array_merge($content,$content_from_event_item);
//                                    }
//                                }


                            }


                            if ($all_url_segments) {
                                $page_url_segments_str_for_file = implode('/', $page_url_segment_3);
                                $file1 = $page_url_segments_str_for_file . '.php';
                                $file2 = 'layouts' . DS . $page_url_segments_str_for_file . '.php';
                                $render_file_temp = $td_base . $file1;
                                $render_file_temp2 = $td_base . $file2;

                                if (is_file($render_file_temp)) {
                                    $page['simply_a_file'] = $file1;
                                    $page['layout_file'] = $file1;
                                } else if (is_file($render_file_temp2)) {
                                    $page['simply_a_file'] = $file2;
                                    $page['layout_file'] = $file2;
                                } elseif ($found_mod) {
                                    $page['id'] = 0;
                                    $page['content_type'] = 'page';
                                    $page['parent'] = '0';
                                    $page['url'] = app()->url_manager->string();
                                    $page['active_site_template'] = $the_active_site_template;

                                    $file_for_module = 'module.php';
                                    $render_file_for_module = $td_base . $file_for_module;
                                    if (is_file($render_file_for_module)) {
                                        $file_for_module = 'module.php';
                                    } else if (is_file($render_file_for_module)) {
                                        $file_for_module = 'clean.php';
                                    }


                                    template_var('no_edit', 1);

                                    $mod_params = '';
                                    if ($preview_module_template != false) {
                                        $mod_params = $mod_params . " template='{$preview_module_template}' ";
                                    }
                                    if ($preview_module_id != false) {
                                        $mod_params = $mod_params . " id='{$preview_module_id}' ";
                                    }
                                    $found_mod = $page_url;
                                    $page['simply_a_file'] = $file_for_module;
                                    $page['layout_file'] = $file_for_module;
                                    $page['content'] = '<module type="' . $page_url . '" ' . $mod_params . '  />';

                                    //  $page['simply_a_file'] = 'clean.php';
                                    $page['layout_file'] = $file_for_module;
                                    template_var('content', $page['content']);

                                    template_var('new_page', $page);
                                    $show_404_to_non_admin = false;
                                }
                            }


                        } elseif (is_array($page_url_segment_3)) {

                            foreach ($page_url_segment_3 as $mvalue) {
                                if ($found_mod == false and $this->app->module_manager->is_installed($mvalue)) {
                                    $found_mod = true;
                                    $page['id'] = 0;
                                    $page['content_type'] = 'page';
                                    $page['parent'] = '0';
                                    $page['url'] = app()->url_manager->string();
                                    $page['active_site_template'] = $the_active_site_template;
                                    $page['content'] = '<module type="' . $mvalue . '" />';
                                    $page['simply_a_file'] = 'clean.php';
                                    $page['layout_file'] = 'clean.php';
                                    template_var('content', $page['content']);

                                    template_var('new_page', $page);
                                    $enable_full_page_cache = false;
                                    $show_404_to_non_admin = false;
                                }
                            }
                        }
                    } else {

                        if (!is_array($page)) {
                            $page = array();
                        }
                        $page['id'] = 0;

                        if (isset($page_data) and isset($page_data['id'])) {

                            //  $page['id'] = $page_data['id'];
                        }

                        $page['content_type'] = 'page';
                        $page['parent'] = '0';
                        $page['url'] = app()->url_manager->string();

                        $page['active_site_template'] = $the_active_site_template;

                        $page['layout_file'] = $the_new_page_file;
                        $page['simply_a_file'] = $simply_a_file;
                        template_var('new_page', $page);
                        template_var('simply_a_file', $simply_a_file);
                        $show_404_to_non_admin = false;

                        $enable_full_page_cache = false;

                    }
                }
                // }
            }
        }

        if ($show_404_to_non_admin and !$is_admin) {
            $page['simply_a_file'] = '404.php';
            $page['layout_file'] = '404.php';

        }

        if (isset($page['id']) AND $page['id'] != 0) {

            // if(!isset($page['layout_file']) or $page['layout_file'] == false){
            $page = $this->app->content_manager->get_by_id($page['id']);

            // }
            if ($page['content_type'] == 'post' and isset($page['parent'])) {
                $content = $page;
                $page = $this->app->content_manager->get_by_id($page['parent']);
            } else {
                $content = $page;
            }
        } else {
            $content = $page;
        }
        if (isset($content['created_at']) and trim($content['created_at']) != '') {
            $content['created_at'] = date($date_format, strtotime($content['created_at']));
        }

        if (isset($content['updated_at']) and trim($content['updated_at']) != '') {
            $content['updated_at'] = date($date_format, strtotime($content['updated_at']));
        }

        if ($is_preview_template != false) {
            $is_preview_template = str_replace('____', DS, $is_preview_template);
            $is_preview_template = sanitize_path($is_preview_template);

            $content['active_site_template'] = $is_preview_template;
        }

        if ($is_layout_file != false and $is_admin == true) {
            $is_layout_file = str_replace('____', DS, $is_layout_file);
            if ($is_layout_file == 'inherit') {
                if (isset($request_params['inherit_template_from']) and intval($request_params['inherit_template_from']) != 0) {
                    $inherit_layout_from_this_page = $this->app->content_manager->get_by_id($request_params['inherit_template_from']);

                    if (isset($inherit_layout_from_this_page['layout_file']) and $inherit_layout_from_this_page['layout_file'] != 'inherit') {
                        $is_layout_file = $inherit_layout_from_this_page['layout_file'];
                    }

                    if (isset($inherit_layout_from_this_page['layout_file']) and $inherit_layout_from_this_page['layout_file'] != 'inherit') {
                        $is_layout_file = $inherit_layout_from_this_page['layout_file'];
                    }
                }
            }
            $content['layout_file'] = $is_layout_file;
        }

        if ($is_custom_view and $is_custom_view != false) {
            $content['custom_view'] = $is_custom_view;
        }

        if (isset($content['is_active']) and ($content['is_active'] == 'n' or $content['is_active'] == 0)) {
            if (app()->user_manager->is_admin() == false) {
                $page_non_active = array();
                $page_non_active['id'] = 0;
                $page_non_active['content_type'] = 'page';
                $page_non_active['parent'] = '0';
                $page_non_active['url'] = app()->url_manager->string();
                $page_non_active['content'] = 'This page is not published!';
                $page_non_active['simply_a_file'] = 'clean.php';
                $page_non_active['layout_file'] = 'clean.php';
                $page_non_active['page_non_active'] = true;
                template_var('content', $page_non_active['content']);
                $content = $page_non_active;
            }
        } elseif (isset($content['is_deleted']) and $content['is_deleted'] == 1) {
            if (app()->user_manager->is_admin() == false) {
                $page_non_active = array();
                $page_non_active['id'] = 0;
                $page_non_active['content_type'] = 'page';
                $page_non_active['parent'] = '0';
                $page_non_active['url'] = app()->url_manager->string();
                $page_non_active['content'] = 'This page is deleted!';
                $page_non_active['simply_a_file'] = 'clean.php';
                $page_non_active['layout_file'] = 'clean.php';
                $page_non_active['page_is_deleted'] = true;
                template_var('content', $page_non_active['content']);
                $content = $page_non_active;
            }
        }

        if (isset($content['require_login']) and $content['require_login'] == 1) {
            if (app()->user_manager->id() == 0) {

                return app()->url_manager->redirect(login_url() . '?redirect=' . urlencode(mw()->url_manager->current()));

//                $page_non_active = array();
//                $page_non_active['id'] = 0;
//                $page_non_active['content_type'] = 'page';
//                $page_non_active['parent'] = '0';
//                $page_non_active['url'] = app()->url_manager->string();
//                $page_non_active['content'] = ' <module type="users/login" class="user-require-login-on-view" /> ';
//                $page_non_active['simply_a_file'] = 'clean.php';
//                $page_non_active['layout_file'] = 'clean.php';
//                $page_non_active['page_require_login'] = true;
//
//                template_var('content', $page_non_active['content']);
//                $content = $page_non_active;
            }
        }
        if (!defined('IS_HOME')) {
            if (isset($content['is_home']) and $content['is_home'] == 1) {
                define('IS_HOME', true);
                // $this->app->template->head('<link rel="canonical" href="' . site_url() . '">');
            }
        }

        $this->app->content_manager->define_constants($content);
        if($content and isset($content['id'])){
        $this->app->content_manager->content_id=intval($content['id']);
        }

        event_trigger('mw.front', $content);

        $overwrite = mw()->event_manager->trigger('mw.front.content_data', $content);
        if (isset($overwrite[0])) {
            $content = $overwrite[0];
        }

//        $override = $this->app->event_manager->trigger('content.link.after', $link);
//        if (is_array($override) && isset($override[0])) {
//            $link = $override[0];
//        }
        event_trigger('mw_frontend', $content);

        $render_file = $this->app->template->get_layout($content);


        $content['render_file'] = $render_file;

        if (defined('TEMPLATE_DIR')) {
            app()->template_manager->boot_template();
        }

        if ($this->return_data != false) {
            return $content;
        }


        if (!isset($page['title'])) {
            $page['title'] = 'New page';
        }
        if (!isset($content['title'])) {
            $content['title'] = 'New content';
        }


        $category = false;
        if (defined('CATEGORY_ID')) {
            $category = $this->app->category_manager->get_by_id(CATEGORY_ID);
        }

        if (isset($is_quick_edit) and $is_quick_edit == true and !defined('QUICK_EDIT')) {
            define('QUICK_EDIT', true);
        }

        if ($render_file) {

            $render_params = array();
            if ($show_404_to_non_admin) {

                $event_404 = event_trigger('mw.frontend.404', $content);
                if ($event_404) {
                    foreach ($event_404 as $event_item) {
                        if (is_array($event_item) and !empty($event_item)) {
                            $content = array_merge($content, $event_item);
                        }
                    }
                }


                if (!is_admin()) {
                    $load_template_404 = template_dir() . '404.php';
                    $load_template_404_2 = TEMPLATES_DIR . 'default/404.php';
                    if (is_file($load_template_404)) {
                        $render_file = $load_template_404;
                    } else {
                        if (is_file($load_template_404_2)) {
                            $render_file = $load_template_404_2;
                        }
                    }
                }
            }

            /*    if (!defined('CATEGORY_ID')) {
                    define('CATEGORY_ID', false);
                }

              /*  if (!defined('POST_ID')) {
                    define('POST_ID', false);
                }*/

            /* if (!defined('CONTENT_ID')) {
                 define('CONTENT_ID', false);
             }

             if (!defined('PAGE_ID')) {
                 define('PAGE_ID', false);
             }*/

            $render_params['render_file'] = $render_file;
            $render_params['page_id'] = page_id();
            $render_params['content_id'] = content_id();
            $render_params['post_id'] = post_id();
            $render_params['category_id'] = category_id();
            $render_params['content'] = $content;
            $render_params['category'] = $category;
            $render_params['page'] = $page;
            $render_params['meta_tags'] = true;

            $l = $this->app->template->render($render_params);
            if (is_object($l)) {
                return $l;
            }

            // used for preview from the admin wysiwyg
            if (isset($request_params['isolate_content_field'])) {
                require_once MW_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
                $pq = \phpQuery::newDocument($l);

                $isolated_head = pq('head')->eq(0)->html();

                $found_field = false;
                if (isset($request_params['isolate_content_field'])) {
                    foreach ($pq['[field=content]'] as $elem) {
                        $isolated_el = $l = pq($elem)->htmlOuter();
                    }
                }

                $is_admin = app()->user_manager->is_admin();
                if ($is_admin == true and isset($isolated_el) != false) {
                    $tb = mw_includes_path() . DS . 'toolbar' . DS . 'editor_tools' . DS . 'wysiwyg' . DS . 'index.php';

                    //$layout_toolbar = file_get_contents($filename);
                    $layout_toolbar = new View($tb);
                    $layout_toolbar = $layout_toolbar->__toString();
                    if ($layout_toolbar != '') {
                        if (strstr($layout_toolbar, '{head}')) {
                            if ($isolated_head != false) {
                                $layout_toolbar = str_replace('{head}', $isolated_head, $layout_toolbar);
                            }
                        }

                        if (strpos($layout_toolbar, '{content}')) {
                            $l = str_replace('{content}', $l, $layout_toolbar);
                        }

                        //$layout_toolbar = mw()->parser->process($layout_toolbar, $options = array('no_apc' => 1));
                    }
                }
            }
            $modify_content = event_trigger('on_load', $content);

            if ($this->debugbarEnabled) {
                \Debugbar::startMeasure('render', 'Time for rendering');
            }

            if ($is_editmode === null and $is_admin == true and mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false)) {
                //editmode fix
                $back_to_editmode = app()->user_manager->session_get('back_to_editmode');

                if (!$back_to_editmode) {
                    if (isset($_COOKIE['mw-back-to-live-edit']) and $_COOKIE['mw-back-to-live-edit']) {
                        if ($is_admin) {
                            $is_editmode = true;
                        }
                    }
                }


            }

            if ($is_editmode == true and !defined('IN_EDIT')) {
                define('IN_EDIT', true);
            }



            $l = $this->app->parser->process($l);


            if ($this->debugbarEnabled) {
                \Debugbar::stopMeasure('render');
            }
            if ($preview_module_id != false) {
                $request_params['embed_id'] = $preview_module_id;
            }
            if (isset($request_params['embed_id'])) {
                $find_embed_id = trim($request_params['embed_id']);
                $l = $this->app->parser->get_by_id($find_embed_id, $l);
            }

            if ($is_editmode == false
                and !$is_preview_template
                and !$is_preview_module
                and $this->isolate_by_html_id == false
                and !isset($request_params['isolate_content_field'])
                and !isset($request_params['embed_id'])
                and !is_cli()
                and !defined('MW_API_CALL')
            ) {
                event_trigger('mw.pageview');
            }

            //$apijs_loaded = $this->app->template->get_apijs_url();

            //$apijs_loaded = $this->app->template->get_apijs_url() . '?id=' . CONTENT_ID;

            $is_admin = app()->user_manager->is_admin();
            // $default_css = '<link rel="stylesheet" href="' . mw_includes_url() . 'default.css?v=' . MW_VERSION . '" type="text/css" />';


            $default_css_url = $this->app->template->get_default_system_ui_css_url();
            $default_css = '<link rel="stylesheet" href="' . $default_css_url . '" type="text/css" />';



            $headers = event_trigger('site_header', TEMPLATE_NAME);
            $template_headers_append = '';
            $one = 1;
            if (is_array($headers)) {
                foreach ($headers as $modify) {
                    if ($modify != false and is_string($modify) and $modify != '') {
                        $template_headers_append = $template_headers_append . $modify;
                    }
                }
                if ($template_headers_append != false and $template_headers_append != '') {
                    $l = str_ireplace('</head>', $template_headers_append . '</head>', $l, $one);
                }
            }

            $template_headers_src = $this->app->template->head(true);
            $template_footer_src = $this->app->template->foot(true);

            $template_headers_src_callback = $this->app->template->head_callback($page);
            if (is_array($template_headers_src_callback) and !empty($template_headers_src_callback)) {
                foreach ($template_headers_src_callback as $template_headers_src_callback_str) {
                    if (is_string($template_headers_src_callback_str)) {
                        $template_headers_src = $template_headers_src . "\n" . $template_headers_src_callback_str;
                    }
                }
            }


            if (isset($page['created_by'])) {
                $author = app()->user_manager->get_by_id($page['created_by']);
                if (is_array($author) and isset($author['profile_url']) and $author['profile_url'] != false) {
                    $template_headers_src = $template_headers_src . "\n" . '<link rel="author" href="' . trim($author['profile_url']) . '" />' . "\n";
                }
            }

            if ($template_headers_src != false and is_string($template_headers_src)) {
                $l = str_ireplace('</head>', $template_headers_src . '</head>', $l, $one);
            }

            $template_footer_src = $this->app->template->foot(true);

            $template_footer_src_callback = $this->app->template->foot_callback($page);
            if (is_array($template_footer_src_callback) and !empty($template_footer_src_callback)) {
                foreach ($template_footer_src_callback as $template_footer_src_callback_str) {
                    if (is_string($template_footer_src_callback_str)) {
                        $template_footer_src = $template_footer_src . "\n" . $template_footer_src_callback_str;
                    }
                }
            }

            // Add custom footer tags
            $website_footer_tags = $this->websiteOptions['website_footer'];
            if ($website_footer_tags != false) {
                $template_footer_src .= $website_footer_tags . "\n";
            }

            if ($template_footer_src != false and is_string($template_footer_src)) {
                $l = str_ireplace('</body>', $template_footer_src . '</body>', $l, $one);
            }


            $l = $this->app->template->append_api_js_to_layout($l);


            //   if (!stristr($l, $apijs_loaded)) {
            //$apijs_settings_loaded = $this->app->template->get_apijs_settings_url() . '?id=' . CONTENT_ID . '&category_id=' . CATEGORY_ID;
//            $apijs_settings_loaded = $this->app->template->get_apijs_settings_url();
//            $apijs_settings_script = "\r\n" . '<script src="' . $apijs_settings_loaded . '"></script>' . "\r\n";
//            $apijs_settings_script .= '<script src="' . $apijs_loaded . '"></script>' . "\r\n";
//            $l = str_ireplace('<head>', '<head>' . $apijs_settings_script, $l);
            //  }

            if (isset($content['active_site_template']) and $content['active_site_template'] == 'default' and $the_active_site_template != 'default' and $the_active_site_template != 'mw_default') {
                $content['active_site_template'] = $the_active_site_template;
            }




            // if ($is_editmode == true) {
            if (isset($content['active_site_template']) and trim($content['active_site_template']) != '' and $content['active_site_template'] != 'default') {
                if (!defined('CONTENT_TEMPLATE')) {
                    define('CONTENT_TEMPLATE', $content['active_site_template']);
                }

                $custom_live_edit = TEMPLATES_DIR . DS . $content['active_site_template'] . DS . 'live_edit.css';
                $live_edit_css_folder = userfiles_path() . 'css' . DS . $content['active_site_template'] . DS;
                $live_edit_url_folder = userfiles_url() . 'css/' . $content['active_site_template'] . '/';
                $custom_live_edit = $live_edit_css_folder . DS . 'live_edit.css';
            } else {
                if (!defined('CONTENT_TEMPLATE')) {
                    define('CONTENT_TEMPLATE', $the_active_site_template);
                }

                //                if ($the_active_site_template == 'mw_default') {
                //                    $the_active_site_template = 'default';
                //                }
                $custom_live_edit = TEMPLATE_DIR . DS . 'live_edit.css';

                $live_edit_css_folder = userfiles_path() . 'css' . DS . $the_active_site_template . DS;
                $live_edit_url_folder = userfiles_url() . 'css/' . $the_active_site_template . '/';
                $custom_live_edit = $live_edit_css_folder . 'live_edit.css';
            }
            $custom_live_edit = normalize_path($custom_live_edit, false);

            $liv_ed_css = false;
            if (is_file($custom_live_edit)) {
                $custom_live_editmtime = filemtime($custom_live_edit);
                $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url_folder . 'live_edit.css?version=' . $custom_live_editmtime . '" id="mw-template-settings" type="text/css" />';
                $l = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
            }


            $liv_ed_css_get_custom_css_content = $this->app->template->get_custom_css_content();
            if ($liv_ed_css_get_custom_css_content == false) {
                if ($is_editmode) {
                    $liv_ed_css = '<link rel="stylesheet"   id="mw-custom-user-css" type="text/css" />';
                }
            } else {
                $liv_ed_css = $this->app->template->get_custom_css_url();

                $liv_ed_css = '<link rel="stylesheet" href="' . $liv_ed_css . '" id="mw-custom-user-css" type="text/css" />';
            }

            if ($liv_ed_css != false) {
                $l = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
            }
            //    }

            // Add custom head tags
            $website_head_tags = $this->websiteOptions['website_head'];
            $rep_count = 1;
            if ($website_head_tags != false) {
                $l = str_ireplace('</head>', $website_head_tags . '</head>', $l, $rep_count);
            }

            if (defined('MW_VERSION')) {
                $generator_tag = "\n" . '<meta name="generator" content="' . addslashes(mw()->ui->brand_name()) . '" />' . "\n";
                $l = str_ireplace('</head>', $generator_tag . '</head>', $l, $rep_count);
            }





            $template_config = $this->app->template->get_config();
            $enable_default_css = true;
            if ($template_config and isset($template_config["standalone_ui"]) and $template_config["standalone_ui"]) {
                if (!$is_editmode and !$back_to_editmode) {
                    $enable_default_css = false;
                }
            }
            if ($enable_default_css) {
                $l = str_ireplace('<head>', '<head>' . $default_css, $l);
            }


            if (isset($content['original_link']) and $content['original_link'] != '') {
                $content['original_link'] = str_ireplace('{site_url}', app()->url_manager->site(), $content['original_link']);
                $redirect = $this->app->format->prep_url($content['original_link']);
                if ($redirect != '' and $redirect != site_url() and $redirect . '/' != site_url()) {
                    return app()->url_manager->redirect($redirect);
                }
            }
            if ($is_editmode == true and $this->isolate_by_html_id == false and !isset($request_params['isolate_content_field'])) {
                if ($is_admin == true) {
                    $l = $this->liveEditToolbar($l);
                }
            } elseif ($is_editmode == false and $is_admin == true and mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false)) {
                if (!isset($request_params['isolate_content_field']) and !isset($request_params['content_id'])) {
                    if(!isset($_REQUEST['preview_layout'])) {
                        if ($back_to_editmode == true) {
                            $l = $this->liveEditToolbarBack($l);
                        }
                    }
                }
            } else {

                $l = $this->app->template->optimize_page_loading($l);

            }


            $l = $this->app->parser->replace_url_placeholders($l);


            if ($page != false and empty($this->page)) {
                $this->page = $page;
            }
            $l = execute_document_ready($l);

            event_trigger('frontend');


            $l = mw()->template->add_csrf_token_meta_tags($l);

            $is_embed = app()->url_manager->param('embed');

            if ($is_embed != false) {
                $this->isolate_by_html_id = $is_embed;
            }

            if ($this->isolate_by_html_id != false) {
                $id_sel = $this->isolate_by_html_id;
                $this->isolate_by_html_id = false;
                require_once MW_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
                $pq = \phpQuery::newDocument($l);
                foreach ($pq['#' . $id_sel] as $elem) {
                    $l = pq($elem)->htmlOuter();
                }
            }
            if (mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false) and $is_editmode) {
                app()->user_manager->session_set('last_content_id', CONTENT_ID);
            }
            if (isset($output_cache_content) and $enable_full_page_cache and $output_cache_timeout != false) {
                if (!defined('MW_NO_OUTPUT_CACHE')) {
                    $output_cache_content_save = [];
                    $l = $this->app->parser->replace_non_cached_modules_with_placeholders($l);

                    $output_cache_content_save['layout'] = $l;
                    $output_cache_content_save['time'] = now();
                    if (!str_contains($output_cache_content, 'image-generate-tn-request')) {
                        $this->app->cache_manager->save($output_cache_content_save, $output_cache_id, $output_cache_group, $output_cache_timeout);
                    } else {
                        $this->app->cache_manager->save(null, $output_cache_id, $output_cache_group, $output_cache_timeout);
                    }
                }
            }
            if (isset($request_params['debug'])) {
                if ($this->app->make('config')->get('app.debug')) {
                    $is_admin = app()->user_manager->is_admin();
                    if ($is_admin == true) {
                        include mw_includes_path() . 'debug.php';
                    }
                }
            }


            if ($show_404_to_non_admin and !$is_admin) {
                $response = \Response::make($l);
                $response->setStatusCode(404);
                return $response;
            }

            $response = \Response::make($l);
            if (defined('MW_NO_OUTPUT_CACHE') or $is_editmode == true or (strstr($l, 'image-generate-tn-request'))) {
                $response->header('Pragma', 'no-cache');
                $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
                $response->header('Cache-Control', 'no-cache, must-revalidate, no-store, max-age=0, private');
            }


            return $response;


        } else {
            echo 'Error! Page is not found? Please login in the admin and make a page.';

           // $this->app->cache_manager->clear();

            return;
        }
    }

    public function m()
    {
        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        if (!defined('MW_NO_OUTPUT')) {
            define('MW_NO_OUTPUT', true);
        }

        return $this->module();
    }

    public function sitemapxml()
    {
        $sm_file = mw_cache_path() . mw()->url_manager->hostname() . '_sitemap.xml';

        $updateSitemap = false;
        if (is_file($sm_file)) {
            $filelastmodified = filemtime($sm_file);
            // The file is old
            if ((time() - $filelastmodified) > 3 * 3600) {
                $updateSitemap = true;
            }
        }

        if ($updateSitemap) {
            $map = new \Microweber\Utils\Sitemap($sm_file);
            $map->file = $sm_file;

            // >>> Add categories
            $categories = get_categories('no_limit=1');
            foreach ($categories as $category) {
                $link = category_link($category['id']);
                $map->addPage($link, 'daily', 1, $category['updated_at']);
            }
            // <<< Add categories
            $cont = get_content('is_active=1&is_deleted=0&limit=2500&fields=id,content_type,url,updated_at&orderby=updated_at desc');

            if (!empty($cont)) {
                foreach ($cont as $item) {
                    if (!empty($item['content_type']) && !empty($item['url']) && in_array($item['content_type'], ['page', 'product', 'post'])) {
                        $map->addPage($this->app->content_manager->link($item['id']), 'daily', 1, $item['updated_at']);
                    }
                }
            }

            $map = $map->create();
        }
        $map = $sm_file;
        $fp = fopen($map, 'r');

        // send the right headers
        header('Content-Type: text/xml');
        header('Content-Length: ' . filesize($map));

        // dump the file and stop the script
        fpassthru($fp);

        event_trigger('mw_robot_url_hit');

        exit;
    }

    private function _api_response($res)
    {
        $status_code = 200;
        if ($res instanceof Response) {
            return $res;
        }

        if (defined('MW_API_RAW')) {
            return response($res);
        }

        if (!defined('MW_API_HTML_OUTPUT')) {
            if (is_bool($res) or is_int($res)) {
                return \Response::make(json_encode($res), $status_code);
            } elseif ($res instanceof RedirectResponse) {
                return $res;
            } elseif ($res instanceof Response) {
                return $res;
            }

            $response = \Response::make($res, $status_code);
            if (is_bool($res) or is_int($res) or is_array($res)) {
                $response->header('Content-Type', 'application/json');
            }

            return $response;
        } else {
            if (is_array($res)) {
                $res = json_encode($res);
            } else if (is_bool($res)) {
                $res = (bool)$res;
            }
            $response = \Response::make($res, $status_code);
            return $response;
        }
    }


    /**
     * @deprecated 1.1.12 Moved to JsCompileController
     */

    public function apijs_settings()
    {
        return (new JsCompileController())->apijs_settings();
    }


    /**
     * @deprecated 1.1.12 Moved to JsCompileController
     */
    public function apijs()
    {
        return (new JsCompileController())->apijs();
    }


    public function robotstxt()
    {
        header('Content-Type: text/plain');
        $robots = $this->websiteOptions['robots_txt'];

        if ($robots == false) {
            $robots = "User-agent: *\nAllow: /" . "\n";
            $robots .= 'Disallow: /cache/' . "\n";
            $robots .= 'Disallow: /storage/' . "\n";
            $robots .= 'Disallow: /database/' . "\n";
            $robots .= 'Disallow: /vendor/' . "\n";
            $robots .= 'Disallow: /src/' . "\n";
            $robots .= 'Disallow: /userfiles/modules/*/*.php' . "\n";
            $robots .= 'Disallow: /userfiles/templates/*/*.php' . "\n";
        }
        event_trigger('mw_robot_url_hit');
        echo $robots;
        exit;
    }

    public function show_404()
    {
        header('HTTP/1.0 404 Not Found');
        $v = new View(MW_ADMIN_VIEWS_DIR . '404.php');
        echo $v;
    }

    public function __get($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
    }

    public function __set($name, $data)
    {
        if (is_callable($data)) {
            $this->functions[$name] = $data;
        } else {
            $this->vars[$name] = $data;
        }
    }

    public function __call($method, $args)
    {
        if (isset($this->functions[$method])) {
            call_user_func_array($this->functions[$method], $args);
        } else {
            // error out
        }
    }
}
