<?php
namespace MicroweberPackages\Modules\Tabs\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Tab extends Model
{
    // Add the trait
    use Sushi;

    protected static string $optionGroup = 'general';

    protected $keyType = 'string';

    protected $fillable = ['title', 'icon'];

    protected array $schema = [
        'id' => 'string',
        'title' => 'datetime',
        'icon' => 'integer',
        'position' => 'integer',
    ];

    public static function queryForOptionGroup(string $optionGroup = 'general'): Builder
    {
        static::$optionGroup = $optionGroup;

        return static::query();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $optionGroup = $model::$optionGroup;
            $settings = get_option('settings', $optionGroup);
            $settings = json_decode($settings, true);
            if (!$settings && !is_array($settings)) {
                $settings = [];
            }
            $itemId = uniqid();
            $settings[] = [
                'id' => $itemId,
                'title' => $model->title,
                'icon' => $model->icon,
                'position' => 0,
            ];
            save_option('settings', json_encode($settings), $optionGroup);
        });

        static::updating(function ($model) {
            $optionGroup = $model::$optionGroup;
            $settings = get_option('settings', $optionGroup);
            $settings = json_decode($settings, true);
            if ($settings) {
                $settings = array_map(function ($tab) use ($model) {
                    if ($tab['id'] == $model->id) {
                        $tab['title'] = $model->title;
                        $tab['icon'] = $model->icon;
                        $tab['position'] = $model->position;
                    }
                    return $tab;
                }, $settings);
                save_option('settings', json_encode($settings), $optionGroup);
            }
        });
    }

    public function getKey()
    {
        return 'id';
    }

    public function getRows()
    {
        $settings = get_option('settings', static::$optionGroup);
        if ($settings) {
            $settings = json_decode($settings, true);
            if (!empty($settings) && is_array($settings)) {
                $rows = [];
                foreach ($settings as $tab) {
                    $rows[] = [
                        'id' => $tab['id'],
                        'title' => $tab['title'] ?? '',
                        'icon' => $tab['icon'] ?? '',
                        'position' => $tab['position'] ?? '',
                    ];
                }
                // Sort by position
                usort($rows, function ($a, $b) {
                    return $a['position'] <=> $b['position'];
                });
                return $rows;
            }
        }

        return [];

    }
}
