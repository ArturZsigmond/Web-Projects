<?php
require '../includes/db.php';
$entries = $pdo->query("SELECT * FROM entries ORDER BY date_created DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h1>Admin Panel</h1>
  <table>
    <tr><th>Email</th><th>Title</th><th>Comment</th><th>Date</th><th>Actions</th></tr>
    <?php foreach ($entries as $entry): ?>
      <tr>
        <form method="post" action="../update_entry.php">
          <input type="hidden" name="id" value="<?= $entry['id'] ?>">
          <td><?= htmlspecialchars($entry['author_email']) ?></td>
          <td><input name="title" value="<?= htmlspecialchars($entry['title']) ?>"></td>
          <td><textarea name="comment"><?= htmlspecialchars($entry['comment']) ?></textarea></td>
          <td><?= $entry['date_created'] ?></td>
          <td>
            <button type="submit">Update</button>
        </form>
        <form method="post" action="../delete_entry.php" onsubmit="return confirm('Delete this entry?');">
          <input type="hidden" name="id" value="<?= $entry['id'] ?>">
          <button type="submit">Delete</button>
        </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
