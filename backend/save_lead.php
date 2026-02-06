<?php
require_once "db.php";
require_once "send_sms.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("method_not_allowed");
}

$name     = trim($_POST["name"] ?? "");
$mobile   = trim($_POST["mobile"] ?? "");
$interest = trim($_POST["interest"] ?? "");

if ($name === "" || $mobile === "" || $interest === "") {
    http_response_code(400);
    exit("invalid");
}

$stmt = $pdo->prepare("INSERT INTO leads (name, mobile, interest) VALUES (:n,:m,:i)");
$stmt->execute([
    ":n" => $name,
    ":m" => $mobile,
    ":i" => $interest
]);

// Send SMS to visitor
$to = normalizeLKNumber($mobile);

$interestText = ($interest === "service")
    ? "Security Service"
    : (($interest === "join") ? "Joining as a Security Officer" : $interest);

$msg = "Hi $name, WEALTH LANKA SECURITY received your request about: $interestText. We will contact you shortly.";

try {
    sendSMS($to, $msg);
} catch (Exception $e) {
    // keep submission successful even if SMS fails
}

echo "success";
