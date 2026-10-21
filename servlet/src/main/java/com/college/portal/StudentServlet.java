package com.college.portal;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.*;
import java.util.*;

@WebServlet("/students")
public class StudentServlet extends HttpServlet {
    @Override protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        // Protect the portal: only a session created by successful login can view profiles.
        if (!Boolean.TRUE.equals(req.getSession(false)==null?null:req.getSession(false).getAttribute("loggedIn"))) { resp.sendRedirect(req.getContextPath()+"/login"); return; }
        List<Map<String,Object>> students=new ArrayList<>();
        String sql="SELECT s.id,s.name,s.roll_number,s.email,s.course,f.id AS file_id,f.original_name,f.file_type FROM students s LEFT JOIN student_files f ON s.id=f.student_id ORDER BY s.id DESC";
        try (Connection con=Db.getConnection(); PreparedStatement ps=con.prepareStatement(sql); ResultSet rs=ps.executeQuery()) {
            while(rs.next()) {
                Map<String,Object> row=new HashMap<>(); row.put("id",rs.getInt("id")); row.put("name",rs.getString("name")); row.put("roll",rs.getString("roll_number")); row.put("email",rs.getString("email")); row.put("course",rs.getString("course")); row.put("fileId",(Integer)(rs.getObject("file_id")==null?null:rs.getInt("file_id"))); row.put("fileName",rs.getString("original_name")); row.put("fileType",rs.getString("file_type")); students.add(row);
            }
        } catch(SQLException ex) { throw new ServletException("Unable to retrieve student profiles. Check MySQL configuration.",ex); }
        req.setAttribute("students",students); req.getRequestDispatcher("/WEB-INF/students.jsp").forward(req,resp);
    }
}
