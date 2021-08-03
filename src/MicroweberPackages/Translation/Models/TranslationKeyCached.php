<?php

namespace MicroweberPackages\Translation\Models;



use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class TranslationKeyCached extends TranslationKey
{
    use CacheableQueryBuilderTrait;
}
