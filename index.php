<?php
session_start();
require 'db.php';

if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

$voter_id = $_SESSION['voter_id'];

// Check if the user has already voted
$stmt = $pdo->prepare("SELECT candidate_id FROM votes WHERE voter_id = ?");
$stmt->execute([$voter_id]);
$already_voted = $stmt->fetchColumn();

$candidates = $pdo->query("SELECT * FROM candidates")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_voted) {
    $candidate_id = $_POST['candidate_id'];

    // Record the vote in the votes table and increment candidate's votes
    $stmt = $pdo->prepare("INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)");
    $stmt->execute([$voter_id, $candidate_id]);

    $stmt = $pdo->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = ?");
    $stmt->execute([$candidate_id]);
    header("Location: results.php");

    // Refresh the page to prevent re-submission
    // header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Page</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Vote for Your Candidate</h2>
        <?php if ($already_voted): ?>
            <p class="message">You have already voted for this election.</p>
        <?php else: ?>
            <form method="POST" action="index.php">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="candidate">
                        <?php if ($candidate['photo']): ?>
                            <img src="<?php echo $candidate['photo']; ?>" alt="<?php echo htmlspecialchars($candidate['name']); ?>" width="100" height="100">
                        <?php endif; ?>
                        <strong><?php echo htmlspecialchars($candidate['name']); ?></strong> - <?php echo htmlspecialchars($candidate['position']); ?><br>
                        <button type="submit" name="candidate_id" value="<?php echo $candidate['id']; ?>">Vote</button>
                    </div>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
