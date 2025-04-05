<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public $fillable = [
        'name',
        'company_number',
        'vat_number',
        'phone',
        'email',
        'address',
        'city',
        'zip',
        'country',
        'website',
        'logo',
        'rel_type',
        'rel_id',
    ];

}
