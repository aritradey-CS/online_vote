<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="refresh" content="3;url=login.php" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Logged Out - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
  <h2>Logged Out</h2>
  <p>You have been successfully logged out.</p>
  <p>Redirecting to <a href="login.php" class="button">Login Page</a>...</p>
</div>
</body>
</html>
