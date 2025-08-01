<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user ID from session
$uid = $_SESSION['user_id'];

// Check if the user already voted
$user = $conn->query("SELECT voted FROM users WHERE id=$uid")->fetch_assoc();
if ($user['voted']) {
    // Instead of die(), better to show styled message, so we store it here and show in HTML below
    $alreadyVotedMessage = "You have already voted. <a href='results.php' class='button'>View Results</a>";
}

// Handle vote submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['candidate_id']) && !empty($_POST['candidate_id'])) {
        $cid = intval($_POST['candidate_id']);

        // Verify candidate exists
        $check = $conn->query("SELECT id FROM candidates WHERE id = $cid");
        if ($check && $check->num_rows === 1) {
            // Insert vote, with error handling
            if ($conn->query("INSERT INTO votes (user_id, candidate_id) VALUES ($uid, $cid)")) {
                // Mark user as voted
                $conn->query("UPDATE users SET voted=1 WHERE id=$uid");

                $successMessage = "Vote cast successfully! <a href='results.php' class='button'>View Results</a>";
            } else {
                $errorMessage = "Database error: " . htmlspecialchars($conn->error);
            }
        } else {
            $errorMessage = "Invalid candidate selected.";
        }
    } else {
        $errorMessage = "No candidate selected. Please choose a candidate.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vote - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
  <h2>Cast Your Vote</h2>

  <?php if (isset($alreadyVotedMessage)): ?>
    <div class="message info"><?= $alreadyVotedMessage ?></div>
  <?php elseif (isset($successMessage)): ?>
    <div class="message success"><?= $successMessage ?></div>
  <?php else: ?>
    <?php if (isset($errorMessage)): ?>
      <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <h3>Choose a candidate:</h3>
      <?php
        $candidates = $conn->query("SELECT * FROM candidates");
        if ($candidates->num_rows > 0):
          while($row = $candidates->fetch_assoc()):
      ?>
          <label>
            <input type="radio" name="candidate_id" value="<?= htmlspecialchars($row['id']) ?>" required>
            <?= htmlspecialchars($row['name']) ?>
          </label><br>
      <?php
          endwhile;
        else:
          echo "<p>No candidates available right now. Please check back later.</p>";
        endif;
      ?>
      <button type="submit">Vote</button>
    </form>
  <?php endif; ?>

  <br>
  <a href="logout.php" class="button">Logout</a>
</div>
</body>
</html>
