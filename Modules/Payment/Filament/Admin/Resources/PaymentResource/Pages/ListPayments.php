<?php

namespace Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        // task-2026-05-28-2f5a6c / AI-1099 — header CreateAction hides when the
        // table has zero rows so the empty-state CTA (configure payment
        // providers) is the only primary affordance on the empty page. The
        // Payment Provider Settings shortcut is downgraded to ->color('gray')
        // so the two header actions no longer render as competing primary
        // blue buttons (double-primary defect).
        return [
            Actions\CreateAction::make()
                ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists()),
            Action::make('settings')
                ->label('Payment Provider Settings')
                ->url(PaymentProviderResource::getUrl('index'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray'),
        ];
    }
}
