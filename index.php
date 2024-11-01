<?php
session_start();
require 'db.php';

if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

$candidates = $pdo->query("SELECT * FROM candidates")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_id = $_POST['candidate_id'];
    $stmt = $pdo->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = ?");
    $stmt->execute([$candidate_id]);
    echo "Vote submitted!";
}
?>

<h2>Vote for Your Candidate</h2>
<form method="POST" action="index.php">
    <?php foreach ($candidates as $candidate): ?>
        <div>
            <strong><?php echo $candidate['name']; ?></strong> - <?php echo $candidate['position']; ?>
            <button type="submit" name="candidate_id" value="<?php echo $candidate['id']; ?>">Vote</button>
        </div>
    <?php endforeach; ?>
</form>
