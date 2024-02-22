<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Analytics;
use Illuminate\Support\Facades\Cookie;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;


class TemplateMetaTagsPlaceholderReplacer
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;
    public $websiteOptions = [];

    public function __construct($app = null)
    {
        $this->app = $app;
        $this->websiteOptions = app()->option_repository->getWebsiteOptions();
    }

    public function render($params)
    {

        if (isset($params['layout'])) {
            $layout = $params['layout'];
            $meta_content_id = 0;
            $meta_category_id = 0;
            $l = $layout;
            $meta = array();
            $meta['content_image'] = '';
            $meta['description'] = '';
            if (is_home()) {
                $meta['content_url'] = site_url();
            } else {
                $meta['content_url'] = app()->url_manager->current(1);
            }
            $meta['og_description'] = $this->websiteOptions['website_description'];
            $meta['og_type'] = 'website';
            if (isset($params['content_id']) and $params['content_id']) {
                $meta_content_id = $params['content_id'];
            } else {
                $meta_content_id = page_id();
                if (content_id() > 0) {
                    $meta_content_id = content_id();
                }
            }
            if (post_id() == 0) {

                if (isset($params['category_id']) and $params['category_id']) {
                    $meta_category_id = $params['category_id'];
                } else {
                    if (category_id() > 0) {
                        $meta_category_id = category_id();
                    }
                }
            }


            $useFromContentId = 0;
            $useFromCategoryId = 0;
            if ($meta_category_id > 0) {
                $useFromCategoryId = $meta_category_id;
            }

            if ($meta_content_id > 0) {
                $useFromCategoryId = 0;
                $useFromContentId = $meta_content_id;
            }


            if ($useFromCategoryId > 0) {
                $meta_category_data = app()->category_manager->get_by_id($useFromCategoryId);

                if ($meta_category_data) {
                    $meta['title'] = $meta_category_data['title'];
                    $meta['description'] = $meta_category_data['description'];
                    if (isset($meta_category_data['category_meta_title']) and $meta_category_data['category_meta_title'] != '') {
                        $meta['title'] = $meta_category_data['category_meta_title'];
                    }
                    if (isset($meta_category_data['category_meta_description']) and $meta_category_data['category_meta_description'] != '') {
                        $meta['description'] = $meta_category_data['category_meta_description'];
                    }

                    if (isset($meta_category_data['category_meta_keywords']) and $meta_category_data['category_meta_keywords'] != '') {
                        $meta['content_meta_keywords'] = $meta_category_data['category_meta_keywords'];
                    }
                    $meta['og_description'] = $meta['description'];

                    $content_image = app()->media_manager->get_picture($meta_category_id, 'category');
                    if ($content_image) {
                        $meta['content_image'] = $content_image;
                        $meta['og_image'] = $content_image;
                    }
                }
            } else if ($useFromContentId > 0) {
                $meta = app()->content_manager->get_by_id($useFromContentId);
                $content_image = app()->media_manager->get_picture($useFromContentId);
                $cont_id = get_content_by_id($useFromContentId);

                if ($content_image) {
                    $meta['content_image'] = $content_image;
                } else {
                    $meta['content_image'] = '';

                    if ($cont_id and isset($cont_id['content']) and trim($cont_id['content']) != '') {
                        $img = app()->media_manager->get_first_image_from_html(html_entity_decode($cont_id['content']));

                        if ($img != false) {
                            $surl = app()->url_manager->site();
                            $img = app()->format->replace_once('{SITE_URL}', $surl, $img);
                            $meta['content_image'] = $img;
                        }

                    }
                }
//                if ($cont_id and isset($cont_id['content_body']) and $cont_id['content_body']) {
//                    $meta['description'] = str_replace("\n", ' ', app()->format->limit(strip_tags($cont_id['content_body']), 500));
//                } else if (isset($meta['description']) and $meta['description'] != '') {
//                    $meta['description'] = str_replace("\n", ' ', app()->format->limit(strip_tags($meta['description']), 500));
//                } else if (isset($meta['content']) and $meta['content'] != '') {
//                    $meta['description'] = str_replace("\n", ' ', app()->format->limit(strip_tags($meta['content']), 500));
//                } else {
//                    $meta['description'] = '';
//                }


                $meta['content_url'] = app()->content_manager->link($meta_content_id);
                if (isset($meta['content_type'])) {
                    $meta['og_type'] = $meta['content_type'];
                    if ($meta['content_type'] == 'post') {
                        $meta['og_type'] = 'article';

                    } elseif ($meta['og_type'] != 'page' and trim($meta['subtype']) != '') {
                        $meta['og_type'] = $meta['subtype'];
                    }
                    if ($meta['content_type'] == 'product') {
                        // fetch sku, currency, price for product structured data
                        $meta['product_currency'] = app()->option_manager->get('currency', 'payments');
                        $product_price = app()->shop_manager->get_product_price($meta_content_id);
                        $meta['product_price'] = $product_price;
                        $product_fields = app()->fields_manager->get(['rel_type' => 'content', 'rel_id' => $meta_content_id, 'return_full' => true]);
                        $meta['product_sku'] = '';
                        if (empty(!$product_fields)) {
                            foreach ($product_fields as $k => $field_data) {
                                if ($field_data['name_key'] == 'sku' && !empty($field_data['value'])) {
                                    $meta['product_sku'] = $field_data['value'];
                                    break;
                                }
                            }
                        }
                    }

                    if ($meta['description'] != false and trim($meta['description']) != '') {
                        //  $meta['description'] = $meta['description'];
                    } elseif ($meta['content'] != false and trim($meta['content']) != '') {
                        $meta['description'] = str_replace("\n ", ' ', app()->format->limit(strip_tags($meta['content']), 1500));
                    } elseif ($meta['content_body'] != false and trim($meta['content_body']) != '') {
                        $meta['description'] = str_replace("\n ", ' ', app()->format->limit(strip_tags($meta['content_body']), 1500));
                    }

                    if (isset($meta['description']) and $meta['description'] != '') {
                        $meta['og_description'] = $meta['description'];
                    } else {
                        if ($meta['content']) {
                            $meta['og_description'] = trim(app()->format->limit(strip_tags($meta['content']), 500));
                        }
                    }

                }
            } else {
                $meta['title'] = $this->websiteOptions['website_title'];
                $meta['description'] = $this->websiteOptions['website_description'];
                $meta['content_meta_keywords'] = $this->websiteOptions['website_keywords'];
            }

            $meta['og_site_name'] = $this->websiteOptions['website_title'];

            if (!empty($meta)) {
                if (isset($meta['content_meta_title']) and $meta['content_meta_title'] != '') {
                    $meta['title'] = $meta['content_meta_title'];
                } elseif (isset($meta['title']) and $meta['title'] != '') {
                } else {
                    $meta['title'] = $this->websiteOptions['website_title'];
                }
                if (isset($meta['description']) and $meta['description'] != '') {
                } else {
                    $meta['description'] = $this->websiteOptions['website_description'];

                }

                if (isset($meta['description']) and $meta['description'] != '') {
                    $meta['content_meta_description'] = $meta['description'];
                } elseif (isset($meta['content_meta_description'])) {
                    $meta['content_meta_description'] = content_description($meta['id']);
                } else {
                    $meta['content_meta_description'] = $this->websiteOptions['website_description'];
                }

                if (!isset($meta['og_description']) and isset($meta['content_meta_description'])) {
                    $meta['og_description'] = $meta['content_meta_description'];
                }


                if (isset($meta['title']) and $meta['title'] != '') {
                    $meta['content_meta_title'] = strip_tags($meta['title']);
                } elseif (isset($found_mod) and $found_mod != false) {
                    $meta['content_meta_title'] = ucwords(str_replace('/', ' ', $found_mod));
                } else {
                    $meta['content_meta_title'] = ucwords(str_replace('/', ' ', app()->url_manager->segment(0)));
                }

                if (isset($meta['content_meta_keywords']) and $meta['content_meta_keywords'] != '') {
                } else {
                    $meta['content_meta_keywords'] = $this->websiteOptions['website_keywords'];
                }

                if (is_array($meta)) {
                    foreach ($meta as $key => $item) {
                        if (is_string($item)) {
                            $item = html_entity_decode($item);
                            $item = strip_tags($item);
                            $item = str_replace('&amp;zwnj;', ' ', $item);
                            $item = str_replace('"', ' ', $item);
                            $item = str_replace("'", ' ', $item);

                            $item = str_replace('>', '', $item);
                            $item = str_replace('&amp;quot;', ' ', $item);
                            $item = str_replace('quot;', ' ', $item);
                            $item = str_replace('&amp;', ' ', $item);
                            $item = str_replace('amp;', ' ', $item);
                            $item = str_replace('nbsp;', ' ', $item);
                            $item = str_replace('#039;', ' ', $item);
                            $item = str_replace('&amp;nbsp;', ' ', $item);
                            $item = str_replace('&', ' ', $item);
                            $item = str_replace(';', ' ', $item);
                            $item = str_replace('  ', ' ', $item);
                            $item = str_replace(' ', ' ', $item);
                            $l = str_replace('{' . $key . '}', $item, $l);
                        } elseif ($item == false) {
                            $l = str_replace('{' . $key . '}', '', $l);
                        }
                    }
                }
            }


            $layout = $l;


            return $layout;
        }

    }


}
