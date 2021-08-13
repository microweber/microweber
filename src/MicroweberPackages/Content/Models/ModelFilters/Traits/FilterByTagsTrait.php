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
    public function tags($tags)
    {
        if (!empty($tags)) {

            $dbDriver = Config::get('database.default');
            $model = $this->getModel();
            $table = $model->getTable();

            $to_search_keywords = explode(',', $tags);
            if (!empty($to_search_keywords)) {
                $this->query->join('tagging_tagged', 'tagging_tagged.taggable_id', '=', $table . '.id')->distinct();
                foreach ($to_search_keywords as $to_search_keyword) {
                    $to_search_keyword = trim($to_search_keyword);
                    if ($to_search_keyword != false) {
                        if ($dbDriver == 'pgsql') {
                            $this->query->orWhere('tagging_tagged.tag_name', 'ILIKE', '%' . $to_search_keyword . '%');
                        } else {
                            $this->query->orWhere('tagging_tagged.tag_name', 'LIKE', '%' . $to_search_keyword . '%');
                        }
                    }
                }
            }
        }

        return $this->query;
    }

}
