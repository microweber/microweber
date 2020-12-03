<?php

namespace MicroweberPackages\Database\Traits;

trait  HasCreatedByFieldsTrait {


    protected static function bootHasCreatedByFieldsTrait()
    {

        static::saving(function ($model) {
            $model->edited_by= auth()->id();
            if(!$model->created_by){
                $model->created_by= auth()->id();
            }
        });
        
        static::creating(function ($model) {
            $model->edited_by= auth()->id();
            $model->created_by= auth()->id();
        });
    }
}
