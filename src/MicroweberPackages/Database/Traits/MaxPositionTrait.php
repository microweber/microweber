<?php

namespace MicroweberPackages\Database\Traits;

trait  MaxPositionTrait
{

    public function updateMaxPositionFieldOnModel()
    {
        $maxPosition = 0;
        if(isset($this->position)){
            //do nothing
            return $this;
        }
        if (isset($this->id) and $this->id) {

            $wasCreated = $this->wasRecentlyCreated;
            if (!$wasCreated) {
                // do nothing on existing ids
                return $this;
            }
        }


        if (!isset($this->position) && isset($this->rel_id) && isset($this->rel_type)) {

            $position = get_class($this)::where([
                ['rel_id', '=', $this->rel_id],
                ['rel_type', '=', $this->rel_type]
            ])->max('position');

            $maxPosition = intval($position) + 1;
        } else {
            $position = get_class($this)::query()->max('position');
            $maxPosition = intval($position) + 1;
        }

        $this->position = $maxPosition;

        $this->savePositionFieldWithoutEvents();

        return $this;
    }

//    public static function bootHasMediaTrait()
//    {
//        static::saved(function ($model)  {
//            foreach($model->_newMediaToAssociate as $mediaField) {
//                $model->media()->save($mediaField);
//                $mediaField->updateMaxPositionFieldOnModel();
//            }
//
//            $model->_newMediaToAssociate = []; //empty the array
//
//            $model->refresh();
//        });
//    }

    protected static function bootMaxPositionTrait()
    {
        static::saved(function ($model) {
            $model->updateMaxPositionFieldOnModel();
        });
    }

    public function savePositionFieldWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
