<?php

namespace MicroweberPackages\ContentField\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;

class TranslateContentField extends TranslateTable
{

    protected $relId = 'id';
    protected $relType = 'content_fields';

    protected $columns = [
        'value'
    ];

}
