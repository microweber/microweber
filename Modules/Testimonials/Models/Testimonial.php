<?php

namespace Modules\Testimonials\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Testimonial extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Testimonials\Events\TestimonialWasCreated::class,
        'updated' => \Modules\Testimonials\Events\TestimonialWasUpdated::class,
        'deleted' => \Modules\Testimonials\Events\TestimonialWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;


    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;
    use MaxPositionTrait;


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

    public $translatable = ['name', 'content', 'client_company', 'client_role'];


    protected $casts = [
        'client_image' => ReplaceSiteUrlCast::class, //Casts like that: http://picsum.photos/400/200/ =>  {SITE_URL}400/200/
    ];

}
