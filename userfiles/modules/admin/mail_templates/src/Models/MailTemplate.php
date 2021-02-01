<?php

namespace MicroweberPackages\Admin\MailTemplates\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
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

    public $translatable = ['name','from_name', 'subject', 'message'];
}
