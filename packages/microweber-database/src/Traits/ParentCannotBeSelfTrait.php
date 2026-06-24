<?php

namespace MicroweberPackages\Database\Traits;

trait  ParentCannotBeSelfTrait
{
    public function updateParentFieldOnModelIfParentIsSelf()
    {

        if (isset($this->id) and $this->id) {
            if (isset($this->parent) and $this->parent == $this->id) {
                $this->parent = 0;
            }
        }

    }


    protected static function bootParentCannotBeSelfTrait()
    {

        static::saving(function ($model) {
            $model->updateParentFieldOnModelIfParentIsSelf();
        });
    }

}
