<?php

namespace Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;

class ManageCampaigns extends ManageRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
              Actions\Action::make('create')
                ->label('Create Campaign')
                ->url(route('filament.admin-newsletter.pages.create-campaign')),
        ];
    }
}
