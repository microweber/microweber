<?php

namespace Modules\Content\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PagingNav
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


    /**
     * paging.
     *
     * paging
     *
     * @param $params ['num'] = 5; //the numer of pages
     *
     * @return string - html string with ul/li
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
    public function get($params)
    {

        $params = parse_params($params);

        $pages_count = 1;
        $base_url = false;
        $paging_param = 'current_page';
        $keyword_param = 'keyword_param';
        $class = 'pagination';
        $li_class = '';
        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }

        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = intval($params['limit']);
        }

        if (isset($params['class'])) {
            $class = $params['class'];
        }
        if (isset($params['li_class'])) {
            $li_class = $params['li_class'];
        }

        if (isset($params['paging_param'])) {
            $paging_param = $params['paging_param'];
        }

        $current_page_from_url = $this->app->url_manager->param($paging_param);

        if (isset($params['current_page'])) {
            $current_page_from_url = $params['current_page'];
        } elseif (isset($params['curent_page'])) {
            $current_page_from_url = $params['curent_page'];
        }
        $no_wrap = false;
        if (isset($params['no_wrap'])) {
            $no_wrap = true;
        }

        // Laravel pagination
        if (isset($params['laravel_pagination'])) {

            if ($this->app->url_manager->is_ajax() == false) {
                $base_url = $this->app->url_manager->current(1);
            } else {
                if ($_SERVER['HTTP_REFERER'] != false) {
                    $base_url = $_SERVER['HTTP_REFERER'];
                }
            }

            $current_page_from_url = $current_page_from_url ?: (Paginator::resolveCurrentPage() ?: 1);

            $items = [];
            for ($i = 0; $i <= $params['laravel_total']; $i++) {
                $items[] = 1;
            }
            $items = Collection::make($items);

            $paginate = new LengthAwarePaginator($items->forPage($current_page_from_url, $params['laravel_pagination_limit']), $items->count(), $params['laravel_pagination_limit'], $current_page_from_url, []);

            $paginationPath = strtok($base_url, '?');
            $paginate->setPath($paginationPath);

            if (isset($params['return_as_array']) && $params['return_as_array']) {

                $paginateArray = $paginate->toArray();

                $pagination_links = [];

                foreach ($paginateArray['links'] as $paginate) {

                    $pagination_links[] = [
                        'attributes' => [
                            'class' => '',
                            'current' => $paginate['active'],
                            'data-page-number' => '',
                            'href' => $paginate['url']
                        ],
                        'title' => $paginate['label']
                    ];
                }

                return $pagination_links;
            } else {
                return $paginate->links();
            }
        }

        // OLD pagiantion
        $ready_paging_first_links = [];
        $ready_paging_last_links = [];
        $ready_paging_number_links = [];
        $data = $this->get($base_url, $pages_count, $paging_param, $keyword_param);
        if (is_array($data)) {

            if ($no_wrap) {
                $to_print = "<ul class='{$class}'>";
            } else {
                $to_print = "<div class='{$class}-holder' ><ul class='{$class}'>";
            }

            if ($current_page_from_url > 1 && isset($params['show_first_last'])) {
                $to_print = '<a data-page-number="' . $data[1] . '" href="' . $data[1] . '">' . _e('First', true) . '</a>';
                $ready_paging_first_links[] = [
                    'attributes' => [
                        'class' => false,
                        'current' => false,
                        'data-page-number' => $data[1],
                        'href' => $data[1]
                    ],
                    'title' => _e('First', true)
                ];
            }

            $paging_items = array();
            $active_item = 1;
            foreach ($data as $key => $value) {
                $skip = false;
                $act_class = false;
                if ($current_page_from_url != false) {
                    if (intval($current_page_from_url) == intval($key)) {
                        $act_class = ' active ';
                        $active_item = $key;
                    }
                }

                $item_to_print = '';
                $item_to_print .= "";
                $item_to_print .= "<li class=\"page-item\"><a class=\"{$act_class} page-link\" href=\"$value\" data-page-number=\"$key\">$key</a></li> ";
                $item_to_print .= '';
                $paging_items[$key] = $item_to_print;

                /*
                 * TODO: this will bug when we have many products
                 *   if (count($ready_paging_number_links) > $limit) {
                      continue;
                  }*/

                $ready_paging_number_links[] = [
                    'attributes' => [
                        'class' => $act_class,
                        'current' => $act_class,
                        'data-page-number' => $key,
                        'href' => $value
                    ],
                    'title' => $key
                ];
            }

            if ($limit != false and count($paging_items) > $limit) {
                $limited_paging = array();

                $limited_paging_begin = array();

                foreach ($paging_items as $key => $paging_item) {
                    if ($key == $active_item) {
                        $steps = $steps2 = floor($limit / 2);
                        for ($i = 1; $i <= $steps; ++$i) {
                            if (isset($paging_items[$key - $i])) {
                                $limited_paging_begin[$key - $i] = $paging_items[$key - $i];
                                // $steps2--;
                            } else {
                                ++$steps2;
                            }
                        }

                        $limited_paging[$key] = $paging_item;
                        for ($i = 1; $i <= $steps2; ++$i) {
                            if (isset($paging_items[$key + $i])) {
                                $limited_paging[$key + $i] = $paging_items[$key + $i];
                            }
                        }
                    }
                }
                $prev_link = '#';
                $next_link = '#';
                if (isset($data[$active_item - 1])) {
                    $prev_link = $data[$active_item - 1];
                    $limited_paging_begin[] = '<li class="page-item"><a data-page-number="' . ($active_item - 1) . '" href="' . $prev_link . '" class="page-link">&laquo;</a></li>';

                    $ready_paging_first_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => ($active_item - 1),
                            'href' => $prev_link
                        ],
                        'title' => 'Previous'
                    ];

                }

                $limited_paging_begin = array_reverse($limited_paging_begin);
                $limited_paging = array_merge($limited_paging_begin, $limited_paging);

                if (isset($data[$active_item + 1])) {
                    $next_link = $data[$active_item + 1];
                    $limited_paging[] = '<li class="page-item"><a data-page-number="' . ($active_item + 1) . '" href="' . $next_link . '" class="page-link">&raquo;</a></li>';

                    $ready_paging_last_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => ($active_item + 1),
                            'href' => $next_link
                        ],
                        'title' => 'Next'
                    ];

                }

                if (isset($params['show_first_last'])) {
                    $limited_paging[] = '<li class="page-item"><a data-page-number="' . end($data) . '" href="' . end($data) . '" class="page-link">' . _e('Last', true) . '</a></li>';

                    $ready_paging_last_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => end($data),
                            'href' => end($data)
                        ],
                        'title' => _e('Last', true)
                    ];

                }

                if (count($limited_paging) > 2) {
                    $paging_items = $limited_paging;
                }
            }

            if (isset($params['return_as_array']) && $params['return_as_array']) {

                $ready_paging_links = array_merge($ready_paging_first_links, $ready_paging_number_links);
                $ready_paging_links = array_merge($ready_paging_links, $ready_paging_last_links);

                return $ready_paging_links;
            }

            $to_print .= implode("\n", $paging_items);

            if ($no_wrap) {
                $to_print .= '</ul>';
            } else {
                $to_print .= '</ul></div>';
            }

            return $to_print;
        }
    }

}
