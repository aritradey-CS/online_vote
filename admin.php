<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// Check if the logged-in user is admin
$user = $conn->query("SELECT is_admin FROM users WHERE id = $uid")->fetch_assoc();
if (!$user || $user['is_admin'] != 1) {
    // Not an admin - deny access
    die('<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Access Denied</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Access Denied</h2>
    <p>You do not have permission to access this page.</p>
    <a href="login.php" class="button">Back to Login</a>
  </div>
</body>
</html>');
}

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle form submission for adding candidate
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
        $name = $conn->real_escape_string(trim($_POST['name']));
        
        // Check if candidate name already exists (optional)
        $check = $conn->query("SELECT id FROM candidates WHERE name = '$name'");
        if ($check && $check->num_rows > 0) {
            $errorMessage = "Candidate '$name' already exists.";
        } else {
            if ($conn->query("INSERT INTO candidates (name) VALUES ('$name')")) {
                $successMessage = "Candidate '$name' added successfully.";
            } else {
                $errorMessage = "Database error: " . htmlspecialchars($conn->error);
            }
        }
        
    } else {
        $errorMessage = "Candidate name cannot be empty.";
    }
}

// Fetch all candidates for display
$candidates = $conn->query("SELECT * FROM candidates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Panel - Manage Candidates</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Candidate Management</h2>

    <?php if ($successMessage): ?>
      <div class="message success"><?= $successMessage ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
      <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <label for="name">Add New Candidate:</label>
      <input type="text" name="name" id="name" placeholder="Candidate name" required />
      <button type="submit">Add Candidate</button>
    </form>

    <h3>Existing Candidates:</h3>
    <?php if ($candidates->num_rows > 0): ?>
      <ul>
        <?php while ($row = $candidates->fetch_assoc()): ?>
          <li><?= htmlspecialchars($row['name']) ?></li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No candidates added yet.</p>
    <?php endif; ?>

    <br />
    <a href="dashboard.php" class="button">View Live Dashboard</a><br><br>
    <a href="logout.php" class="button">Logout</a>
  </div>
</body>
</html>
