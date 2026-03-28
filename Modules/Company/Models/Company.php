<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }

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
