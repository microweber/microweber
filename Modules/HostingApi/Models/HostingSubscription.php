<?php

namespace Modules\HostingApi\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Models\Customer;

class HostingSubscription extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\HostingApi\Events\HostingSubscriptionWasCreated::class,
        'updated' => \Modules\HostingApi\Events\HostingSubscriptionWasUpdated::class,
        'deleted' => \Modules\HostingApi\Events\HostingSubscriptionWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    protected $table = 'hosting_subscriptions';

    protected $fillable = [
        'customer_id',
        'hosting_plan_id',
        'domain',
        'system_username',
        'system_password',
        'status',
        'description',
        'setup_date',
        'expiry_date',
    ];

    protected $casts = [
        'setup_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function hostingPlan()
    {
        return $this->belongsTo(HostingPlan::class, 'hosting_plan_id');
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, 'hosting_subscription_id');
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
        $this->domains()->update(['status' => 'suspended']);
    }

    public function unsuspend(): void
    {
        $this->update(['status' => 'active']);
        $this->domains()->update(['status' => 'active']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
