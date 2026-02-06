<?php
session_start();
require "../backend/db.php";
if (!isset($_SESSION["admin"])) header("Location: login.php");

$leadCount = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$joinCount = $pdo->query("SELECT COUNT(*) FROM joins")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body{font-family:Segoe UI;background:#f4f6fa;}
nav{background:#102E50;color:#fff;padding:15px;}
.container{padding:30px;}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;}
.card{background:#fff;padding:30px;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.1);}
a{color:#102E50;font-weight:600;text-decoration:none;}
</style>
</head>
<body>

<nav>
Admin Dashboard |
<a href="leads.php">Leads</a> |
<a href="joins.php">Join Requests</a> |
<a href="logout.php">Logout</a>
</nav>

<div class="container">
<div class="cards">
  <div class="card">
    <h2><?= $leadCount ?></h2>
    <p>Total Leads</p>
  </div>
  <div class="card">
    <h2><?= $joinCount ?></h2>
    <p>Join Requests</p>
  </div>
</div>
</div>
</body>
</html>
