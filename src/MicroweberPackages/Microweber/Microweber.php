<?php

namespace MicroweberPackages\Microweber;

use MicroweberPackages\Microweber\Traits\ManagesContent;
use MicroweberPackages\Microweber\Traits\ManagesUrl;

//@todo move to manager class in seperate package
class Microweber
{
    use ManagesUrl;
    use ManagesContent;
}
