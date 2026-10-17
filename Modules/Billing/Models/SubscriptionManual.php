<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class SubscriptionManual extends Model
{
    use FiresModelLifecycleEvents;

    protected $fillable = [
        'user_id',
        'activate_free_trial_after_date',
        'auto_activate_free_trial_after_date'
    ];
    public $timestamps = false;
    protected $table = 'subscriptions_manual';

    protected $casts = [
        'activate_free_trial_after_date'=>'datetime',
    ];

}
