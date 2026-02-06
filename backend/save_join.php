<?php
require_once "db.php";
require_once "send_sms.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("method_not_allowed");
}

$name   = trim($_POST["name"] ?? "");
$mobile = trim($_POST["mobile"] ?? "");
$email  = trim($_POST["email"] ?? "");
$reason = trim($_POST["reason"] ?? "");

if ($name === "" || $mobile === "") {
    http_response_code(400);
    exit("invalid");
}

$stmt = $pdo->prepare("
    INSERT INTO join_requests (name, mobile, email, reason)
    VALUES (:n,:m,:e,:r)
");
$stmt->execute([
    ":n" => $name,
    ":m" => $mobile,
    ":e" => $email,
    ":r" => $reason
]);

// SMS to applicant
$to = normalizeLKNumber($mobile);
$msg = "Hi $name, WEALTH LANKA SECURITY received your application. We will contact you shortly.";

try { sendSMS($to, $msg); } catch (Exception $e) {}

echo "success";
