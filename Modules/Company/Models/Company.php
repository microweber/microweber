<?php

namespace Modules\Company\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\Database\Factories\CompanyFactory;

class Company extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Company\Events\CompanyWasCreated::class,
        'updated' => \Modules\Company\Events\CompanyWasUpdated::class,
        'deleted' => \Modules\Company\Events\CompanyWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

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
