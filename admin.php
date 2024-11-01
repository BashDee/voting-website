<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];

    $stmt = $pdo->prepare("INSERT INTO candidates (name, position) VALUES (?, ?)");
    $stmt->execute([$name, $position]);
    echo "Candidate added!";
}
?>

<h2>Add a New Candidate</h2>
<form method="POST" action="admin.php">
    Candidate Name: <input type="text" name="name" required><br>
    Position: <input type="text" name="position" required><br>
    <button type="submit">Add Candidate</button>
</form>
