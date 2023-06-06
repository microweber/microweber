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
        return $this->hasMany(FormDataValue::class)->where('field_value', '!=', '');
    }

    public function formList()
    {
        return $this->belongsTo(FormList::class);
    }

    public function getFormDataValues()
    {
        $formDataValues = [];
        foreach($this->formDataValues()->get() as $formDataValue) {
            if (strpos(strtolower($formDataValue->field_key), 'captcha') !== false) {
                continue;
            }
            $formDataValues[] = [
                'field_name' => ucwords($formDataValue->field_name),
                'field_value' => nl2br($formDataValue->field_value),
            ];
        }

        return $formDataValues;
    }

    public function getEmail()
    {
        $dataValues = $this->formDataValues()->get();

        $email = false;
        if (!empty($dataValues)) {
            foreach ($dataValues as $dataValue) {
                if ($dataValue->field_key == 'email') {
                    $email = $dataValue->field_value;
                    break;
                }
                if ($dataValue->field_type == 'email') {
                    $email = $dataValue->field_value;
                    break;
                }
            }
        }
        if ($email) {
            return str_limit($email, 20);
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
                if (strpos($dataValue->field_key, 'subject') !== false) {
                    return $dataValue->field_value;
                }
                if (strpos($dataValue->field_key, 'message') !== false) {
                    return str_limit($dataValue->field_value, 65);
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
                    break;
                }
                if (strpos($dataValue->field_key, 'name') !== false) {
                    return $dataValue->field_value;
                    break;
                }
            }
        }

        return _e('No name', true);
    }
}
