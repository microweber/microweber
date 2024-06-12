<?php
namespace MicroweberPackages\LiveEdit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ModuleItemSushi extends Model
{
    // Add the trait
    use Sushi;

    protected static string $optionGroup = 'general';

    protected $keyType = 'string';

    protected array $schema = [
        'id' => 'string',
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
            $settingItem = [];
            $settingItem['id'] = uniqid();
            if (isset($model->fillable) && !empty($model->fillable)) {
                foreach ($model->fillable as $fillable) {
                    if ($fillable == 'id') {
                        continue;
                    }
                    $settingItem[$fillable] = $model->$fillable;
                }
            }
            $settings[] = $settingItem;

            save_option('settings', json_encode($settings), $optionGroup);
        });

        static::deleting(function ($model) {
            $optionGroup = $model::$optionGroup;
            $settings = get_option('settings', $optionGroup);
            $settings = json_decode($settings, true);
            if ($settings) {
                $settings = array_filter($settings, function ($tab) use ($model) {
                    return $tab['id'] != $model->id;
                });
                save_option('settings', json_encode($settings), $optionGroup);
            }
        });

        static::saving(function ($model) {

            $optionGroup = $model::$optionGroup;
            $settings = get_option('settings', $optionGroup);
            $settings = json_decode($settings, true);
            if ($settings) {
                $settings = array_map(function ($tab) use ($model) {
                    if ($tab['id'] == $model->id) {
                        if (isset($model->fillable) && !empty($model->fillable)) {
                            foreach ($model->fillable as $fillable) {
                                $tab[$fillable] = $model->$fillable;
                            }
                        }
                    }
                    return $tab;
                }, $settings);
                save_option('settings', json_encode($settings), $optionGroup);
            }
        });
    }

    public function getKey()
    {
        if ($this->id) {
            return $this->id;
        }
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
                    $row = [];
                    $row['id'] = $tab['id'];
                    if (isset($this->fillable) && !empty($this->fillable)) {
                        foreach ($this->fillable as $fillable) {
                            $row[$fillable] = $tab[$fillable] ?? '';
                        }
                    }
                    $rows[] = $row;
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
