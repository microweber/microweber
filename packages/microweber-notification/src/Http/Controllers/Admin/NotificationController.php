<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Http\Controllers\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\Notification\Models\Notification;

/**
 * Admin notification listing and bulk actions.
 *
 * Extends the base Laravel controller so the package works in standalone apps.
 * Host apps attach auth middleware via config/routes.
 */
class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $readyNotifications = [];
        $adminId = $this->currentUserId();
        $notifications = $this->paginateForAdmin($request, $adminId);

        /** @var Notification $notification */
        foreach ($notifications->items() as $notification) {
            if (! is_string($notification->type) || ! class_exists($notification->type)) {
                continue;
            }

            try {
                /** @var object $messageType */
                $messageType = new $notification->type();
            } catch (\ArgumentCountError) {
                continue;
            } catch (\Exception) {
                continue;
            }

            if (method_exists($messageType, 'setNotification')) {
                $messageType->setNotification($notification);
            }

            if (! method_exists($messageType, 'message')) {
                continue;
            }

            $icon = '<i class="mdi mdi-email-check"></i>';
            if (method_exists($messageType, 'icon')) {
                $icon = (string) $messageType->icon();
            }

            $readyNotifications[] = [
                'id' => $notification->id,
                'read' => $notification->read_at !== null,
                'icon' => $icon,
                'message' => $messageType->message(),
            ];
        }

        return view('notification::notifications.index', [
            'is_quick' => 1,
            'type' => $request->get('type'),
            'notifications_model' => $notifications,
            'notifications' => $readyNotifications,
        ]);
    }

    public function read(Request $request): void
    {
        $idsPost = $request->post('ids');
        $adminId = $this->currentUserId();

        if (is_string($idsPost) && $idsPost === 'all') {
            Notification::query()
                ->where('notifiable_id', $adminId)
                ->update(['read_at' => Carbon::now()]);

            return;
        }

        $ids = is_string($idsPost) ? [$idsPost] : (array) $idsPost;

        foreach ($ids as $id) {
            $notify = Notification::query()
                ->where('notifiable_id', $adminId)
                ->where('id', $id)
                ->first();
            if ($notify) {
                $notify->read_at = Carbon::now();
                $notify->save();
            }
        }
    }

    public function reset(Request $request): void
    {
        $idsPost = $request->post('ids');
        $adminId = $this->currentUserId();

        if (is_string($idsPost) && $idsPost === 'all') {
            Notification::query()
                ->where('notifiable_id', $adminId)
                ->update(['read_at' => null]);

            return;
        }

        $ids = is_string($idsPost) ? [$idsPost] : (array) $idsPost;

        foreach ($ids as $id) {
            $notify = Notification::query()->where('id', $id)->first();
            if ($notify) {
                $notify->read_at = null;
                $notify->save();
            }
        }
    }

    public function delete(Request $request): void
    {
        $idsPost = $request->post('ids');
        $adminId = $this->currentUserId();

        if (is_string($idsPost) && $idsPost === 'all') {
            Notification::query()->where('notifiable_id', $adminId)->delete();

            return;
        }

        $ids = is_string($idsPost) ? [$idsPost] : (array) $idsPost;

        foreach ($ids as $id) {
            Notification::query()
                ->where('notifiable_id', $adminId)
                ->where('id', $id)
                ->delete();
        }
    }

    /**
     * @return array<string, mixed>|bool|string|null
     */
    public function testMail(Request $request): mixed
    {
        if (! class_exists(MailSenderService::class)) {
            return ['error' => 'Mail sender package is not available.'];
        }

        /** @var MailSenderService $send */
        $send = app(MailSenderService::class);

        return $send->test($request->all());
    }

    /**
     * @return LengthAwarePaginator<int, Notification>
     */
    protected function paginateForAdmin(Request $request, string $adminId): LengthAwarePaginator
    {
        $limit = max(1, $request->integer('limit', 30));

        if (trait_exists(\EloquentFilter\Filterable::class)) {
            /** @var LengthAwarePaginator<int, Notification> $paginator */
            $paginator = Notification::filter($request->all())
                ->where('notifiable_id', $adminId)
                ->orderBy('created_at', 'desc')
                ->paginate($limit)
                ->appends($request->except('page'));

            return $paginator;
        }

        /** @var LengthAwarePaginator<int, Notification> $paginator */
        $paginator = Notification::query()
            ->where('notifiable_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->appends($request->except('page'));

        return $paginator;
    }

    protected function currentUserId(): string
    {
        $admin = Auth::user();
        if ($admin === null) {
            return '';
        }

        $id = $admin->getAuthIdentifier();

        if (is_string($id) || is_int($id)) {
            return (string) $id;
        }

        return '';
    }
}
