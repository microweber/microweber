<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.welcome-widget';

    protected static bool $isLazy = false;

    public function getGreeting(): string
    {
        $user = Auth::user();
        $name = $user?->first_name ?? $user?->username ?? $user?->email ?? 'Admin';

        return "Welcome back, {$name}";
    }

    /**
     * AI-705 / task-2026-05-16-2dbae0 — actionable counters that
     * replace the prior static subtitle filler (spec §2 AD4 — the
     * "here is what is happening" copy was rejected). Each counter
     * draws from the SAME query as the matching Filament navigation
     * badge (single source of truth):
     *
     *   - new comments → CommentResource::getNavigationBadge()
     *     (is_moderated = false AND is_spam = false)
     *   - new orders   → OrderResource::getNavigationBadge()
     *     (order_status = New)
     *   - new messages → FormEntryResource visitor inbox
     *     (forms_data.is_read = 0)
     *
     * Each counter returns its `url` via the resource's own
     * `getUrl('index')` so route names stay encapsulated; if a
     * Module isn't installed (class_exists() guard) the counter
     * is silently omitted rather than breaking the widget.
     *
     * @return array<int, array{count:int,label_singular:string,label_plural:string,url:string}>
     */
    public function getCounters(): array
    {
        $counters = [];

        if (class_exists(\Modules\Comments\Models\Comment::class)
            && class_exists(\Modules\Comments\Filament\Resources\CommentResource::class)) {
            $count = 0;
            try {
                // task-2026-05-26 / AI-1107 — exclude Faker @example.* comments
                $count = (int) \Modules\Comments\Models\Comment::where('is_moderated', false)
                    ->where('is_spam', false)
                    ->where('comment_email', 'NOT LIKE', '%@example.com')
                    ->where('comment_email', 'NOT LIKE', '%@example.org')
                    ->where('comment_email', 'NOT LIKE', '%@example.net')
                    ->count();
            } catch (\Throwable $e) {
                $count = 0;
            }
            $counters[] = [
                'count' => $count,
                'label_singular' => 'new comment',
                'label_plural' => 'new comments',
                'url' => $this->safeResourceUrl(\Modules\Comments\Filament\Resources\CommentResource::class),
            ];
        }

        if (class_exists(\Modules\Order\Models\Order::class)
            && class_exists(\Modules\Order\Filament\Admin\Resources\OrderResource::class)
            && class_exists(\Modules\Order\Enums\OrderStatus::class)) {
            $count = 0;
            try {
                $count = (int) \Modules\Order\Models\Order::where(
                    'order_status',
                    \Modules\Order\Enums\OrderStatus::New->value
                )->count();
            } catch (\Throwable $e) {
                $count = 0;
            }
            $counters[] = [
                'count' => $count,
                'label_singular' => 'new order',
                'label_plural' => 'new orders',
                'url' => $this->safeResourceUrl(\Modules\Order\Filament\Admin\Resources\OrderResource::class),
            ];
        }

        if (class_exists(\Modules\Form\Models\FormData::class)
            && class_exists(\Modules\Form\Filament\Resources\FormEntryResource::class)) {
            $count = 0;
            try {
                $count = (int) \Modules\Form\Models\FormData::where('is_read', 0)->count();
            } catch (\Throwable $e) {
                $count = 0;
            }
            $counters[] = [
                'count' => $count,
                'label_singular' => 'new message',
                'label_plural' => 'new messages',
                'url' => $this->safeResourceUrl(\Modules\Form\Filament\Resources\FormEntryResource::class),
            ];
        }

        return $counters;
    }

    public function isAllCaughtUp(): bool
    {
        $counters = $this->getCounters();
        if (empty($counters)) {
            // No module installed at all → render nothing rather
            // than "All caught up." (which would be misleading).
            return false;
        }
        foreach ($counters as $counter) {
            if (($counter['count'] ?? 0) > 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Wrap `$resource::getUrl('index')` so a Filament boot failure
     * (e.g. running this widget in a test surface without the panel
     * registered) degrades to a harmless `#` href instead of an
     * unhandled exception.
     */
    private function safeResourceUrl(string $resourceClass): string
    {
        try {
            return $resourceClass::getUrl('index');
        } catch (\Throwable $e) {
            return '#';
        }
    }
}
