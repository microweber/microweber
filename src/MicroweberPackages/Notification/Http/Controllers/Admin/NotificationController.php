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

use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\User\Models\User;

class NotificationController extends AdminController
{


    public function index(Request $request)
    {

        $readyNotifications = [];

        $admin = Auth::user();

        foreach ($admin->unreadNotifications as $notification) {

            if (!class_exists($notification->type)) {
                continue;
            }

            $messageType = new $notification->type();

            if (method_exists($messageType, 'setNotification')) {
                $messageType->setNotification($notification);
            }

            if (!method_exists($messageType, 'message')) {
                continue;
            }

            $readyNotifications[] = [
                'id' => $notification->id,
                'message' => $messageType->message()
            ];
        }


        return $this->view('notification::notifications.index', [
            'is_quick' => 1,
            'filter_by' => 1,
            'notifications' => $readyNotifications
        ]);
    }


}