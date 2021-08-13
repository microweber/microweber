<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

trait FilterByTagsTrait
{
    public function allTags($tags)
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        } elseif (!is_array($tags)) {
            $tags = array($tags);
        }

        if (is_array($tags) and !empty($tags)) {
            $tags = array_values($tags);
            $this->query->withAllTags($tags);
        }

        return $this->query;
    }

    public function tags($tags)
    {
        if (!empty($tags)) {

            $dbDriver = Config::get('database.default');
            $model = $this->getModel();
            $table = $model->getTable();

            $to_search_keywords = explode(',', $tags);
            if (!empty($to_search_keywords)) {
                $this->query->join('tagging_tagged', 'tagging_tagged.taggable_id', '=', $table . '.id')->distinct();
                $this->query->where(function ($query) use ($to_search_keywords, $dbDriver) {
                    foreach ($to_search_keywords as $to_search_keyword) {
                        $to_search_keyword = trim($to_search_keyword);
                        if ($to_search_keyword != false) {
                            if ($dbDriver == 'pgsql') {
                                $query->orWhere('tagging_tagged.tag_name', 'ILIKE', '%' . $to_search_keyword . '%');
                            } else {
                                $query->orWhere('tagging_tagged.tag_name', 'LIKE', '%' . $to_search_keyword . '%');
                            }
                        }
                    }
                });
            }
        }

        return $this->query;
    }

}
