<?php

namespace Modules\Order\Filament\Admin\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\Filament\Actions\DeleteActionOnlyIcon;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderRefund;
use Modules\Payment\Enums\PaymentStatus;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getRefundAction(),
            DeleteActionOnlyIcon::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->size('xl')
                ->onlyIconAndTooltip()
                ->outlined(),
            Actions\EditAction::make()
                ->action('save')
                ->icon('heroicon-o-check-circle')
                ->size('xl')
                ->label('Save')
                ->color('primary'),
        ];
    }

    protected function getRefundAction(): Actions\Action
    {
        return Actions\Action::make('refund')
            ->label('Refund')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('danger')
            ->outlined()
            ->visible(fn (): bool => $this->record->is_paid && $this->record->order_status !== OrderStatus::Refunded->value)
            ->form(function (): array {
                /** @var Order $order */
                $order = $this->record;
                $totalRefunded = $order->refunds()->where('status', 'completed')->sum('amount');
                $orderAmount = (float) $order->amount;
                $refundable = max(0, $orderAmount - $totalRefunded);

                return [
                    Forms\Components\ToggleButtons::make('type')
                        ->label('Refund type')
                        ->options([
                            'full' => 'Full refund',
                            'partial' => 'Partial refund',
                        ])
                        ->icons([
                            'full' => 'heroicon-m-arrow-uturn-left',
                            'partial' => 'heroicon-m-minus-circle',
                        ])
                        ->default('full')
                        ->inline()
                        ->required()
                        ->live(),

                    Forms\Components\Placeholder::make('refundable_amount')
                        ->label('Refundable amount')
                        ->content(fn () => number_format($refundable, 2) . ' ' . ($order->currency ?? '')),

                    Forms\Components\TextInput::make('amount')
                        ->label('Refund amount')
                        ->numeric()
                        ->required()
                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        ->maxValue($refundable)
                        ->default($refundable)
                        ->prefix($order->currency ?? '$')
                        ->visible(fn (Forms\Get $get) => $get('type') === 'partial'),

                    Forms\Components\Select::make('reason')
                        ->label('Reason')
                        ->options([
                            'customer_request' => 'Customer request',
                            'defective_product' => 'Defective product',
                            'wrong_item' => 'Wrong item shipped',
                            'not_received' => 'Item not received',
                            'duplicate_order' => 'Duplicate order',
                            'other' => 'Other',
                        ])
                        ->required(),

                    Forms\Components\Textarea::make('note')
                        ->label('Internal note')
                        ->rows(2)
                        ->maxLength(1000),
                ];
            })
            ->action(function (array $data): void {
                /** @var Order $order */
                $order = $this->record;
                $totalRefunded = $order->refunds()->where('status', 'completed')->sum('amount');
                $orderAmount = (float) $order->amount;
                $refundable = max(0, $orderAmount - $totalRefunded);

                $amount = $data['type'] === 'full' ? $refundable : (float) $data['amount'];

                if ($amount <= 0 || $amount > $refundable) {
                    \Filament\Notifications\Notification::make()
                        ->title('Invalid refund amount')
                        ->body('The refund amount must be between 0.01 and ' . number_format($refundable, 2))
                        ->danger()
                        ->send();
                    return;
                }

                $payment = $order->payments()->latest()->first();

                OrderRefund::create([
                    'order_id' => $order->id,
                    'payment_id' => $payment?->id,
                    'amount' => $amount,
                    'type' => $data['type'],
                    'reason' => $data['reason'],
                    'note' => $data['note'] ?? null,
                    'status' => 'completed',
                    'refunded_by' => auth()->id(),
                ]);

                // Update payment status if there's a linked payment
                if ($payment) {
                    $payment->update(['status' => PaymentStatus::Refunded->value]);
                }

                // If fully refunded, update order status
                $newTotalRefunded = $totalRefunded + $amount;
                if ($newTotalRefunded >= $orderAmount) {
                    $order->update(['order_status' => OrderStatus::Refunded->value]);
                }

                \Filament\Notifications\Notification::make()
                    ->title('Refund processed')
                    ->body(number_format($amount, 2) . ' ' . ($order->currency ?? '') . ' refunded successfully.')
                    ->success()
                    ->send();
            })
            ->modalHeading('Process Refund')
            ->modalDescription(fn (): string => 'Order #' . $this->record->order_reference_id)
            ->modalSubmitActionLabel('Process Refund')
            ->requiresConfirmation(false);
    }

    protected function getFormActions(): array
    {
        return [
        ];
    }
}
