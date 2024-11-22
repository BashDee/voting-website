<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin = $_POST['admin'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE admin = ?");
    $stmt->execute([$admin]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin'] = $admin;

        header("Location: admin.php");
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<h1>ADMIN LOGIN</h1>

<form method="POST" action="admin-login.php">
    Username: <input type="text" name="admin" required><br>

    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>

</form>

