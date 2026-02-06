<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Twilio\Rest\Client;

function normalizeLKNumber(string $mobile): string {
    $m = preg_replace('/\s+/', '', trim($mobile));

    if (preg_match('/^\+94\d{9}$/', $m)) return $m;             // +9477...
    if (preg_match('/^0\d{9}$/', $m))   return "+94" . substr($m, 1); // 077.. -> +9477..
    if (preg_match('/^\d{9}$/', $m))    return "+94" . $m;      // 77.. -> +9477..

    if ($m !== "" && $m[0] !== '+') $m = "+94" . ltrim($m, "0");
    return $m;
}

function sendSMS(string $to, string $message): void {
    $client = new Client(TWILIO_SID, TWILIO_TOKEN);
    $client->messages->create($to, [
        "from" => TWILIO_FROM,
        "body" => $message
    ]);
}
