<?php

namespace Tasawk\Api\V1\Shared;

use Notification;
use Tasawk\Api\Facade\Api;
use Tasawk\Http\Resources\Api\Customer\NotificationResource;
use Tasawk\Notifications\SendAdminMessagesNotification;


class NotificationServices {
    public function all() {
        $notifications = NotificationResource::collection(auth()->user()->notifications()->latest()->paginate());
        return Api::isOk(__("Notification list"))->setData($notifications);
    }

    public function destroy($notification = null) {
        !$notification
            ? auth()->user()->notifications()->delete()
            : auth()->user()->notifications()->where("id", $notification)->delete();
        return Api::isOk(__("Notification has been deleted"));
    }

    public function fcm() {
        return Notification::send(auth()->user(), new SendAdminMessagesNotification(...request()->only('title', 'body')));
    }

}
