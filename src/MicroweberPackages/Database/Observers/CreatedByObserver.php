<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */
namespace MicroweberPackages\Database\Observers;

use Illuminate\Support\Facades\Session;

class CreatedByObserver
{
    private function addCreatedEditedSession(&$model, $create = true)
    {
        $attr = $model->toArray();

        if(auth()->id() && !empty($attr)) {
            if($create) {
                //Creating
                if(array_key_exists('created_by',$attr) or $this->hasField($model,'created_by')) {
                    $model->created_by = auth()->id();
                }

                if(array_key_exists('edited_by',$attr) or $this->hasField($model,'edited_by')) {
                    $model->edited_by= auth()->id();
                }

            } else {
                //Updating
                if(array_key_exists('edited_by',$attr) or $this->hasField($model,'edited_by')) {
                    $model->edited_by= auth()->id();
                }
            }
        }

        if($this->hasField($model,'session_id')) {
            $model->session_id= Session::getId();
        }

    }


    private function hasField($model, $field){

            if ($model->getConnection()
                ->getSchemaBuilder()
                ->hasColumn($model->getTable(), $field)) {

            return true;
            }

      }

    public function saved($model)
    {

        $this->addCreatedEditedSession($model, true);
        //$model->created_by = auth()->id();
    }

    public function saving($model)
    {

        $this->addCreatedEditedSession($model, true);
        //$model->created_by = auth()->id();
    }

    public function updated($model)
    {
        $this->addCreatedEditedSession($model, false);
        //$model->edited_by= auth()->id();
    }

    public function updating($model)
    {
        $this->addCreatedEditedSession($model, false);
        //$model->edited_by= auth()->id();
    }

    public function deleted($model)
    {
    }

    public function restored($model)
    {
    }

    public function created($model)
    {
       // $this->addCreatedEditedSession($model, true);
        $model->created_by = auth()->id();
    }

    public function creating($model)
    {
        //dd(2);
       // $this->addCreatedEditedSession($model, true);
        $model->created_by = auth()->id();
    }
}
