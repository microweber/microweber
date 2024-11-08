<?php

namespace Modules\Testimonials\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'content',
        'client_image',
        'client_company',
        'client_role',
        'client_website',
        'position',
        'rel_id',
        'rel_type',
    ];
}
