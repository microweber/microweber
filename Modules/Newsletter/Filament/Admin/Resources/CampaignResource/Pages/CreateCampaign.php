<?php

namespace Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;
}