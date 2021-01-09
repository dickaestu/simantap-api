<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(){
        $user = JWTAuth::user();
        $notifications = Notification::where('user_id', $user->id)->where('read_at', null)->orderByDesc('created_at')->get();
        $count = $notifications->count();
        return response()->json([
            'message' => 'fetched all success.',
            'total' => $count,
            'data' => $notifications,
        ], 200);
    }

    //Method after send notification to firebase store notification here.
    public static function store($model, $userId){
        $model->notifications()->create([
            'user_id' => $userId,
        ]);

    }

    //Update Read At
    public function update($id){
        $user = JWTAuth::user();

        $notification = Notification::FindOrFail($id);
        if($user->id != $notification->user_id){
            return response()->json([
                'message' => 'only same user can update the notification.'
            ], 401);
        }
        
        $notification->update([
            'read_at' => now()
        ]);

        return response()->json([
            'message' => 'notification updated successfully'
        ], 200);
    }
}
