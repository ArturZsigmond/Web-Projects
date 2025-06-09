<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html>
<head>
  <title>Login – Image Puzzle</title>
  <style>
    form { max-width: 300px; margin: 50px auto; }
    label, input { display: block; width: 100%; margin-bottom: 8px; }
    .error { color: red; }
    .info { color: green; }
  </style>
</head>
<body>
  <h2 style="text-align: center;">Login</h2>

  <% if ("true".equals(request.getParameter("registered"))) { %>
    <div class="info">Registration successful—please log in.</div>
  <% } %>

  <% if (request.getAttribute("error") != null) { %>
    <div class="error"><%= request.getAttribute("error") %></div>
  <% } %>

  <form action="<%= request.getContextPath() %>/login" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required />

    <button type="submit">Log In</button>
  </form>

  <p style="text-align: center;">
    Don’t have an account? <a href="<%= request.getContextPath() %>/register.jsp">Register</a>
  </p>
</body>
</html>
