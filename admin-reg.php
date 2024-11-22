<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the admin details into the database
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $hashed_password]);

        echo "<p>Registration successful! <a href='admin-login.php'>Login here</a></p>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Unique constraint violation
            echo "<p>Username already exists. Try another one.</p>";
        } else {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
</head>
<body>
    <h2>Admin Registration</h2>
    <form method="POST" action="admin-reg.php">
        Username: <input type="text" name="username" required><br>
        
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
