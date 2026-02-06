<?php
require_once "send_sms.php";

$to = normalizeLKNumber("0770601183"); // put your verified number here
sendSMS($to, "✅ Test SMS from Wealth Lanka Security (XAMPP).");
echo "sent";
?>