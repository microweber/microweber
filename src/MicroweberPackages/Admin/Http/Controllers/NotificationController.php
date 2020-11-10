<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:49 PM
 */

namespace MicroweberPackages\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Admin\Models\AdminUser;
use MicroweberPackages\App\Http\Controllers\AdminController;

class NotificationController extends AdminController {


    public function index(Request $request)
    {

        $readyNotifications = [];

        $admin = AdminUser::find(Auth::user()->id);

        foreach ($admin->unreadNotifications as $notification) {

            if (!class_exists($notification->type)) {
                continue;
            }

            $messageType = new $notification->type($notification->data);

            if (!method_exists($messageType, 'message')) {
                continue;
            }

            $readyNotifications[] = $messageType->message();
        }


        return $this->view('admin::notifications.index', [
            'is_quick'=>1,
            'notifications'=>$readyNotifications
        ]);
    }


}