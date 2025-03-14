<?php

namespace MicroweberPackages\LaravelModules\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class SystemModulesSushi extends Model
{
    use Sushi;

    protected $table = 'system_modules_sushi';

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
        'icon'
    ];

    /**
     * Get the schema for the model.
     *
     * @return array
     */
    public function getSchema()
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'alias' => 'string',
            'description' => 'string',
            'path' => 'string',
            'version' => 'string',
            'type' => 'string',
            'priority' => 'integer',
            'sort' => 'integer',
            'status' => 'boolean',

            'icon' => 'string',
        ];
    }

    /**
     * Get the rows for the model.
     *
     * @return array
     */
    public function getRows()
    {
        if (!app()->bound('modules')) {
            return [];
        }

        $modules = app()->modules->allEnabled();
        $rows = [];

        foreach ($modules as $module) {
            $rows[] = [
                'id' => crc32($module->getName()),
                'name' => $module->getName(),
                'alias' => $module->getLowerName(),
                'description' => $module->getDescription(),
                'path' => $module->getPath(),
                'version' => $module->get('version', 'dev'),
                'type' => $module->get('type', '1'),
                'priority' => (int) $module->get('priority', 1024),
                'sort' => (int) $module->get('order', 0),
                'status' => true,
                'icon' => $this->getModuleIcon($module),
            ];
        }

        return $rows;
    }

    /**
     * Get the module icon.
     *
     * @param object $module
     * @return string
     */
    protected function getModuleIcon($module)
    {
        $iconPath = $module->getPath() . '/resources/svg/icon.svg';
        if (file_exists($iconPath)) {
            return file_get_contents($iconPath);
        }

        return '';
    }

    /**
     * Get the admin URL for the module.
     *
     * @return string|null
     */
    public function adminUrl()
    {
        if (app()->bound('modules')) {
            $module = app()->modules->findOrFail($this->name);
            if ($module) {
                return $module->get('admin_url');
            }
        }

        return null;
    }

    /**
     * Get the inline icon for the module.
     *
     * @return string
     */
    public function getIconInline()
    {
        return $this->icon;
    }

    /**
     * Get a module by name.
     *
     * @param string $name
     * @return static|null
     */
    public static function getByName($name)
    {
        return static::where('name', $name)->first();
    }
}
