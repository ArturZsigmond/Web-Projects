package com.example.puzzle;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;

@WebServlet("/puzzle")
public class PuzzleServlet extends HttpServlet {

    /**
     * returns true if idx1 and idx2 are adjacent (up/down/left/right)
     * on a 3×3 grid (indexes 0..8).
     */
    private boolean isAdjacent(int idx1, int idx2) {
        int r1 = idx1 / 3, c1 = idx1 % 3;
        int r2 = idx2 / 3, c2 = idx2 % 3;
        return (r1 == r2 && Math.abs(c1 - c2) == 1)
            || (c1 == c2 && Math.abs(r1 - r2) == 1);
    }

    /** returns true if the given array represents the “solved” state: 1,2,3,4,5,6,7,8,0 */
    private boolean isSolved(int[] arr) {
        for (int i = 0; i < 8; i++) {
            if (arr[i] != i + 1) {
                return false;
            }
        }
        return (arr[8] == 0);
    }

    /**
     * computes whether a 3×3 arrangement is solvable.
     * for odd‐width boards, the parity of the inversion count must be even.
     */
    private boolean isSolvable(int[] arr) {
        int inversions = 0;
        for (int i = 0; i < 9; i++) {
            if (arr[i] == 0) continue;
            for (int j = i + 1; j < 9; j++) {
                if (arr[j] == 0) continue;
                if (arr[i] > arr[j]) {
                    inversions++;
                }
            }
        }
    
        return (inversions % 2) == 0;
    }

    /**
     * generates a random, solvable 3×3 permutation of {0,1,…,8}.
     * re‐shuffles until the inversion parity is even.
     */
    private int[] generateSolvableShuffle() {
        List<Integer> list = new ArrayList<>();
        for (int i = 0; i < 9; i++) {
            list.add(i);
        }

        int[] arr = new int[9];
        do {
            Collections.shuffle(list);
            for (int i = 0; i < 9; i++) {
                arr[i] = list.get(i);
            }
        } while (!isSolvable(arr));

        return arr;
    }


    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        HttpSession session = request.getSession(false);
        if (session == null || session.getAttribute("userId") == null) {
            // not logged in → redirect to login page
            response.sendRedirect(request.getContextPath() + "/login.jsp");
            return;
        }

  
        int[] tiles = (int[]) session.getAttribute("tiles");
        Integer movesCount = (Integer) session.getAttribute("movesCount");
        Boolean solved = (Boolean) session.getAttribute("solved");

        if (tiles == null || movesCount == null || solved == null) {
            tiles = new int[]{1, 2, 3, 4, 5, 6, 7, 0, 8};
            movesCount = 0;
            solved = false;
            session.setAttribute("tiles", tiles);
            session.setAttribute("movesCount", movesCount);
            session.setAttribute("solved", solved);
        }

        String action = request.getParameter("action");

 
        if ("recordScore".equals(action) && Boolean.TRUE.equals(solved)) {
            int userId = (Integer) session.getAttribute("userId");
            try (Connection conn = DBUtil.getConnection()) {
                String sql = "INSERT INTO game_records (user_id, moves_count) VALUES (?, ?)";
                PreparedStatement stmt = conn.prepareStatement(sql);
                stmt.setInt(1, userId);
                stmt.setInt(2, movesCount);
                stmt.executeUpdate();
            } catch (SQLException e) {
                throw new ServletException(e);
            }
            session.setAttribute("scoreSaved", true);
            response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
            return;
        }

 
        if ("restart".equals(action)) {
            int[] newTiles = new int[]{1, 2, 3, 4, 5, 6, 7, 0, 8};
            session.setAttribute("tiles", newTiles);
            session.setAttribute("movesCount", 0);
            session.setAttribute("solved", false);
            response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
            return;
        }

  
        if ("shuffle".equals(action)) {
            int[] newTiles = generateSolvableShuffle();
            session.setAttribute("tiles", newTiles);
            session.setAttribute("movesCount", 0);
            session.setAttribute("solved", false);
            response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
            return;
        }


        if ("swap".equals(action) && !Boolean.TRUE.equals(solved)) {
            try {
                int fromIdx = Integer.parseInt(request.getParameter("fromIndex"));
                int toIdx = Integer.parseInt(request.getParameter("toIndex"));

                int temp = tiles[fromIdx];
                tiles[fromIdx] = tiles[toIdx];
                tiles[toIdx] = temp;

                movesCount++;
                session.setAttribute("movesCount", movesCount);

                if (isSolved(tiles)) {
                    session.setAttribute("solved", true);
                }
                session.setAttribute("tiles", tiles);

            } catch (NumberFormatException e) {
            }
            response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
            return;
        }

        String clickParam = request.getParameter("fromIndex");
        if ("clickMove".equals(action) && clickParam != null && !Boolean.TRUE.equals(solved)) {
            try {
                int fromIdx = Integer.parseInt(clickParam);
                int blankIdx = -1;
                for (int i = 0; i < 9; i++) {
                    if (tiles[i] == 0) {
                        blankIdx = i;
                        break;
                    }
                }
                if (blankIdx != -1 && isAdjacent(blankIdx, fromIdx)) {
                    tiles[blankIdx] = tiles[fromIdx];
                    tiles[fromIdx] = 0;

                    movesCount++;
                    session.setAttribute("movesCount", movesCount);

                    if (isSolved(tiles)) {
                        session.setAttribute("solved", true);
                    }
                    session.setAttribute("tiles", tiles);
                }
            } catch (NumberFormatException e) {
            }
            response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
            return;
        }

        response.sendRedirect(request.getContextPath() + "/puzzle.jsp");
    }
}
