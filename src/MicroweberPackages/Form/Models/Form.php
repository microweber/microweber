<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'forms_data';

    protected $casts = [
        'form_values' => 'array',
    ];
}
