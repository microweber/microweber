<?php

namespace MicroweberPackages\LaravelModules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleDependency extends Model
{
    use HasFactory;

    protected $table = 'module_dependencies';

    protected $fillable = [
        'module_name',
        'dependency_module_name',
        'version_constraint',
        'dependency_type',
        'is_optional',
        'description',
    ];

    protected $casts = [
        'is_optional' => 'boolean',
    ];

    const TYPE_REQUIRE = 'require';
    const TYPE_CONFLICT = 'conflict';
    const TYPE_SUGGEST = 'suggest';
    const TYPE_REPLACE = 'replace';

    /**
     * Get the module that has this dependency
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_name', 'name');
    }

    /**
     * Get the dependency module
     */
    public function dependencyModule(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'dependency_module_name', 'name');
    }

    /**
     * Get valid dependency types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_REQUIRE,
            self::TYPE_CONFLICT,
            self::TYPE_SUGGEST,
            self::TYPE_REPLACE,
        ];
    }

    /**
     * Check if this is a hard requirement
     */
    public function isRequired(): bool
    {
        return $this->dependency_type === self::TYPE_REQUIRE && !$this->is_optional;
    }

    /**
     * Check if this is a conflict
     */
    public function isConflict(): bool
    {
        return $this->dependency_type === self::TYPE_CONFLICT;
    }
}
