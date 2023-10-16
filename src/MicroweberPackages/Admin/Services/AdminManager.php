<?php

namespace MicroweberPackages\Admin\Services;

use Illuminate\Support\Facades\Event;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\MenuBuilder\Menu;
use MicroweberPackages\Template\Traits\HasMenus;
use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;

class AdminManager
{
    use HasMenus;
    use HasScriptsAndStylesTrait;

    public function __construct()
    {
        $this->addDefaultScripts();
        $this->addDefaultStyles();
        $this->addDefaultCustomTags();

        $this->initMenus();
    }

    public function initMenus()
    {
        $this->menus['left_menu_top'] = new Menu();
        $this->menus['left_menu_bottom'] = new Menu();
        $this->menus['top_menu_left'] = new Menu();
        $this->menus['top_menu_right'] = new Menu();
        $this->menus['footer_links'] = new Menu();

        $this->menus['left_menu_top']->addChild('Dashboard', [
            'uri' => admin_url(),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M520 456V216h320v240H520ZM120 616V216h320v400H120Zm400 320V536h320v400H520Zm-400 0V696h320v240H120Zm80-400h160V296H200v240Zm400 320h160V616H600v240Zm0-480h160v-80H600v80ZM200 856h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360 776Z"/></svg>'
            ]
        ]);
        $this->menus['left_menu_top']->getChild('Dashboard')
            ->setExtra('orderNumber', 1);

        $this->menus['left_menu_top']->addChild('Website', [
            'uri' => admin_url(),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-40-82v-78q-33 0-56.5-23.5T360 736v-40L168 504q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440 894Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600 280v16q0 33-23.5 56.5T520 376h-80v80q0 17-11.5 28.5T400 496h-80v80h240q17 0 28.5 11.5T600 616v120h40q26 0 47 15.5t29 40.5Z"/></svg>'
            ]
        ]);

        $this->menus['left_menu_top']->getChild('Website')
            ->setExtra('orderNumber', 2);

        $this->menus['left_menu_top']->getChild('Website')
            ->addChild('Design', [
                'attributes' => ['route'=>'admin.page.design'],
            ]);

        $this->menus['left_menu_top']->getChild('Website')
            ->addChild('Pages', [
                'attributes' => ['route'=>'admin.page.index'],
            ]);

        $this->menus['left_menu_top']->getChild('Website')
           ->getChild('Pages')
           ->setExtra('routes', [
               'admin.page.index',
               'admin.page.create',
               'admin.page.edit',
               'admin.page.show',
           ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Pages')
            ->addChild('Add Page', [
                'attributes' => ['route' => 'admin.page.create']
            ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Pages')
            ->addChild('List Pages', [
                'attributes' => ['route' => 'admin.page.index']
            ]);

        $this->menus['left_menu_top']->getChild('Website')
            ->addChild('Categories', [
                'attributes' => ['route'=>'admin.category.index'],
            ]);
        $this->menus['left_menu_top']->getChild('Website')
            ->getChild('Categories')
            ->setExtra('routes', [
                'admin.category.index',
                'admin.category.create',
                'admin.category.edit',
                'admin.category.show',
            ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Categories')
            ->addChild('Add Category', [
                'attributes' => ['route' => 'admin.category.create']
            ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Categories')
            ->addChild('List Categories', [
                'attributes' => ['route' => 'admin.category.index']
            ]);

        $this->menus['left_menu_top']->getChild('Website')
            ->addChild('Posts', [
                'attributes' => ['route'=>'admin.post.index'],
            ]);
        $this->menus['left_menu_top']->getChild('Website')
            ->getChild('Posts')
            ->setExtra('routes', [
                'admin.post.index',
                'admin.post.create',
                'admin.post.edit',
                'admin.post.show',
            ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Posts')
            ->addChild('Add Post', [
                'attributes' => ['route' => 'admin.post.create']
            ]);

        $this->menus['left_menu_top']
            ->getChild('Website')
            ->getChild('Posts')
            ->addChild('List Posts', [
                'attributes' => ['route' => 'admin.post.index']
            ]);
        $showServiceLink = mw()->ui->enable_service_links();
        $showPoweredBy = mw()->ui->powered_by_link_enabled();

        if($showServiceLink && $showPoweredBy) {

            $this->menus['left_menu_bottom']->addChild('Get Help', [
                'uri' => 'https://microweber.com/support',
                'attributes' => [
                    'icon' => ' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M478 816q21 0 35.5-14.5T528 766q0-21-14.5-35.5T478 716q-21 0-35.5 14.5T428 766q0 21 14.5 35.5T478 816Zm-36-154h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342 438l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506 530q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>'
                ]
            ]);
        }

        $this->menus['left_menu_bottom']->addChild('Log out', [
            'uri' => api_url('logout'),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M440 616V216h80v400h-80Zm40 320q-74 0-139.5-28.5T226 830q-49-49-77.5-114.5T120 576q0-80 33-151t93-123l56 56q-48 40-75 97t-27 121q0 116 82 198t198 82q117 0 198.5-82T760 576q0-64-26.5-121T658 358l56-56q60 52 93 123t33 151q0 74-28.5 139.5t-77 114.5q-48.5 49-114 77.5T480 936Z"/></svg>'
            ]
        ]);
    }


    public function addDefaultScripts(): void
    {
        $apijs_combined_loaded = app()->template->get_apijs_combined_url();
        $this->addScript('mw-api-js', $apijs_combined_loaded);
    }

    public function addDefaultStyles(): void
    {
       // $default_css_url = app()->template->get_default_system_ui_css_url();
     //   $this->addStyle('mw-default-css', $default_css_url);

        $main_css_url = app()->template->get_admin_system_ui_css_url();
        $this->addStyle('mw-ui-css', $main_css_url);


        $url = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/dist/js/tabler.min.js';
        $this->addScript('mw-tabler-js', $url);



        $favicon_image = get_option('favicon_image', 'website');

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }

        if ($favicon_image) {
            $this->addStyle('favicon', $favicon_image, ['rel' => 'shortcut icon']);
        }

    }

    public function addDefaultCustomTags(): void
    {
        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $this->addCustomHeadTag($template_headers_src);
        }
    }

    public function serving(\Closure $callback): void
    {
        Event::listen(ServingAdmin::class, $callback);
    }

}
