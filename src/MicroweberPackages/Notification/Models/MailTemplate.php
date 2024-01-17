<?php

namespace MicroweberPackages\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class MailTemplate extends Model
{

    use HasMultilanguageTrait;

  //  protected $appends = ['type', 'name', 'subject'];

    public $timestamps = false;

    public $fillable = [
        "type",
        "name",
        "subject",
        "message",
        "from_name",
        "from_email",
        "custom",
        "copy_to",
        "plain_text",
        "is_active",
    ];

    public $translatable = ['subject', 'message', ''];
}
