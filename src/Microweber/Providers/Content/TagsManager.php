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


    public function get_values($params)
    {

        if (is_string($params)) {
            $params = parse_params($params);
        }
        if ($params == false) {
            return;
        }
        if (!isset($params['table']) or !isset($params['id'])) {
            return;
        }
        $table = $params['table'];
        $id = intval($params['id']);

        $params['table'] = $table;

        $supports_tags = false;

        if (isset($params['table'])) {
            $model = $this->app->database_manager->table($params['table']);

            $methodVariable = array($model, 'tags');
            if (is_callable($methodVariable, true, $callable_name)) {
                $supports_tags = true;
            }
        }
        if ($supports_tags) {
            $tags_return = array();
            $article = $model->whereId($id)->first();
            if ($article) {
                $tags = $article->existingTags();
                if ($tags and is_object($tags)) {
                    $tags = $tags->toArray();
                    if (!empty($tags)) {
                        foreach ($tags as $tag) {
                            if (isset($tag['name'])) {
                                $tags_return[] = $tag['name'];
                            }
                        }
                        return $tags_return;
                    }
                }
            }
        }
    }
}
