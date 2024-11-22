<?php
session_start();
require 'db.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Handle form submission to add or update a candidate
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_candidate'])) {
        $name = $_POST['name'];
        $position = $_POST['position'];

        // Handle image upload for new candidate
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo = 'uploads/' . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
        } else {
            $photo = null;
        }

        // Insert candidate data with photo path
        $stmt = $pdo->prepare("INSERT INTO candidates (name, position, photo) VALUES (?, ?, ?)");
        $stmt->execute([$name, $position, $photo]);
        echo "Candidate added!";
    } elseif (isset($_POST['edit_candidate'])) {
        // Handle edit action
        $id = $_POST['candidate_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];

        // Handle image upload for editing candidate
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo = 'uploads/' . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
            $stmt = $pdo->prepare("UPDATE candidates SET name = ?, position = ?, photo = ? WHERE id = ?");
            $stmt->execute([$name, $position, $photo, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE candidates SET name = ?, position = ? WHERE id = ?");
            $stmt->execute([$name, $position, $id]);
        }
        echo "Candidate updated!";
    }
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->execute([$id]);
    echo "Candidate deleted!";
}

// Fetch all candidates to display them
$candidates = $pdo->query("SELECT * FROM candidates")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <a href="logout.php">Logout</a>
        
        <h2>Add a New Candidate</h2>
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <input type="hidden" name="add_candidate" value="1">
            Candidate Name: <input type="text" name="name" required><br>
            Position: <input type="text" name="position" required><br>
            Photo: <input type="file" name="photo" accept="image/*"><br>
            <button type="submit">Add Candidate</button>
        </form>

        <h2>Current Candidates</h2>
        <table>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Position</th>
                <th>Total Votes</th>
                <th>Actions</th>
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
                    <td>
                        <form method="POST" action="admin.php" style="display:inline;" enctype="multipart/form-data">
                            <input type="hidden" name="edit_candidate" value="1">
                            <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">
                            Name: <input type="text" name="name" value="<?php echo htmlspecialchars($candidate['name']); ?>" required><br>
                            Position: <input type="text" name="position" value="<?php echo htmlspecialchars($candidate['position']); ?>" required><br>
                            Photo: <input type="file" name="photo" accept="image/*"><br>
                            <button type="submit">Update</button>
                        </form>
                        <a href="admin.php?delete_id=<?php echo $candidate['id']; ?>" onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
