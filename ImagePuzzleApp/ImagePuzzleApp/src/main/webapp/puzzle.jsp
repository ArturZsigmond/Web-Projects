<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ page import="javax.servlet.http.*, java.util.*" %>
<%
  // 1. Ensure user is logged in
  HttpSession userSession = request.getSession(false);
  if (userSession == null || userSession.getAttribute("userId") == null) {
    response.sendRedirect(request.getContextPath() + "/login.jsp");
    return;
  }

  // 2. Load or initialize puzzle state
  int[] tiles;
  Integer movesCount;
  Boolean solved;

  if (userSession.getAttribute("tiles") == null) {
    // Initial fixed scramble (you can randomize later)
    tiles = new int[]{1,2,3,4,5,6,7,0,8};
    movesCount = 0;
    solved = false;
    userSession.setAttribute("tiles", tiles);
    userSession.setAttribute("movesCount", movesCount);
    userSession.setAttribute("solved", solved);
  } else {
    tiles = (int[]) userSession.getAttribute("tiles");
    movesCount = (Integer) userSession.getAttribute("movesCount");
    solved = (Boolean) userSession.getAttribute("solved");
  }
%>
<!DOCTYPE html>
<html>
<head>
  <title>Play Puzzle – Image Puzzle</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .top-bar {
      text-align: center;
      margin-top: 20px;
    }
    .top-bar a, .top-bar form {
      margin: 0 10px;
      display: inline-block;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(3, 100px);
      grid-template-rows: repeat(3, 100px);
      gap: 2px;
      margin: 20px auto;
      width: max-content;
    }
    .grid-item {
      position: relative;
      width: 100px;
      height: 100px;
    }
    .grid-item img {
      width: 100%;
      height: 100%;
      border: 1px solid #ccc;
      cursor: grab;
    }
    .grid-item img.dragging {
      opacity: 0.5;
      cursor: grabbing;
    }
    .info {
      text-align: center;
      margin-top: 10px;
    }
    .btn {
      display: inline-block;
      padding: 6px 12px;
      background: #28a745;
      color: #fff;
      border: none;
      cursor: pointer;
      text-decoration: none;
      font-size: 14px;
      border-radius: 4px;
    }
    .btn:disabled {
      background: #999;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <div class="top-bar">
    <h2 style="display: inline;">Welcome, <%= userSession.getAttribute("username") %>!</h2>
    &nbsp;&nbsp;
    <a href="<%= request.getContextPath() %>/logout">Logout</a>
    &nbsp;&nbsp;
    <form action="<%= request.getContextPath() %>/puzzle" method="post" style="display:inline;">
      <input type="hidden" name="action" value="shuffle" />
      <button
        type="submit"
        class="btn"
        <%= (Boolean.TRUE.equals(solved) ? "disabled" : "") %>
      >
        Shuffle
      </button>
    </form>
  </div>

  <div class="info">
    Moves: <span id="moveCount"><%= movesCount %></span>
  </div>

  <div class="grid" id="puzzleGrid">
    <%
      // Output nine “grid-item” DIVs (each with data-index)
      for (int i = 0; i < 9; i++) {
        int tileValue = tiles[i];
    %>
      <div class="grid-item" data-index="<%= i %>">
        <% if (tileValue == 0) { %>
          <img
            src="<%= request.getContextPath() %>/puzzle-images/blank.png"
            alt="blank"
            draggable="<%= (Boolean.TRUE.equals(solved) ? "false" : "true") %>"
          />
        <% } else { %>
          <img
            src="<%= request.getContextPath() %>/puzzle-images/tile<%= tileValue %>.jpg"
            alt="Tile <%= tileValue %>"
            draggable="<%= (Boolean.TRUE.equals(solved) ? "false" : "true") %>"
          />
        <% } %>
      </div>
    <%
      }
    %>
  </div>

  <%
    // If solved, show the “recordScore” and “playAgain” buttons:
    if (Boolean.TRUE.equals(solved)) {
  %>
    <div class="info">
      <strong>Congratulations! You solved the puzzle in <%= movesCount %> moves.</strong><br/><br/>
      <form action="<%= request.getContextPath() %>/puzzle" method="post" style="display:inline;">
        <input type="hidden" name="action" value="recordScore" />
        <button type="submit" class="btn">Save Your Score</button>
      </form>
      &nbsp;&nbsp;
      <form action="<%= request.getContextPath() %>/puzzle" method="post" style="display:inline;">
        <input type="hidden" name="action" value="restart" />
        <button type="submit" class="btn">Play Again</button>
      </form>
    </div>
  <%
    }
  %>

  <form id="swapForm" action="<%= request.getContextPath() %>/puzzle" method="post" style="display:none;">
    <input type="hidden" name="fromIndex" id="fromIndex" value="" />
    <input type="hidden" name="toIndex"   id="toIndex"   value="" />
    <input type="hidden" name="action"    id="actionField" value="swap" />
  </form>

  <script>
    <% if (Boolean.TRUE.equals(solved)) { %>
      const isSolved = true;
    <% } else { %>
      const isSolved = false;
    <% } %>

    let draggedIndex = null;

    document.querySelectorAll(".grid-item img").forEach(img => {
      img.addEventListener("dragstart", function (evt) {
        if (isSolved) {
          evt.preventDefault();
          return;
        }
        draggedIndex = this.closest(".grid-item").getAttribute("data-index");
        this.classList.add("dragging");
      });
      img.addEventListener("dragend", function (evt) {
        this.classList.remove("dragging");
      });
    });

    document.querySelectorAll(".grid-item").forEach(div => {
      div.addEventListener("dragover", function (evt) {
        evt.preventDefault();
      });
      div.addEventListener("drop", function (evt) {
        evt.preventDefault();
        if (isSolved) return;

        const targetIndex = this.getAttribute("data-index");
        if (draggedIndex === null || targetIndex === null) return;
        if (draggedIndex === targetIndex) return;

        document.getElementById("fromIndex").value = draggedIndex;
        document.getElementById("toIndex").value   = targetIndex;
        document.getElementById("actionField").value = "swap";
        document.getElementById("swapForm").submit();
      });
    });

    document.querySelectorAll(".grid-item img").forEach(img => {
      img.addEventListener("click", function () {
        if (isSolved) return;

        const clickedIndex = this.closest(".grid-item").getAttribute("data-index");

        document.getElementById("fromIndex").value   = clickedIndex;
        document.getElementById("toIndex").value     = "";
        document.getElementById("actionField").value = "clickMove";
        document.getElementById("swapForm").submit();
      });
    });
  </script>
</body>
</html>
