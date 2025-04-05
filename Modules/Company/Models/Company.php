<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public $fillable = [
        'name',
        'website',
        'description',
        'email',
        'logo',

        'country',
        'city',
        'zip',
        'address',
        'phone',
        'company_number',
        'vat_number',

        'rel_type',
        'rel_id',
    ];

}
