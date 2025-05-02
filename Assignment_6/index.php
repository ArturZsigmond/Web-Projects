<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Guestbook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Guestbook</h1>

    <p>Welcome, <strong><?= htmlspecialchars($_SESSION['email']) ?></strong> | <a href="logout.php">Logout</a></p>

    <form id="guestbook-form">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="comment" placeholder="Comment" required></textarea>
        <label>
            <select name="rating">
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3" selected>3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
        </label>
        <button type="submit">Add Entry</button>
    </form>

    <div id="entries-container"></div>
    <button id="prev-page">Previous</button>
    <button id="next-page">Next</button>

    <script>
        const userId = <?= json_encode($_SESSION['user_id']) ?>;
    </script>
    <script src="js/main.js"></script>
</body>
</html>
