<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;
use Modules\Billing\Models\Subscription;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('refund')
                ->label('Refund Last Payment')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Refund Last Payment')
                ->modalDescription('This will process a refund for the last payment associated with this subscription. This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, Refund')
                ->visible(fn () => auth()->user()->can('manage_billing'))
                ->action(function () {
                    $record = $this->record;

                    try {
                        // Mock refund implementation
                        logger()->info('Refund initiated for subscription', [
                            'subscription_id' => $record->id,
                            'stripe_id' => $record->stripe_id,
                            'refund_amount' => $record->plan?->price ?? 0,
                            'refunded_at' => now()->toISOString(),
                        ]);

                        Notification::make()
                            ->title('Refund Processed (Mock)')
                            ->body("A refund has been initiated for subscription #{$record->id}.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Refund Failed')
                            ->body('Failed to process refund: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('cancel')
                ->label('Cancel Subscription')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Cancel Subscription')
                ->modalDescription('Are you sure you want to cancel this subscription? This will stop future billing.')
                ->modalSubmitActionLabel('Yes, Cancel')
                ->visible(fn (Subscription $record) => $record->stripe_status === 'active' || $record->stripe_status === 'trialing')
                ->action(function () {
                    $record = $this->record;

                    try {
                        // In production: Cancel via Stripe API
                        // $record->cancel();

                        $record->update(['stripe_status' => 'canceled']);

                        Notification::make()
                            ->title('Subscription Canceled')
                            ->body("Subscription #{$record->id} has been canceled.")
                            ->success()
                            ->send();

                        $this->redirect($this->getResource()::getUrl('index'));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Cancel Failed')
                            ->body('Failed to cancel subscription: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Subscription #{$this->record->id}";
    }
}
