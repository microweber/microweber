<?php

namespace Modules\HostingApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;

    protected $table = 'hosting_domains';

    protected $fillable = [
        'domain',
        'ip',
        'hosting_subscription_id',
        'server_application_type',
        'server_application_settings',
        'status',
        'is_main',
        'document_root',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'server_application_settings' => 'array',
    ];

    protected $attributes = [
        'status' => 'active',
        'is_main' => false,
        'ip' => '',
    ];

    public function hostingSubscription()
    {
        return $this->belongsTo(HostingSubscription::class, 'hosting_subscription_id');
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
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
