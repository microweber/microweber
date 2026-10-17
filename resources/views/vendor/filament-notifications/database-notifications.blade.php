
@php
    use Filament\Support\View\Components\BadgeComponent;
    use Illuminate\View\ComponentAttributeBag;

    $notifications = $this->getNotifications();
    $unreadNotificationsCount = $this->getUnreadNotificationsCount();
    $hasNotifications = $notifications->count();
    $isPaginated = $notifications instanceof \Illuminate\Contracts\Pagination\Paginator && $notifications->hasPages();
    $pollingInterval = $this->getPollingInterval();
@endphp

<div class="fi-no-database mw-no-database">
    <x-filament::modal
        close-button
        id="database-notifications"
        slide-over
        sticky-header
        teleport="body"
        width="md"
        class="fi-no-database mw-no-database"
        :attributes="
            new \Illuminate\View\ComponentAttributeBag([
                'wire:poll.' . $pollingInterval => $pollingInterval ? '' : false,
            ])
        "
    >
        @if ($trigger = $this->getTrigger())
            <x-slot name="trigger">
                {{ $trigger->with(['unreadNotificationsCount' => $unreadNotificationsCount]) }}
            </x-slot>
        @endif

        <x-slot name="header">
            <div>
                <h2 class="fi-modal-heading">
                    {{ __('filament-notifications::database.modal.heading') }}

                    @if ($unreadNotificationsCount)
                        <span
                            {{
                                (new ComponentAttributeBag)->color(BadgeComponent::class, 'primary')->class([
                                    'fi-badge fi-size-xs',
                                ])
                            }}
                        >
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </h2>

                <div class="fi-ac">
                    @if ($hasNotifications && $unreadNotificationsCount && $this->markAllNotificationsAsReadAction?->isVisible())
                        {{ $this->markAllNotificationsAsReadAction }}
                    @endif

                    @if ($hasNotifications && $this->clearNotificationsAction?->isVisible())
                        {{ $this->clearNotificationsAction }}
                    @endif
                </div>
            </div>
        </x-slot>

        @if ($hasNotifications)
            @foreach ($notifications as $notification)
                <div
                    @class([
                        'fi-no-notification-read-ctn' => ! $notification->unread(),
                        'fi-no-notification-unread-ctn' => $notification->unread(),
                    ])
                >
                    {{ $this->getNotification($notification)->inline() }}
                </div>
            @endforeach

            @if ($broadcastChannel = $this->getBroadcastChannel())
                @script
                    <script>
                        window.addEventListener('EchoLoaded', () => {
                            window.Echo.private(@js($broadcastChannel)).listen(
                                '.database-notifications.sent',
                                () => {
                                    setTimeout(
                                        () => $wire.call('$refresh'),
                                        500,
                                    )
                                },
                            )
                        })

                        if (window.Echo) {
                            window.dispatchEvent(new CustomEvent('EchoLoaded'))
                        }
                    </script>
                @endscript
            @endif

            @if ($isPaginated)
                <x-slot name="footer">
                    <x-filament::pagination :paginator="$notifications" />
                </x-slot>
            @endif
        @else

            <div class="mw-no-database-empty">
                <x-filament::icon
                    :icon="\Filament\Support\Icons\Heroicon::OutlinedBellSlash"
                    :alias="\Filament\Notifications\View\NotificationsIconAlias::DATABASE_MODAL_EMPTY_STATE"
                    class="mw-no-database-empty__icon"
                />
                <h3 class="mw-no-database-empty__heading">
                    {{ __('filament-notifications::database.modal.empty.heading') }}
                </h3>
                <p class="mw-no-database-empty__description">
                    {{ __('filament-notifications::database.modal.empty.description') }}
                </p>
            </div>
        @endif
    </x-filament::modal>
</div>
