<?php

namespace Microweber\Utils\Adapters\Template\RenderHelpers;


class TemplateMetaTagsRenderer
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function render($params)
    {

        if (isset($params['layout'])) {
            $layout = $params['layout'];





            $l = $layout;
            $meta = array();
            $meta['content_image'] = '';
            $meta['description'] = '';
            if (is_home()) {
                $meta['content_url'] = site_url();
            } else {
                $meta['content_url'] = $this->app->url_manager->current(1);
            }
            $meta['og_description'] = $this->app->option_manager->get('website_description', 'website');
            $meta['og_type'] = 'website';
            $meta_content_id = PAGE_ID;
            if (CONTENT_ID > 0) {
                $meta_content_id = CONTENT_ID;
            }

            if ($meta_content_id > 0) {
                $meta = $this->app->content_manager->get_by_id($meta_content_id);
                $content_image = $this->app->media_manager->get_picture($meta_content_id);
                if ($content_image) {
                    $meta['content_image'] = $content_image;
                } else {
                    $meta['content_image'] = '';
                }
                $meta['content_url'] = $this->app->content_manager->link($meta_content_id);
                if (isset($meta['content_type'])) {
                    $meta['og_type'] = $meta['content_type'];
                    if ($meta['og_type'] != 'page' and trim($meta['subtype']) != '') {
                        $meta['og_type'] = $meta['subtype'];
                    }
                    if ($meta['description'] != false and trim($meta['description']) != '') {
                       // $meta['description'] = $meta['description'];
                    } elseif ($meta['content'] != false and trim($meta['content']) != '') {
                        $meta['description'] = str_replace("\n", ' ', $this->app->format->limit($this->app->format->clean_html(strip_tags($meta['content'])), 500));
                    }

                    if (isset($meta['description']) and $meta['description'] != '') {
                        $meta['og_description'] = $meta['description'];
                    } else {
                        $meta['og_description'] = trim($this->app->format->limit($this->app->format->clean_html(strip_tags($meta['content'])), 500));
                    }
                }
            } else {
                $meta['title'] = $this->app->option_manager->get('website_title', 'website');
                $meta['description'] = $this->app->option_manager->get('website_description', 'website');
                $meta['content_meta_keywords'] = $this->app->option_manager->get('website_keywords', 'website');
            }

            $meta['og_site_name'] = $this->app->option_manager->get('website_title', 'website');

            if (!empty($meta)) {
                if (isset($meta['content_meta_title']) and $meta['content_meta_title'] != '') {
                    $meta['title'] = $meta['content_meta_title'];
                } elseif (isset($meta['title']) and $meta['title'] != '') {
                } else {
                    $meta['title'] = $this->app->option_manager->get('website_title', 'website');
                }
                if (isset($meta['description']) and $meta['description'] != '') {
                } else {
                    $meta['description'] = $this->app->option_manager->get('website_description', 'website');
                }

                if (isset($meta['description']) and $meta['description'] != '') {
                    $meta['content_meta_description'] = strip_tags($meta['description']);
                    unset($meta['description']);
                } elseif (isset($meta['content']) and $meta['content'] != '') {
                    $meta['content_meta_description'] = strip_tags($meta['content']);
                } elseif (isset($meta['title']) and $meta['title'] != '') {
                    $meta['content_meta_description'] = strip_tags($meta['title']);
                }

                if (isset($meta['title']) and $meta['title'] != '') {
                    $meta['content_meta_title'] = strip_tags($meta['title']);
                } elseif (isset($found_mod) and $found_mod != false) {
                    $meta['content_meta_title'] = ucwords(str_replace('/', ' ', $found_mod));
                } else {
                    $meta['content_meta_title'] = ucwords(str_replace('/', ' ', $this->app->url_manager->segment(0)));
                }

                if (isset($meta['content_meta_keywords']) and $meta['content_meta_keywords'] != '') {
                } else {
                    $meta['content_meta_keywords'] = $this->app->option_manager->get('website_keywords', 'website');
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




//dd(__FILE__,$layout);

            return $layout;
        }

    }


    private function _replace_inline_values(){

//     Replaces for
//    <title>{content_meta_title}</title>
//    <meta name="keywords" content="{content_meta_keywords}">
//    <meta name="description" content="{content_meta_description}">
//    <meta property="og:title" content="{content_meta_title}">
//    <meta property="og:type" content="{og_type}">
//    <meta property="og:url" content="{content_url}">
//    <meta property="og:image" content="{content_image}">
//    <meta property="og:description" content="{og_description}">
//    <meta property="og:site_name" content="{og_site_name}">



    }
}