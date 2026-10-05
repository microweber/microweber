<?php

namespace Modules\Log\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    public $timestamps = true;

    protected $fillable = [
        'level',
        'message',
        'rel_type',
        'rel_id',
        'channel',
        'logged_at',
        'is_system',
        'field',
        'rel',
        'value',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];
}
