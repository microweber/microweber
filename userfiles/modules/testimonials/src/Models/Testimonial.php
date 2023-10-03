<?php
namespace MicroweberPackages\Modules\Testimonials\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{

    public $timestamps = false;

    public $fillable = [
        'name',
        'content',
        'read_more_url',
        'project_name',
        'client_company',
        'client_role',
        'client_picture',
        'client_website',
        'position'
    ];

}
