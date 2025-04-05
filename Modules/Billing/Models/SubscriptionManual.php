<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionManual extends Model
{
    public $timestamps = false;
    protected $table = 'subscriptions_manual';

    protected $casts = [
        'activate_free_trial_after_date'=>'datetime',
    ];

}
