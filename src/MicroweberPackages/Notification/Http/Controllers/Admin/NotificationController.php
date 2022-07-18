<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:49 PM
 */

namespace MicroweberPackages\Notification\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;

class NotificationController extends AdminController
{
    public function index(Request $request)
    {

        $readyNotifications = [];

        $admin = Auth::user();

        $notifications = Notification::filter($request->all())
            ->where('notifiable_id', $admin->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('limit', 30))
            ->appends($request->except('page'));

        foreach ($notifications as $notification) {

            if (!class_exists($notification->type)) {
                continue;
            }

            try {
                $messageType = new $notification->type();
            } catch (\ArgumentCountError $e) {
                continue;
            } catch (\Exception $e) {
                continue;
            }

            if (method_exists($messageType, 'setNotification')) {
                $messageType->setNotification($notification);
            }

            if (!method_exists($messageType, 'message')) {
                continue;
            }

            $icon = '<i class="mdi mdi-email-check"></i>';
            if (method_exists($messageType, 'icon')) {
                $icon = $messageType->icon();
            }

            $read = false;
            if ($notification->read_at > 0) {
                $read = true;
            }

            $readyNotifications[] = [
                'id' => $notification->id,
                'read' => $read,
                'icon' => $icon,
                'message' => $messageType->message()
            ];
        }

        return $this->view('notification::notifications.index', [
            'is_quick' => 1,
            'type' => $request->get('type'),
            'notifications_model' => $notifications,
            'notifications' => $readyNotifications
        ]);
    }

    public function read(Request $request)
    {
        $idsPost = $request->post('ids');
        $admin = Auth::user();

        if (empty($idsPost)) {
            Notification::where('notifiable_id', $admin->id)->update(['read_at' => date('Y-m-d H:i:s')]);
        } else {

            if (is_string($idsPost)) {
                $ids = array();
                $ids[] = $idsPost;
            } else {
                $ids = $idsPost;
            }

            foreach ($ids as $id) {
                $notify = Notification::where('notifiable_id', $admin->id)->where('id', $id)->first();
                if ($notify) {
                    $notify->read_at = date('Y-m-d H:i:s');
                    $notify->save();
                }
            }
        }

    }

    public function reset(Request $request)
    {
        $idsPost = $request->post('ids');

        $admin = Auth::user();

        if (empty($idsPost)) {
            Notification::where('notifiable_id', $admin->id)->update(['read_at' => null]);
        } else {

            if (is_string($idsPost)) {
                $ids = array();
                $ids[] = $idsPost;
            } else {
                $ids = $idsPost;
            }

            foreach ($ids as $id) {
                $notify = Notification::where('id', $id)->first();
                if ($notify) {
                    $notify->read_at = null;
                    $notify->save();
                }
            }
        }
    }

    public function delete(Request $request)
    {
        $idsPost = $request->post('ids');

        $admin = Auth::user();

        if (empty($idsPost)) {
            Notification::where('notifiable_id', $admin->id)->delete();
        } else {

            if (is_string($idsPost)) {
                $ids = array();
                $ids[] = $idsPost;
            } else {
                $ids = $idsPost;
            }

            foreach ($ids as $id) {
                Notification::where('notifiable_id', $admin->id)->where('id', $id)->delete();
            }
        }
    }

    public function testMail(Request $request)
    {
        $send = new MailSender();
        return $send->test($request->all());
    }

}
