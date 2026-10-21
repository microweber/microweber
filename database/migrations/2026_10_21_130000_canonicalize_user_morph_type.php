<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Collapse the two user class names onto one polymorphic identity.
 *
 * The auth provider instantiates App\Models\User while the rest of the CMS
 * uses the base MicroweberPackages\User\Models\User — the same user, but
 * Eloquent keyed polymorphic rows by the concrete class, so roles assigned in
 * admin (base class) were invisible to the logged-in user (subclass) and a
 * chunk of notifications were keyed to the wrong class. User::getMorphClass()
 * now pins the base class for both; this realigns the pre-existing rows that
 * were stored under the subclass so nothing is orphaned.
 */
return new class extends Migration
{
    private const FROM = 'App\\Models\\User';
    private const TO = 'MicroweberPackages\\User\\Models\\User';

    /** @var array<string,string> table => morph-type column */
    private array $targets = [
        'model_has_roles' => 'model_type',
        'model_has_permissions' => 'model_type',
        'notifications' => 'notifiable_type',
        'personal_access_tokens' => 'tokenable_type',
    ];

    public function up(): void
    {
        foreach ($this->targets as $table => $column) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                DB::table($table)->where($column, self::FROM)->update([$column => self::TO]);
            }
        }
    }

    public function down(): void
    {
        // Intentionally irreversible: the subclass/base split this corrects is
        // a bug, not a state worth restoring.
    }
};
