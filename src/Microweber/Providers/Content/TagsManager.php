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


        $model = $this->app->database_manager->table($params['table']);

        $methodVariable = array($model, 'tags');
        if (is_callable($methodVariable, true, $callable_name)) {
            $supports_tags = true;
        }

        if ($supports_tags) {
            $tags_return = array();
            $article = $model->whereId($id)->first();

            if ($article) {
                foreach ($article->tags as $tag) {
                    if (is_object($tag)) {
                        $tags_return[] = $tag->name;
                    }
                }
                if (!empty($tags_return)) {
                    return $tags_return;
                }
            }
        }
    }
}
