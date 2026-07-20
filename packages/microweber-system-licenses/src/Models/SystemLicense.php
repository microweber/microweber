<?php

namespace MicroweberPackages\SystemLicenses\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class SystemLicense extends Model
{
    use CacheableQueryBuilderTrait;

    protected $table = 'system_licenses';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'local_key',
        'local_key_hash',
        'registered_name',
        'company_name',
        'domains',
        'status',
        'product_id',
        'service_id',
        'billing_cycle',
        'reg_on',
        'due_on',
        'created_by',
        'edited_by',
    ];

    protected $casts = [
        'product_id'  => 'integer',
        'service_id'  => 'integer',
        'created_by'  => 'integer',
        'edited_by'   => 'integer',
        'reg_on'      => 'datetime',
        'due_on'      => 'datetime',
    ];
}