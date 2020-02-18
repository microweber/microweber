<?php

namespace Microweber\Providers\Content;


class TagsManager
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
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

                        if ($childs) {
                            $article_tags = [];
                            $childs_chunk = array_chunk($childs, 3000);
                            foreach ($childs_chunk as $child_chunk) {
                                $get_content_tags = db_get('tagging_tagged',[
                                   'taggable_id'=>'[in]'.implode(',',$child_chunk),
                                    'no_limit'=>1,
                                    'no_cache'=>1
                                ]);
                                if ($get_content_tags) {
                                    $article_tags = array_merge($article_tags, $get_content_tags);
                                }
                            }
                            if ($return_full) {
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
                        if ($return_full) {
                            return $article->toArray();
                        }
                        foreach ($article->tags as $tag) {
                            if (is_object($tag)) {
                                $tags_return[] = $tag->name;
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
