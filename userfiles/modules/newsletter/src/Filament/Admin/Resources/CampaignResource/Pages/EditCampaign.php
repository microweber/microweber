<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

}
