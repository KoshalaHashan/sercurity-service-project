<?php
session_start();
require "../backend/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username=?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin"] = $admin["id"];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Invalid login";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{font-family:Segoe UI;background:#102E50;color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
form{background:#fff;color:#102E50;padding:30px;border-radius:12px;width:300px;}
input,button{width:100%;padding:10px;margin:10px 0;}
button{background:#F5C45E;border:none;font-weight:600;}
</style>
</head>
<body>

<form method="post">
<h3>Admin Login</h3>
<?php if(!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
<input name="username" placeholder="Username" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
</form>

</body>
</html>
