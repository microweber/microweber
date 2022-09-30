<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemComponent extends AutoCompleteMultipleItemsComponent
{
    public $perPage = 10;
    public $total = 0;
}
