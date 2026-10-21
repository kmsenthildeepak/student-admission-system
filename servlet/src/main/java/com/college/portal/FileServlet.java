package com.college.portal;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.*;
import java.nio.file.*;
import java.sql.*;

@WebServlet("/file")
public class FileServlet extends HttpServlet {
    @Override protected void doGet(HttpServletRequest req,HttpServletResponse resp) throws IOException {
        if (!Boolean.TRUE.equals(req.getSession(false)==null?null:req.getSession(false).getAttribute("loggedIn"))) { resp.sendError(403,"Login required"); return; }
        int id; try { id=Integer.parseInt(req.getParameter("id")); } catch(Exception e) { resp.sendError(400,"Invalid file id"); return; }
        try(Connection con=Db.getConnection(); PreparedStatement ps=con.prepareStatement("SELECT original_name,stored_name,file_path,file_type FROM student_files WHERE id=?")) {
            ps.setInt(1,id); try(ResultSet rs=ps.executeQuery()) {
                if(!rs.next()) { resp.sendError(404,"File not found"); return; }
                String stored=Paths.get(rs.getString("stored_name")).getFileName().toString();
                // Files are stored in the shared project uploads folder; configure this absolute path for deployment.
                Path path=Paths.get(getServletContext().getRealPath("/"),"..","..","uploads",stored).normalize();
                if(!Files.isRegularFile(path)) { resp.sendError(404,"Stored file not found. Configure upload path."); return; }
                resp.setContentType(rs.getString("file_type")); resp.setHeader("X-Content-Type-Options","nosniff");
                resp.setHeader("Content-Disposition","inline; filename=""+rs.getString("original_name").replaceAll("[\"\r\n]","_")+""");
                try(InputStream in=Files.newInputStream(path); OutputStream out=resp.getOutputStream()) { in.transferTo(out); }
            }
        } catch(SQLException e) { resp.sendError(500,"Database error"); }
    }
}
