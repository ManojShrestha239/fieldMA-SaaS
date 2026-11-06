<?php

namespace App\Http\Controllers;

use App\Models\AlertReminder;

class AlertReminderController extends Controller
{
    public function index()
    {
        $alerts = AlertReminder::where(['date' => date('Y-m-d'), 'status' => 'Pending'])->groupBy('mobileuser_id')->get();

        foreach ($alerts as $alert) {
            if ($alert->mobileuser->fcm_id) {
                $title = "Today's Reminder";
                $flag = 'reminder';
                $fcmid = $alert->mobileuser->fcm_id;
                $content = [
                    'Title' => "Today's Reminder",
                    'Message' => 'Visit Reminder of The Day',
                ];
                $notification = new NotificationController;
                $notification->individualNotificationSend($fcmid, $title, $flag, $content);
            }

            return 'OK';
        }
    }
}
