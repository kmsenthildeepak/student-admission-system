package com.college.portal;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public final class Db {
    private Db() {}
    public static Connection getConnection() throws SQLException {
        // Update username/password to match the local MySQL installation.
        return DriverManager.getConnection("jdbc:mysql://localhost:3306/student_portal?useSSL=false&serverTimezone=UTC", "root", "");
    }
}
