<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nin = $_POST['nin'];

    $stmt = $pdo->prepare("INSERT INTO voters (name, email, password, nin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $nin]);
    echo "Registration successful!";
}
?>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<form method="POST" action="register.php">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    NIN: <input type="text" name="nin" required><br>
    <button type="submit">Register</button>
</form>
