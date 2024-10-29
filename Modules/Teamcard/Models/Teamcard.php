<?php

namespace Modules\Teamcard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teamcard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'file',
        'bio',
        'role',
        'website',
        'position',
        'module_id',
    ];

}
