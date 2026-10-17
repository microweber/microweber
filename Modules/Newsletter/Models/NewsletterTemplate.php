<?php

namespace Modules\Newsletter\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Database\Factories\NewsletterTemplateFactory;

class NewsletterTemplate extends Model
{
    use FiresModelLifecycleEvents;

    use HasFactory;

    public $timestamps = true;

    protected $table = 'newsletter_templates';

    protected $fillable = [
        'title',
        'text',
        'json',
    ];

    protected static function newFactory()
    {
        return NewsletterTemplateFactory::new();
    }

    public function campaigns()
    {
        return $this->hasMany(NewsletterCampaign::class, 'email_template_id');
    }
}
