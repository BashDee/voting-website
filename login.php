<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM voters WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['voter_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<form method="POST" action="login.php">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>

    <div id="extra">
        <a href="register.php">Register</a>
    </div>
</form>

