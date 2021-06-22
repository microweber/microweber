<?php
namespace MicroweberPackages\Content;

class ContentField extends \MicroweberPackages\Database\BaseModel
{
    public $table = 'content_fields';
    public $table_drafts = 'content_fields_drafts';

    public $cacheTagsToClear = ['content', 'content_fields_drafts', 'content_fields', 'categories'];

}
