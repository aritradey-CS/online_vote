<?php
include 'config.php';

$results = $conn->query("
  SELECT c.name, COUNT(v.id) AS votes_count
  FROM candidates c
  LEFT JOIN votes v ON c.id = v.candidate_id
  GROUP BY c.id
  ORDER BY votes_count DESC, c.name
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Voting Results - Online Voting System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Voting Results</h2>

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
      <p>No voting results available yet.</p>
    <?php endif; ?>

    <br />
    <a href="login.php" class="button">Back to Login</a>
  </div>
</body>
</html>
