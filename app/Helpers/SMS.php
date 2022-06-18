<?php

namespace App\Helpers;

class SMS
{

    public static function groupSmsSend($sender_id, $apiKey, $mobileNo, $message)
    {
        $url = "http://66.45.237.70/api.php";
        $data = array(
            'username' => $sender_id,
            'password' => $apiKey,
            'number' => $mobileNo,
            'message' => $message
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];

        return $smsresult;
    }

    public static function customSmsSend($sender_id, $apiKey, $mobileNo, $message)
    {
        $url = "http://66.45.237.70/api.php";
        $data = array(
            'username' => $sender_id,
            'password' => $apiKey,
            'number' => $mobileNo,
            'message' => $message
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];

        return $smsresult;
    }

    public static function smsCurrentBalance()
    {
        $url = "http://66.45.237.70/balancechk.php?username=alamin08&password=BRSFHE25&type=sms";

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];

        return $smsresult;
    }
}
