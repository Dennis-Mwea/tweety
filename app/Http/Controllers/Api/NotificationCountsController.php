<?php

namespace App\Http\Controllers\Api;

class NotificationCountsController extends BaseApiController
{
    public function __invoke()
    {
        $this->sendResponse([
            'notification_counts' => current_user()->unreadNotifications->count()
        ]);
    }
}
