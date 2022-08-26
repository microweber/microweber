<?php

namespace MicroweberPackages\Database\Traits;

use MicroweberPackages\User\Models\User;

trait HasCreatedByFieldsTrait {

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

    public function author()
    {
        return $this->hasOne(User::class, 'created_by');
    }

    public function authorName()
    {
        return user_name($this->created_by);
    }

}
