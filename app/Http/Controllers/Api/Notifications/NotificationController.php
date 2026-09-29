<?php

namespace App\Http\Controllers\Api\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\NotificationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user('web');
        //dd($user);

        return response()->json([
            'status' => true,
            'message' => 'Notifications fetched successfully',
            'data' => [
                'unread' => $user->unreadNotifications,
                'read' => $user->readNotifications,
            ]
        ]);
    }

    public function show($id)
    {
        $notification = auth()->user('web')->notifications()->findOrFail($id);
        
        return response()->json([
            'status' => true,
            'notification' => $notification
        ]);
    }

    public function viewNotification($id)
    {
        $user = auth()->user('web');

        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        // Mark as read if not already
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => $notification
        ]);
    }

}