<?php

namespace MicroweberPackages\Livewire\Features\SupportLegacyModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Features\SupportLegacyModels\EloquentModelSynth;

class MwEloquentModelSynth extends EloquentModelSynth
{
    /**
     * @param array{class: class-string<Model>, key?: int|string|array<int|string>, connection?: string|null, relations?: list<string>} $meta
     */
    protected function loadModel($meta): ?Model
    {
        $class = $meta['class'];

        // If no alias found, this returns `null`. Morph-map values are model class names.
        /** @var class-string<Model>|null $aliasClass */
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
