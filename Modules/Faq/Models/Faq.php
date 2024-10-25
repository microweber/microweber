<?php

namespace Modules\Faq\Models;


use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',

        'created_at',
        'updated_at',
    ];



}
