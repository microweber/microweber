<?php

namespace MicroweberPackages\LaravelTemplates\Models;

use Illuminate\Database\Eloquent\Model;

class SystemTemplates extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'description',
        'path',
        'version',
        'type',
        'priority',
        'sort',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get active templates.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveTemplates()
    {
        return self::where('status', 1)->get();
    }

    /**
     * Get template by name.
     *
     * @param string $name
     * @return \MicroweberPackages\LaravelTemplates\Models\SystemTemplates|null
     */
    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }
}
