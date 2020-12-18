<?php

namespace MicroweberPackages\Database\Traits;
use Illuminate\Support\Carbon;

trait  HasTimestampsTrait {


    protected static function bootHasTimestampsTrait()
    {

        static::saving(function ($model) {

            if (isset($model->updated_at)) {
                try {
                    $carbonUpdatedAt = Carbon::parse($model->updated_at);
                    $model->updated_at = $carbonUpdatedAt->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
                }
            } else {
                $model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            }

            if (isset($model->created_at)) {

                try {
                    $carbonCreatedAt = Carbon::parse($model->created_at);
                    $model->created_at = $carbonCreatedAt->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $model->created_at = Carbon::now()->format('Y-m-d H:i:s');
                }
            } else {
                $model->created_at = Carbon::now()->format('Y-m-d H:i:s');
            }
        });
    }
}
