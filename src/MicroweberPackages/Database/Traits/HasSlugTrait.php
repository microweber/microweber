<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Database\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Models\Content;

trait HasSlugTrait
{
    protected static function bootHasSlugTrait()
    {
        static::creating(function ($model) {

            $model->generateSlugOnCreate();
        });

        static::updating(function ($model) {
            $model->generateSlugOnUpdate();
        });


    }

    protected function checkSlugExists($slug)
    {

        $checkCatWithSlug = mw()->category_manager->get_by_url($slug);
        if ($checkCatWithSlug) {
            return true;
        }

        $checkContentSlugExists = Content::where('url', $slug)->first();
        if ($checkContentSlugExists) {
            return true;
        }
        $checkContentSlugExists = DB::table('categories')->where('url', $slug)->where('id', '!=', $this->id)->first();
        if ($checkContentSlugExists) {
            return true;
        }
        return false;
    }

    protected function generateSlugOnCreate()
    {


        $attrs = $this->getAttributes();
        $hasUrl = $attrs['url'] ?? null;
        $hasTitle = $attrs['title'] ?? null;

        if (empty($hasUrl)) {
            if (!empty($hasTitle)) {
                $title = $hasTitle;
                $title = trim($title);
                $title = strip_tags($title);
                $title = strtolower($title);
                $slug = mw()->url_manager->slug($title);
                if ($slug == '') {
                    $slug = date('Y-M-d-His');
                }

                if ($this->checkSlugExists($slug)) {
                    $slug = $slug . date('YmdHis');
                }

                $this->url = $slug;
            }

        } else {
            $url = $hasUrl;
            $url = trim($url);
            $url = strip_tags($url);
            $url = strtolower($url);
            $slug = mw()->url_manager->slug($url);
            if ($this->checkSlugExists($slug)) {
                $slug = $slug . date('YmdHis');
            }

            $this->url = $slug;
        }
    }

    protected function generateSlugOnUpdate()
    {

        $attrs = $this->getAttributes();
        $hasUrl = $attrs['url'] ?? null;
        $hasTitle = $attrs['title'] ?? null;

        if (empty($hasUrl)) {

            $slug = mw()->url_manager->slug($hasUrl);
            if ($slug == '') {
                $slug = date('Y-M-d-His');
            }

            if ($this->checkSlugExists($slug)) {
                $slug = $slug . date('YmdHis');
            }

            $this->url = $slug;
        }
    }

}
