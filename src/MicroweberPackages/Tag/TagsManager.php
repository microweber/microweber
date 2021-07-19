<?php

namespace MicroweberPackages\Tag;


class TagsManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = app();
        }
    }




//    public function all(){
//        Article::withAnyTag(['Gardening','Cooking'])->get(); // fetch articles with any tag listed
//
//        Article::withAllTags(['Gardening', 'Cooking'])->get(); // only fetch articles with all the tags
//
//        Conner\Tagging\Model\Tag::where('count', '>', 2)->get(); // return all tags used more than twice
//
//        Article::existingTags(); // return collection of all existing tags on any articles
//    }


    /**
     * @deprecated
     */
    public function get_values($params, $return_full = false)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if ($params == false) {
            return;
        }
        if (!isset($params['table'])) {
            return;
        }
        $id = false;
        $table = $params['table'];
        if (isset($params['id'])) {
            $id = intval($params['id']);
        }
        $params['table'] = $table;

        $supports_tags = false;

        $model = $this->app->database_manager->table($params['table']);
        $supports_tags = $this->app->database_manager->supports($params['table'], 'tags');

        $tags_return = array();
        if ($supports_tags) {
            if ($id) {
                $article = $model->whereId($id)->first();
                if ($article) {
                    $article_data = $article->toArray();

                    if (isset($article_data['content_type']) and $article_data['content_type'] == 'page') {

                        $childs = get_content_children($article_data['id']);
                        $article_tags = [];

                        if ($childs) {

                            $tag_slug_map = [];
                            foreach ($childs as $child_id) {
                                $get_tagging_tagged = db_get('tagging_tagged', 'taggable_id=' . $child_id);
                                if ($get_tagging_tagged) {
                                    foreach ($get_tagging_tagged as $get_tagging_tag) {
                                        $tag_slug_map[$get_tagging_tag['tag_slug']][] = $get_tagging_tag;
                                    }
                                }
                            }

                            foreach ($tag_slug_map as $tag_slug => $tag_map_data) {

                                $article_tags[] = array(
                                    'tag_name' => $tag_map_data[0]['tag_name'],
                                    'tag_slug' => $tag_slug,
                                    'count' => count($tag_slug_map[$tag_slug])
                                );
                            }

                            if ($return_full) {

                                $article_tags = array_filter($article_tags, function ($tag_item) {
                                    if (isset($tag_item['tag_name']) && isset($tag_item['tag_slug'])) {
                                        return true;
                                    }
                                    return false;
                                });

                                return $article_tags;
                            }

                            if (!empty($article_tags)) {
                                foreach ($article_tags as $tag) {
                                    if (isset($tag['tag_name'])) {
                                        $tags_return[] = $tag['tag_name'];
                                    }
                                }
                            }
                        }
                    } else {


                        if (isset($article->tags)) {
                            foreach ($article->tags as $tag) {
                                if (is_object($tag)) {
                                    $get_tag = db_get('tagging_tags', 'slug=' . $tag->slug . '&single=1'); // this is for multilanguage
                                    if ($get_tag) {
                                        $tags_return[] = $get_tag['name'];
                                    }
                                }
                            }
                        }


                        if ($return_full) {
                            if ($article->tags) {
                                $return_ready = [];
                                $article_tags = $article->tags->toArray();
                                if ($article_tags) {
                                    foreach ($article_tags as $article_tag) {
                                        $get_tagging_tagged = db_get('tagging_tagged', 'limit=1&single=1&tag_slug=' . $article_tag['slug']);
                                        if ($get_tagging_tagged) {
                                            $return_ready [] = $get_tagging_tagged;
                                        }
                                    }
                                }

                                return $return_ready;
                            } else {
                                return [];
                            }
                        }
                    }


                }

            } else {

                $article = $model->with('tagged')->first()->existingTags();

                if ($article) {
                    if ($return_full) {
                        return $article->toArray();
                    }

                    foreach ($article as $tag) {
                        if (is_object($tag)) {
                            $tags_return[] = $tag->name;
                        }
                    }
                }
            }


            if (!empty($tags_return)) {

                $tags_return = array_unique($tags_return);
                return $tags_return;
            }

        }
    }
}
