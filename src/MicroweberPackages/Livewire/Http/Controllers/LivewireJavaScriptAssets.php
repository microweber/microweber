<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Livewire\Http\Controllers;

use Livewire\Controllers\LivewireJavaScriptAssets as BaseLivewireJavaScriptAssets;
class LivewireJavaScriptAssets  extends BaseLivewireJavaScriptAssets
{
    use CanPretendToBeAFileButNotInCliTrait;


}
