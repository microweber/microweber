<?php

namespace Modules\Content\Support;

class PagingLinks
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')
    {
        if ($base_url == false) {
            if ($this->app->url_manager->is_ajax() == false) {
                $base_url = $this->app->url_manager->current(1);
            } else {
                if ($_SERVER['HTTP_REFERER'] != false) {
                    $base_url = $_SERVER['HTTP_REFERER'];
                }
            }
        }

        $page_links = array();
        $the_url = $base_url;
        $append_to_links = '';
        if (strpos($the_url, '?')) {
            $the_url = substr($the_url, 0, strpos($the_url, '?'));
        }
        $get_params = array();
        $get_params_append = '';
        if ($_GET) {
            $get_params = array_merge($get_params, $_GET);
        }

        if (isset($get_params[$paging_param])) {
            unset($get_params[$paging_param]);
        }

        if ($get_params and is_array($get_params)) {
            $get_params = array_filter($get_params);

            $get_params_append = implode('&', array_map(
                function ($v, $k) {
                    if ($k and $v and !is_array($v)) {
                        return sprintf("%s=%s", $k, $v);
                    }
                },
                $get_params,
                array_keys($get_params)
            ));
        }

        $in_empty_url = false;
        if ($the_url == site_url()) {
            $in_empty_url = 1;
        }

        if ($get_params_append) {
            if (stristr($base_url, '?') == false) {
                $append_to_links = '?' . $get_params_append;
            } else {
                $append_to_links = '&' . $get_params_append;
            }
        }

        $the_url = explode('/', $the_url);
        for ($x = 1; $x <= $pages_count; ++$x) {
            $new = array();
            foreach ($the_url as $itm) {
                $itm = explode(':', $itm);
                if ($itm[0] == $paging_param) {
                    $itm[1] = $x;
                }
                $new[] = implode(':', $itm);
            }
            $new_url = implode('/', $new);
            $page_links[$x] = $new_url;
        }

        $count = count($page_links);
        for ($x = 1; $x <= $count; ++$x) {
            if (stristr($page_links[$x], $paging_param . ':') == false) {
                if ($in_empty_url == false) {
                    $l = reduce_double_slashes($page_links[$x] . '/' . $paging_param . ':' . $x);
                    if ($get_params_append) {
                        $l = $l . '?' . $get_params_append;
                    }
                } else {
                    $l = reduce_double_slashes($page_links[$x] . '?' . $paging_param . '=' . $x);
                    if ($get_params_append) {
                        $l = $l . '&' . $get_params_append;
                    }
                }

                $l = reduce_double_slashes($page_links[$x] . '?' . $paging_param . '=' . $x);
                if ($get_params_append) {
                    $l = $l . '&' . $get_params_append;
                }
                $l = str_ireplace('module/', '', $l);
                $page_links[$x] = $l;
            }
        }
        return $page_links;
    }
}
