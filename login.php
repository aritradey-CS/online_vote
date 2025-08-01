<?php
session_start();
include 'config.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: vote.php");
            exit;
        } else {
            $errorMessage = "Incorrect password.";
        }
    } else {
        $errorMessage = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
  <h2>User Login</h2>

  <?php if ($errorMessage): ?>
    <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <label for="username">Username:</label>
    <input id="username" name="username" type="text" required />

    <label for="password">Password:</label>
    <input id="password" name="password" type="password" required />

    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</div>
</body>
</html>
