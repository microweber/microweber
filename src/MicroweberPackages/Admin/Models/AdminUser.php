<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 3:13 PM
 */


namespace MicroweberPackages\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Model
{
    use Notifiable;

    public $table = 'users';

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_admin', 1);
    }

}


