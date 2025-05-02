<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ultimate Guestbook</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Fredoka One', cursive;
      background: linear-gradient(to right, #ffecd2, #fcb69f);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      text-align: center;
    }

    h1 {
      font-size: 4.5em;
      color: #ff4d4d;
      margin-bottom: 50px;
      text-shadow: 2px 2px 4px #fff;
    }

    .btn {
      display: block;
      padding: 18px 36px;
      margin: 15px;
      background: white;
      border: 3px solid #ff4d4d;
      border-radius: 20px;
      text-decoration: none;
      color: #ff4d4d;
      font-size: 1.6em;
      width: 250px;
      transition: 0.25s ease-in-out;
    }

    .btn:hover {
      background: #ff4d4d;
      color: white;
      transform: scale(1.05);
    }

    .buttons {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  </style>
</head>
<body>
  <h1>✨ Ultimate Guestbook ✨</h1>

  <div class="buttons">
    <a href="login.php" class="btn">Log In</a>
    <a href="register.php" class="btn">Sign Up</a>
    <a href="#" class="btn" onclick="adminAccess()">Admin Mode</a>
  </div>

  <script>
    function adminAccess() {
      const pass = prompt("Enter admin password:");
      if (pass === 'adminpass') {
        window.location.href = 'admin/admin_panel.php';
      } else {
        alert("Wrong password! 👮");
      }
    }
  </script>
</body>
</html>
