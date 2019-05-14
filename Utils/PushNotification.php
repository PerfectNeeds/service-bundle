<?php

namespace PNService\Utils;

use PN\Bundle\UserBundle\Entity\RefreshToken;

class PushNotification {

    private $appId = "AIzaSyAyPjKj6lEdMc-5z3-s5uWBJ9M65zZvBT8";
    private $url = "https://fcm.googleapis.com/fcm/send";

    public function __construct($appId) {
        $this->appId = $appId;
    }

    private function pushNotification($fields) {
        $json_message = json_encode($fields);
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_message),
            "Authorization:key=" . $this->appId,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_message);

        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function pushNotificationMultiDevice($user, $title, $message, $body, $timeToLive = NULL) {
        $iosTokens = $user->getRefreshTokensByDevice(RefreshToken::DEVICE_IOS);
        $androidTokens = $user->getRefreshTokensByDevice(RefreshToken::DEVICE_ANDROID);

        $this->pushNotificationDataIos($iosTokens, $title, $message, $body, $timeToLive);
        $this->pushNotificationDataAndroid($androidTokens, $title, $message, $body);
        return true;
    }

    public function pushNotificationDataIos($tokens, $title, $message, $body, $timeToLive = NULL, $badge = 1) {
        if (count($tokens) == 0) {
            return TRUE;
        }
        $fields = [
            'registration_ids' => (array) $tokens,
            'notification' => [
                "body" => $message,
                "title" => $title,
                "sound" => "default",
                "badge" => $badge,
                "priority" => "high",
                "icon" => "notification",
                "collapse_key" => "Available",
            ]
        ];
        if ($timeToLive > 0) {
            $fields['notification']['time_to_live'] = $timeToLive;
        }
        if (count($body) > 0) {
            foreach ($body as $key => $value) {
                $fields['data'][$key] = $value;
            }
        }
        return $this->pushNotification($fields);
    }

    public static function pushNotificationDataAndroid($tokens, $title, $message, $body) {
        if (count($tokens) == 0) {
            return TRUE;
        }
        $fields = [
            'registration_ids' => (array) $tokens,
            'data' => [
                'title' => $title,
                'message' => $message,
            ]
        ];
        if (count($body) > 0) {
            foreach ($body as $key => $value) {
                $fields['data'][$key] = $value;
            }
        }
        return $this->pushNotification($fields);
    }

    public function checkExpiredRefreshToken($token) {
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . 0,
            "Authorization:key=" . $this->appId,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iid.googleapis.com/iid/info/' . $token);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $json = json_decode($result);
        if (isset($json->error)) {
            return TRUE;
        } else {
            return FALSE;
        }
        return $result;
    }

}

?>