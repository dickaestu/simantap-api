<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class Notification extends Model
{
    protected $fillable = ['user_id', 'read_at'];

    public function notifable(){
        return $this->morphTo();
    }

    public static function toSingleDevice($firebase, $icon, $click_action)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($firebase['title']);
        $notificationBuilder->setBody($firebase['body'])
            ->setSound('default')
            ->setBadge(1)
            ->setIcon($icon)
            ->setClickAction($click_action)
            ;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'type' => $firebase['data']['type'],
            'id' => $firebase['data']['id']
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($firebase['token'], $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
    }
}
