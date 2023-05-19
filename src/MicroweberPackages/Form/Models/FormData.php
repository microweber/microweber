<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $table = 'forms_data';

    public static function boot() {
        parent::boot();
        static::deleting(function($formData) {
            $formData->formDataValues()->delete();
        });
    }

    public function formDataValues()
    {
        return $this->hasMany(FormDataValue::class);
    }

    public function formList()
    {
        return $this->belongsTo(FormList::class);
    }

    public function getEmail()
    {
        $dataValues = $this->formDataValues()->get();
        if (!empty($dataValues)) {
            foreach ($dataValues as $dataValue) {
                if ($dataValue->field_key == 'email') {
                    return $dataValue->field_value;
                }
            }
        }

        return _e('No email', true);
    }

    public function getSubject()
    {
        $dataValues = $this->formDataValues()->get();
        if (!empty($dataValues)) {
            foreach ($dataValues as $dataValue) {
                if ($dataValue->field_key == 'subject') {
                    return $dataValue->field_value;
                }
            }
        }
        return _e('No subject', true);
    }

    public function getFullName()
    {
        $dataValues = $this->formDataValues()->get();
        if (!empty($dataValues)) {
            foreach ($dataValues as $dataValue) {
                if ($dataValue->field_key == 'name') {
                    return $dataValue->field_value;
                }
            }
        }

        return _e('No name', true);
    }
}
