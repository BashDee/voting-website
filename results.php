<?php
session_start();
require 'db.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total votes for each candidate
$candidates = $pdo->query("SELECT name, position, photo, votes FROM candidates")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Election Results</h2>
        <table>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Position</th>
                <th>Total Votes</th>
            </tr>
            <?php foreach ($candidates as $candidate): ?>
                <tr>
                    <td>
                        <?php if ($candidate['photo']): ?>
                            <img src="<?php echo htmlspecialchars($candidate['photo']); ?>" alt="Photo" width="50" height="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($candidate['name']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['position']); ?></td>
                    <td><?php echo $candidate['votes']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.php">Go Back</a>
    </div>
</body>
</html>
