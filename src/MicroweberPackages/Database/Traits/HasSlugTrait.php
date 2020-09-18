<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Database\Traits;

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

    protected function generateSlugOnCreate()
    {
        if (!empty($this->title)) {

            $slug = mw()->url_manager->slug($this->title);
            if ($slug == '') {
                $slug = date('Y-M-d-His');
            }

            $this->url = $slug;
        }
    }

    protected function generateSlugOnUpdate()
    {
        if (empty($this->url)) {

            $slug = mw()->url_manager->slug($this->title);
            if ($slug == '') {
                $slug = date('Y-M-d-His');
            }

            $this->url = $slug;/**/
        }
    }

}