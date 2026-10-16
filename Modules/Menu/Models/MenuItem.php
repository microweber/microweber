<?php

namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Menu
{
    // Lifecycle events so other modules can react to MenuItem changes.
    protected $dispatchesEvents = [
        'created' => \Modules\Menu\Events\MenuItemWasCreated::class,
        'updated' => \Modules\Menu\Events\MenuItemWasUpdated::class,
        'deleted' => \Modules\Menu\Events\MenuItemWasDeleted::class,
    ];

    protected $table = 'menus';

    protected $primaryKey = 'id';

    protected $attributes = [
        'item_type' => 'menu_item',
    ];


    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('item_type', '=', 'menu_item');
    }
}
