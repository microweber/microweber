<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Database\Factories\NewsletterTemplateFactory;

class NewsletterTemplate extends Model
{
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
}
