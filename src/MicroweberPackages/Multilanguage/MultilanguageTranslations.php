<?php
namespace MicroweberPackages\Multilanguage;

use Illuminate\Database\Eloquent\Model;

class MultilanguageTranslations extends Model
{
    protected $fillable = [
        'rel_id',
        'rel_type',
        'field_name',
        'field_value',
        'locale'
    ];

    public $timestamps = false;

}