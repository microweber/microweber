<?php

namespace MicroweberPackages\LaravelModules\Models;

use Illuminate\Database\Eloquent\Model;

class SystemModules extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_modules';

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
     * Get active modules.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveModules()
    {
        return self::where('status', 1)->get();
    }

    /**
     * Get module by name.
     *
     * @param string $name
     * @return \MicroweberPackages\LaravelModules\Models\SystemModules|null
     */
    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }
}
