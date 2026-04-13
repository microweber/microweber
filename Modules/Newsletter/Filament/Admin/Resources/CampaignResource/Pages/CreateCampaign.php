<?php

namespace Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.admin-newsletter.pages.edit-campaign.{id}', $this->record->id);
    }
}
