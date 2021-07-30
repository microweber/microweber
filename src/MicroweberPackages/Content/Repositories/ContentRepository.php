<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/**
 * @mixin AbstractRepository
 */
class ContentRepository extends AbstractRepository
{


    protected $searchable = [
        'id',
        'title',
        'content',
        'content_body',
        'content_type',
        'content_subtype',
        'description',
        'is_home',
        'is_shop',
        'is_deleted',
        'subtype',
        'subtype_value',
        'parent',
        'layout_file',
        'active_site_template',
        'url',
        'content_meta_title',
        'content_meta_keywords',
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Content::class;


//
//
//
//
//
    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getMedia($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {


            $item = $this->findById($id);
            if ($item) {
                $get = $item->media;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];

        });
    }


    /**
     * Find categories for content
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getCategories($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            $cats = [];


            $item = $this->findById($id);
            if ($item) {
                $get = $item->categories;
                if ($get) {
                    $cats1 = $get->toArray();
                    if ($cats1) {
                        foreach ($cats1 as $cat) {
                            if (isset($cat["parent"])) {
                                unset($cat["parent"]);
                                $cats[] = $cat;
                            }
                            $cats[] = $cat;
                        }
                    }

                    if (is_array($cats) and !empty($cats)) {
                        $cats = array_unique_recursive($cats);
                    }
                    $ready = [];
                    if ($cats) {
                        foreach ($cats as $cat) {

                            $cat_exists = get_category_by_id($cat['parent_id']);
                            if ($cat_exists) {
                                $ready[] = $cat_exists;
                            }
                        }
                    }


                    return $ready;
                }
            }
            return [];


        });
    }


    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getContentData($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {


            $item = $this->findById($id);
            if ($item) {
                $get = $item->contentData;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];


        });
    }

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getCustomFields($id)
    {
       return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {


        $item = $this->findById($id);
        if ($item) {

            $get = $item->customField;
            $ready = [];

            if ($get) {
                foreach ($get as $item) {
                    $cf_item1 = $item->fieldValue;

                    $cf_item = $item->toArray();
                    $cf_item['value'] = false;
                    $cf_item['values'] = [];
                    if (isset($cf_item['field_value'])) {
                        $vals = [];
                        if (!empty($cf_item['field_value'])) {
                            foreach ($cf_item['field_value'] as $itemv) {
                                if ($itemv['value']) {
                                    $vals [] = $itemv['value'];
                                }

                            }
                        }
                        if ($vals) {
                            $cf_item['values'] = $vals;
                            $cf_item['value'] = array_pop($vals);
                        }

                        unset($cf_item['field_value']);

                    }

                    $ready[] = $cf_item;

                }

            }

            return $ready;
        }
        return [];


        });
    }

    public function getCustomFieldsByType($id,$type)
    {
        $fields  = $this->getCustomFields($id);
        if($fields){
            foreach ($fields as $k=>$field){
                if(isset($field['type']) and $field['type']==$type){

                } else {
                  unset($fields[$k]);
                }

            }
        }

        return $fields;

    }

//
//
//    /**
//     * Filter by author attribute
//     *
//     * @return self
//     */
//    public function scopeIsShop()
//    {
//        return $this->addScopeQuery(function ($query) {
//            return $query->where('is_shop', '=', 1);
//        });
//    }

}