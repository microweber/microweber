<?php

namespace Modules\HostingApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HostingPlan extends Model
{
    use HasFactory;

    protected $table = 'hosting_plans';

    protected $fillable = [
        'name',
        'description',
        'disk_space',
        'bandwidth',
        'databases',
        'ftp_accounts',
        'email_accounts',
        'subdomains',
        'parked_domains',
        'addon_domains',
        'ssl_certificates',
        'daily_backups',
        'free_domain',
        'default_server_application_type',
        'default_database_server_type',
        'default_remote_database_server_id',
        'default_server_application_settings',
        'additional_services',
        'features',
        'limitations',
    ];

    protected $casts = [
        'daily_backups' => 'boolean',
        'free_domain' => 'boolean',
        'default_server_application_settings' => 'array',
        'additional_services' => 'array',
        'features' => 'array',
        'limitations' => 'array',
    ];

    public function hostingSubscriptions()
    {
        return $this->hasMany(HostingSubscription::class, 'hosting_plan_id');
    }
}
