<?php
include 'config.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password_plain = $_POST['password'];

    // Basic validations
    if (strlen($username) < 3) {
        $errorMessage = "Username must be at least 3 characters long.";
    } elseif (strlen($password_plain) < 6) {
        $errorMessage = "Password must be at least 6 characters long.";
    } else {
        // Hash the password securely
        $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password_hashed')";
        if ($conn->query($sql) === TRUE) {
            $successMessage = "Registered successfully. <a href='login.php' class='button'>Login</a>";
        } else {
            // Handle duplicate username error gracefully
            if (strpos($conn->error, 'Duplicate entry') !== false) {
                $errorMessage = "Username already exists. Please choose another.";
            } else {
                $errorMessage = "Error: " . htmlspecialchars($conn->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
  <h2>Create an Account</h2>

  <?php if ($successMessage): ?>
    <div class="message success"><?= $successMessage ?></div>
  <?php endif; ?>

  <?php if ($errorMessage): ?>
    <div class="message error"><?= $errorMessage ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <label for="username">Username:</label>
    <input id="username" name="username" type="text" required minlength="3" />

    <label for="password">Password:</label>
    <input id="password" name="password" type="password" required minlength="6" />

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a>.</p>
</div>
</body>
</html>
