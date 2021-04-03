<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    public function index()
    {
        current_user()->unreadNotifications->markAsRead();
        return view('notifications.index', [
            'readNotifications' => current_user()->readNotifications
                ->groupBy(function ($notification) {
                    return $notification->created_at->format('d M y');
                }),
            'unreadNotifications' => current_user()->unReadNotifications
                ->groupBy(function ($notification) {
                    return $notification->created_at->format('d M y');
                }),
        ]);
    }
}
