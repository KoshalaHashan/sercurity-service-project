<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["admin_id"])) {
    http_response_code(403);
    exit("unauthorized");
}

$table = $_POST["table"] ?? "";
$id    = (int)($_POST["id"] ?? 0);

$allowed = ["leads", "join_requests"];
if (!in_array($table, $allowed, true) || $id <= 0) {
    http_response_code(400);
    exit("invalid");
}

$sql = "UPDATE $table SET contacted = 1 WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $id]);

echo "ok";
