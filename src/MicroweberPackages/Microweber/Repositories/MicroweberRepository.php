<?php

namespace MicroweberPackages\Microweber\Repositories;

use MicroweberPackages\Microweber\Traits\ManagesContent;
use MicroweberPackages\Microweber\Traits\ManagesModules;
use MicroweberPackages\Microweber\Traits\ManagesUrl;

//@todo move to manager class in seperate package

class MicroweberRepository
{
    use ManagesUrl;
    use ManagesContent;
    use ManagesModules;
}
