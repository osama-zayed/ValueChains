<?php
namespace App\Services;

use App\Models\User;
use App\Notifications\Notifications;

class UserService
{
    static function NotificationsAdmin($masseg)
    {
        $loggedInUserId = auth()->user()->id;
        $users = User::where('user_type', 'admin')
            ->where('id', '!=', $loggedInUserId)
            ->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $user->notify(new Notifications([
                    "body" => $masseg
                ]));
            }
        }
    }
    public static function userActivity($message,  $userType = 'المستخدم')
    {
        $user = auth('sanctum')->user();

        activity()->causedBy($user)
            ->log(
                'لقد قام' . $userType .' '. $user->name .  $message . " الوقت والتاريخ " . now()
            );
    }
}
