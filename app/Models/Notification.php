<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'read_at'];

    public function notifable()
    {
        return $this->morphTo();
    }

    public static function toSingleDevice($firebase)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $key_authorization = config('fcm.fcm_server_key');
        $project_id = config('fcm.fcm_sender_id');
        $fields = [
            'to' => $firebase['token'],
            'notification' => [
                'body' => $firebase['body'],
                'title' => $firebase['title']
            ],
            'data' => [
                'body' => $firebase['body'],
                'title' => $firebase['title'],
                'id' => $firebase['data']['id'],
                'type' => $firebase['data']['type']
            ],
        ];

        $fields_encode = json_encode($fields);
        $headers = [
            'Authorization:key=' . $key_authorization,
            'Content-Type:application/json',
            'project_id' => $project_id
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_encode);

        $out = curl_exec($ch);
        curl_close($ch);

        return $out;
    }
}
