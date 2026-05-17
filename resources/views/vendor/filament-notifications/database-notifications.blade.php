{{--
  task-2026-05-17-551f7e / AI-774 — admin notifications drawer.
  Jira: https://microweber.atlassian.net/browse/AI-774

  Override of vendor/filament/notifications/resources/views/
  database-notifications.blade.php. Designer's Round-9 audit flagged
  three defects on the empty state:

  1. No persistent header title — vendor swaps alignment to Center
     and DROPS the header slot entirely when $hasNotifications is
     false. User loses the drawer's "Notifications" chrome label.
  2. ~50%-viewport-wide empty state — centered alignment + width=md
     reads as a floating sheet rather than the slide-over drawer the
     filled state uses. Inconsistent chrome.
  3. Passive copy "Please check again later." — handled separately
     by the translation override at
     resources/lang/vendor/filament-notifications/en/database.php
     (replaces with "All caught up" + action-aware description).

  Fix shape:
   - Always pass slide-over (no Alignment::Center swap on empty state).
   - Always render the header slot with the "Notifications" title +
     unread-count badge (renders an empty badge gracefully).
   - Empty-state body shows the bell-slash icon + heading + description
     centered inside the SAME slide-over chrome, NOT in a swapped
     centered modal.
   - Mark-all-as-read + Clear actions hidden when empty (no notifications
     to act on — preserves the vendor behaviour).

  Other vendor mechanics — close-button, sticky-header, modal teleport,
  wire:poll, broadcast channel listener, pagination footer — all
  preserved byte-equivalent.
--}}
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

        {{-- AI-774: header slot is ALWAYS rendered (regardless of $hasNotifications). --}}
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
            {{-- AI-774: empty-state body inside the slide-over chrome.
                 Icon + heading + description from the translation override
                 ("All caught up" / "You'll see new comments, orders, and
                 messages here when they arrive."). --}}
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
