<?php

namespace Coolsam\Modules;

abstract class Resource extends \Filament\Resources\Resource
{
    use \Coolsam\Modules\Traits\CanAccessTrait;
}
