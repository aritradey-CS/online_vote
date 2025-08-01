<?php
// config.php
$conn = new mysqli("localhost", "root", "", "online_voting");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
