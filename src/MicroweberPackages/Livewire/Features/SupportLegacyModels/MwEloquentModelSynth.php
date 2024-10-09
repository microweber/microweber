<?php

namespace MicroweberPackages\Livewire\Features\SupportLegacyModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Features\SupportLegacyModels\EloquentModelSynth;

class MwEloquentModelSynth extends EloquentModelSynth
{
    protected function loadModel($meta): ?Model
    {
        $class = $meta['class'];

        // If no alias found, this returns `null`
        $aliasClass = Relation::getMorphedModel($class);

        if (! is_null($aliasClass)) {
            $class = $aliasClass;
        }

        if (isset($meta['key'])) {
            $model = new $class;

            if (isset($meta['connection'])) {
                $model->setConnection($meta['connection']);
            }

            $query = $model->newQueryForRestoration($meta['key']);

            if (isset($meta['relations'])) {
                $query->with($meta['relations']);
            }

            $model = $query->first();
        } else {
            $model = new $class();
        }
//
//        if(empty($model)){
//            dd($meta);
//        }

//        if(empty($model)){
//            $model = new $class();
//            if (isset($meta['key'])) {
//                $model->id = $meta['key'];
//            }
//
//        }
        return $model;
    }

}
