<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $onlyUnread = $request->boolean('unread');
        $query = $onlyUnread ? $user->unreadNotifications() : $user->notifications();

        $notifications = $query
            ->latest()
            ->paginate(15);

        return view('pages.notifications.index', compact('notifications', 'onlyUnread'));
    }

    public function markAsRead(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var DatabaseNotification|null $notif */
        $notif = $user->notifications()->where('id', $id)->first();
        if ($notif) {
            $notif->markAsRead();
        }

        return back();
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back();
    }
}
