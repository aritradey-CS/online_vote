<?php
include 'config.php';

$results = $conn->query("
  SELECT c.name, COUNT(v.candidate_id) AS votes_count
  FROM candidates c
  LEFT JOIN votes v ON c.id = v.candidate_id
  GROUP BY c.id
  ORDER BY votes_count DESC, c.name ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Live Leaderboard - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
  <!-- Refresh page every 10 seconds -->
  <meta http-equiv="refresh" content="10" />
</head>
<body>
  <div class="container">
    <h2>Live Leaderboard</h2>
    <?php if ($results->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Votes</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $results->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['votes_count']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No voting data available yet.</p>
    <?php endif; ?>

    <br />
    <a href="login.php" class="button">Back to Login</a>
  </div>
</body>
</html>
