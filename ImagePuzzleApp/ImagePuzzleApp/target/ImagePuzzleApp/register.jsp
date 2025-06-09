<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html>
<head>
  <title>Register – Image Puzzle</title>
  <style>
    form { max-width: 300px; margin: 50px auto; }
    label, input { display: block; width: 100%; margin-bottom: 8px; }
    .error { color: red; }
  </style>
</head>
<body>
  <h2 style="text-align: center;">Register</h2>

  <% if (request.getAttribute("error") != null) { %>
    <div class="error"><%= request.getAttribute("error") %></div>
  <% } %>

  <form action="<%= request.getContextPath() %>/register" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required maxlength="50" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required />

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" name="confirm_password" id="confirm_password" required />

    <button type="submit">Sign Up</button>
  </form>

  <p style="text-align: center;">
    Already have an account? <a href="<%= request.getContextPath() %>/login.jsp">Log in</a>
  </p>
</body>
</html>
