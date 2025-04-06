<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Billing\Database\Factories\SubscriptionPlanGroupFactory;

class SubscriptionPlanGroup extends Model
{
    use HasFactory;

    public static function newFactory()
    {
        return SubscriptionPlanGroupFactory::new();
    }

    protected $table = 'subscription_plans_groups';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function features()
    {
        return $this->hasMany(SubscriptionPlanGroupFeature::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(SubscriptionPlan::class, 'subscription_plan_group_id');
    }
}
