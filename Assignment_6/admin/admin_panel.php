<?php
require '../includes/db.php';
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

  <label for="group-select">Group entries by:</label>
  <select id="group-select">
    <option value="none">None</option>
    <option value="author">Author</option>
    <option value="title">Title</option>
  </select>

  <div id="admin-entries-container"></div>
  <button id="admin-prev">Previous</button>
  <button id="admin-next">Next</button>

  <script>
    let adminPage = 1;

    function loadAdminEntries() {
      const groupBy = document.getElementById('group-select').value;
      fetch(`../fetch_entries.php?page=${adminPage}&groupBy=${groupBy}`)
        .then(res => res.json())
        .then(entries => {
          const container = document.getElementById('admin-entries-container');
          container.innerHTML = '';
          for (const entry of entries) {
            const div = document.createElement('div');
            div.className = 'entry';
            div.innerHTML = `
              <form method="post" action="../update_entry.php">
                <input type="hidden" name="id" value="${entry.id}">
                <p><strong>${entry.author_email}</strong></p>
                <input name="title" value="${entry.title}"><br>
                <textarea name="comment">${entry.comment}</textarea><br>
                <small>${entry.date_created}</small><br>
                <button type="submit">Update</button>
              </form>
              <form method="post" action="../delete_entry.php" onsubmit="return confirm('Delete this entry?');">
                <input type="hidden" name="id" value="${entry.id}">
                <button type="submit">Delete</button>
              </form>
              <hr>`;
            container.appendChild(div);
          }
        });
    }

    document.getElementById('group-select').onchange = () => {
      adminPage = 1;
      loadAdminEntries();
    };

    document.getElementById('admin-prev').onclick = () => {
      if (adminPage > 1) adminPage--;
      loadAdminEntries();
    };

    document.getElementById('admin-next').onclick = () => {
      adminPage++;
      loadAdminEntries();
    };

    window.onload = loadAdminEntries;
  </script>
</body>
</html>
