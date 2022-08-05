<?php

namespace MicroweberPackages\App\Managers;

class Ui
{
    public $custom_fields = array();
    public $admin_logo = '';
    public $admin_logo_login = '';
    public $admin_logo_login_link = false;
    public $logo_live_edit = '';
    public $brand_name = 'Microweber';
    public $powered_by_link = false;
    public $disable_powered_by_link = false;
    public $disable_marketplace = false;
    public $package_manager_urls = false;
    public $marketplace_provider_id = false;
    public $marketplace_access_code = false;
    public $enable_service_links = true;
    public $custom_support_url = false;
    public $admin_colors_sass = false;
    public $brand_favicon = '';

    public $modules_ui = array();

    public function __construct()
    {
        $this->admin_logo_login = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
        $this->logo_live_edit = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo-mobile.svg';
        if (mw_is_installed()) {
            $this->defaults();
        }
    }

    public function defaults()
    {
        $btn = array();
        $btn['content_type'] = 'page';
        $btn['title'] = _e('Page', true);
        $btn['class'] = 'mai-page';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'post';
        $btn['title'] = _e('Post', true);
        $btn['class'] = 'mai-post';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'category';
        $btn['title'] = _e('Category', true);
        $btn['class'] = 'mai-category';
        $this->module('content.create.menu', $btn);

//        $notif_count = mw()->notifications_manager->get_unread_count();
//        $notif_count_html = false;
//        if (intval($notif_count) > 0) {
//            $notif_count_html = '<span class="badge badge-danger badge-sm badge-pill d-inline-block ml-2">' . $notif_count . '</span>';
//        }
//        $admin_dashboard_btn = array();
//        $admin_dashboard_btn['view'] = 'admin__notifications';
//        $admin_dashboard_btn['text'] = _e('Notifications', true) . $notif_count_html;
//        $admin_dashboard_btn['icon_class'] = 'mdi mdi-bell';
//        $this->module('admin.dashboard.menu', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'content';
        $admin_dashboard_btn['text'] = _e('Manage Website', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-earth';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'modules';
        $admin_dashboard_btn['text'] = _e('Manage Modules', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-view-grid-plus';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'files';
        $admin_dashboard_btn['text'] = _e('File Manager', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-file-check';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        /*$admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'upgrades';
        $admin_dashboard_btn['text'] = _e('Upgrades', true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-market';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);*/

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'marketplace';
        $admin_dashboard_btn['text'] = _e('Go to Marketplace', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-fruit-cherries';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);


        $notif_count = 0;

//        if (defined('MW_BACKEND') and MW_BACKEND) {
//            $notif_count_updates_data = mw()->update->get_updates_notifications('limit=1');
//
//            if (isset($notif_count_updates_data[0])
//                and isset($notif_count_updates_data[0]['notification_data'])
//                and !empty($notif_count_updates_data[0]['notification_data'])
//            ) {
//                $notif_data = $notif_count_updates_data[0]['notification_data'];
//
//                if (isset($notif_data['core_update'])) {
//                    $notif_count = $notif_count + 1;
//                }
//                $others_updates = array('modules', 'templates', 'elements');
//                foreach ($others_updates as $item) {
//                    if (isset($notif_data[$item]) and is_arr($notif_data[$item])) {
//                        $notif_count = $notif_count + count($notif_data[$item]);
//                    }
//                }
////            if(isset($notif_data['popup']) and $notif_data['popup']) {
////                event_bind(
////                    'mw.admin.dashboard.main', function () use ($notif_data) {
////                    print load_module('updates/updates_popup',$notif_data);
////
////                 }
////                );
////            }
//
//            }
//        }


        $notif_count_html = false;
//        if (intval($notif_count) > 0) {
//            $notif_count_html = '<span class="badge badge-danger badge-sm badge-pill d-inline-block ml-2">' . $notif_count . '</span>';
//        }
        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'updates';
        $admin_dashboard_btn['text'] = _e('Updates', true) . $notif_count_html;
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-update';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['link'] = 'https://microweber.com/contact-us';
        $admin_dashboard_btn['text'] = _e('Suggest a feature', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-penguin';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $fields = array(
            'price' => 'Price',
            'text' => 'Text Field',
            //'button' => 'Button',
            'radio' => 'Single Choice',
            'dropdown' => 'Dropdown',
            'checkbox' => 'Multiple choices',
            'number' => 'Number',
            'phone' => 'Phone',
            'site' => 'Web Site',
            'email' => 'E-mail',
            'address' => 'Address',
            'country' => 'Country',
            'date' => 'Date',
            'time' => 'Time',
            'color' => 'Color',
            'upload' => 'File Upload',
            'property' => 'Property',
            'breakline' => 'Break Line',
            'hidden' => 'Hidden Field',
        );

        $this->custom_fields = $fields;
    }

    public function predefined_colors()
    {
        $colorNames = ['AliceBlue','AntiqueWhite','Aqua','Aquamarine','Azure','Beige','Bisque','Black','BlanchedAlmond','Blue','BlueViolet','Brown','BurlyWood','CadetBlue','Chartreuse','Chocolate','Coral','CornflowerBlue','Cornsilk','Crimson','Cyan','DarkBlue','DarkCyan','DarkGoldenRod','DarkGray','DarkGrey','DarkGreen','DarkKhaki','DarkMagenta','DarkOliveGreen','DarkOrange','DarkOrchid','DarkRed','DarkSalmon','DarkSeaGreen','DarkSlateBlue','DarkSlateGray','DarkSlateGrey','DarkTurquoise','DarkViolet','DeepPink','DeepSkyBlue','DimGray','DimGrey','DodgerBlue','FireBrick','FloralWhite','ForestGreen','Fuchsia','Gainsboro','GhostWhite','Gold','GoldenRod','Gray','Grey','Green','GreenYellow','HoneyDew','HotPink','IndianRed','Indigo','Ivory','Khaki','Lavender','LavenderBlush','LawnGreen','LemonChiffon','LightBlue','LightCoral','LightCyan','LightGoldenRodYellow','LightGray','LightGrey','LightGreen','LightPink','LightSalmon','LightSeaGreen','LightSkyBlue','LightSlateGray','LightSlateGrey','LightSteelBlue','LightYellow','Lime','LimeGreen','Linen','Magenta','Maroon','MediumAquaMarine','MediumBlue','MediumOrchid','MediumPurple','MediumSeaGreen','MediumSlateBlue','MediumSpringGreen','MediumTurquoise','MediumVioletRed','MidnightBlue','MintCream','MistyRose','Moccasin','NavajoWhite','Navy','OldLace','Olive','OliveDrab','Orange','OrangeRed','Orchid','PaleGoldenRod','PaleGreen','PaleTurquoise','PaleVioletRed','PapayaWhip','PeachPuff','Peru','Pink','Plum','PowderBlue','Purple','RebeccaPurple','Red','RosyBrown','RoyalBlue','SaddleBrown','Salmon','SandyBrown','SeaGreen','SeaShell','Sienna','Silver','SkyBlue','SlateBlue','SlateGray','SlateGrey','Snow','SpringGreen','SteelBlue','Tan','Teal','Thistle','Tomato','Turquoise','Violet','Wheat','White','WhiteSmoke','Yellow','YellowGreen'];
        $colorHexs = ['f0f8ff','faebd7','00ffff','7fffd4','f0ffff','f5f5dc','ffe4c4','000000','ffebcd','0000ff','8a2be2','a52a2a','deb887','5f9ea0','7fff00','d2691e','ff7f50','6495ed','fff8dc','dc143c','00ffff','00008b','008b8b','b8860b','a9a9a9','a9a9a9','006400','bdb76b','8b008b','556b2f','ff8c00','9932cc','8b0000','e9967a','8fbc8f','483d8b','2f4f4f','2f4f4f','00ced1','9400d3','ff1493','00bfff','696969','696969','1e90ff','b22222','fffaf0','228b22','ff00ff','dcdcdc','f8f8ff','ffd700','daa520','808080','808080','008000','adff2f','f0fff0','ff69b4','cd5c5c','4b0082','fffff0','f0e68c','e6e6fa','fff0f5','7cfc00','fffacd','add8e6','f08080','e0ffff','fafad2','d3d3d3','d3d3d3','90ee90','ffb6c1','ffa07a','20b2aa','87cefa','778899','778899','b0c4de','ffffe0','00ff00','32cd32','faf0e6','ff00ff','800000','66cdaa','0000cd','ba55d3','9370db','3cb371','7b68ee','00fa9a','48d1cc','c71585','191970','f5fffa','ffe4e1','ffe4b5','ffdead','000080','fdf5e6','808000','6b8e23','ffa500','ff4500','da70d6','eee8aa','98fb98','afeeee','db7093','ffefd5','ffdab9','cd853f','ffc0cb','dda0dd','b0e0e6','800080','663399','ff0000','bc8f8f','4169e1','8b4513','fa8072','f4a460','2e8b57','fff5ee','a0522d','c0c0c0','87ceeb','6a5acd','708090','708090','fffafa','00ff7f','4682b4','d2b48c','008080','d8bfd8','ff6347','40e0d0','ee82ee','f5deb3','ffffff','f5f5f5','ffff00','9acd32'];

        $colors = [];
        foreach ($colorHexs as $i=>$hex) {
            $colors[] = [
                'name'=>$colorNames[$i],
                'hex'=>$hex
            ];
        }
        return $colors;
    }

    public function module($name = false, $arr = false)
    {
        if (!$name) {
            return;
        }
        if (!isset($this->modules_ui[$name])) {
            $this->modules_ui[$name] = array();
        }
        if ($arr != false and !empty($arr)) {
            array_push($this->modules_ui[$name], $arr);
        } else {
            $this->modules_ui[$name] = array_unique($this->modules_ui[$name], SORT_REGULAR);
        }

        return $this->modules_ui[$name];
    }

    public function brand_name()
    {
        return $this->brand_name;
    }

    public function admin_colors_sass()
    {
        return $this->admin_colors_sass;
    }

    public function live_edit_logo()
    {
        return $this->logo_live_edit;
    }

    public function admin_logo_login()
    {
        return $this->admin_logo_login;
    }

    public function admin_logo()
    {
        return $this->admin_logo;
    }


    public function brand_favicon()
    {
        return $this->brand_favicon;
    }

    public function create_content_menu()
    {
        return $this->create_content_menu;
    }

    public function service_links_enabled()
    {
        return intval($this->enable_service_links);
    }

    public function powered_by_link_enabled()
    {
        if (intval($this->disable_powered_by_link) != 0) {
            return false;
        }
        return true;
    }

    public function custom_fields()
    {
        return $this->custom_fields;
    }

    public function enable_service_links()
    {
        return $this->enable_service_links;
    }

    public function powered_by_link()
    {
        if ($this->disable_powered_by_link != false) {
            return;
        }
        $link = '<span class="mw-powered-by"><a href="https://microweber.org/" title="Website Builder">Website Builder</a> by <a href="https://microweber.org" target="_blank" title="Make a website">Microweber</a></span>';
        if ($this->powered_by_link != false) {
            $link = $this->powered_by_link;
        }

        return $link;
    }
}
