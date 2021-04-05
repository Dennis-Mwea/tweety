<?php

namespace App\Http\Controllers\Api;

class NotificationsController extends BaseApiController
{
    public function index()
    {
        $unreadNotifications = current_user()->notifications;
        //return $readNotifications;
        auth()->user()->unreadNotifications->markAsRead();

        return $this->sendResponse($unreadNotifications);
    }
}
