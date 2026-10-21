package com.college.portal;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebServlet("/login")
public class LoginServlet extends HttpServlet {
    @Override protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        req.getRequestDispatcher("/WEB-INF/login.jsp").forward(req, resp);
    }
    @Override protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException, ServletException {
        // Demonstration only: replace with database-backed password_hash verification for production.
        String username=req.getParameter("username"), password=req.getParameter("password");
        if ("student".equals(username) && "Student@123".equals(password)) {
            req.getSession(true).setAttribute("loggedIn", Boolean.TRUE);
            resp.sendRedirect(req.getContextPath()+"/students");
        } else { req.setAttribute("error", "Invalid username or password."); req.getRequestDispatcher("/WEB-INF/login.jsp").forward(req,resp); }
    }
}
