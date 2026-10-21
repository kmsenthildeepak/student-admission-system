# Student Admission and Profile Management System

This assignment has three separate parts: `frontend` (HTML/CSS/JavaScript), `php` (PHP + MySQL CRUD and file uploads), and `servlet` (Jakarta Servlet + JDBC + JSP). They share the SQL schema but are run separately.

## 1. Database
1. Start MySQL.
2. Run `database.sql` in MySQL Workbench or phpMyAdmin.
3. Update database credentials in `php/db.php` and `servlet/src/main/java/com/college/portal/Db.java`.

## 2. Front-end question
Open `frontend/index.html` in a browser. The form validates required fields, email, password strength and file type/size. This standalone page demonstrates client-side validation only; it does not save a record.

## 3. PHP CRUD and upload question
1. Install XAMPP (Apache + PHP + MySQL).
2. Copy this project folder under `xampp/htdocs/` or copy the `php` folder and the shared `uploads` folder into a matching structure.
3. Enable PHP PDO MySQL and Fileinfo extensions; ensure `uploads/` is writable by the web server.
4. Visit `http://localhost/student-admission-system/php/` (adjust URL to the actual folder).
5. Demo credentials: `admin` / `Admin@123`.
6. Add, edit, view and delete student records. Uploaded files are limited to JPG/PNG/PDF and 2 MB; random stored filenames prevent overwrite collisions.

**Important:** The simple hard-coded PHP demo login is for classroom demonstration only. Replace it with a database-backed account system and proper rate limiting before real use.

## 4. Servlet/JSP question
Requirements: JDK 17+, Maven, MySQL and a Jakarta EE 10 compatible container such as Tomcat 10.1.
1. Ensure the schema exists and update `Db.java` credentials.
2. From `servlet/`, run `mvn clean package`.
3. Deploy `target/student-profile-portal.war` to Tomcat 10.1.
4. Open `http://localhost:8080/student-profile-portal/login`.
5. Demo credentials: `student` / `Student@123`.

The servlet reads student records and file metadata using JDBC and displays them with JSP loops/conditionals. `LoginServlet` creates a session; `StudentServlet` and `FileServlet` require that session. The file servlet's upload path must be configured for your local deployment: the PHP app and servlet app should point to the same private uploads directory. Do not expose the upload directory as a public static folder in a real deployment.

## Code explanation
- `frontend/index.html`: registration fields and file input.
- `frontend/style.css`: responsive layout, input styles and error-message styles.
- `frontend/script.js`: required-field, email, password and file validation.
- `database.sql`: `students`, `student_files`, and `portal_users` tables; foreign key links files to students.
- `php/db.php`: PDO connection with exceptions and prepared statement support.
- `php/index.php`: session check, add/update/delete/view CRUD, server-side validation, file upload checks and HTML-escaped output.
- `php/file.php`: requires login and streams a file using stored metadata.
- `Db.java`: JDBC connection.
- `LoginServlet.java`: checks demo credentials and creates a session.
- `StudentServlet.java`: session guard, prepared SQL query, loop through results, forwards records to JSP.
- `FileServlet.java`: session guard, retrieves file metadata and streams file bytes.
- `students.jsp`: loops over student rows and conditionally displays images or file links.

## Screenshots to attach
Run each part and take genuine browser screenshots:
1. Front-end registration form (empty form).
2. Validation messages for invalid email/password.
3. Successful client-side validation message.
4. PHP admin login.
5. PHP add/edit student form and record list.
6. Uploaded file link or image.
7. Servlet login page.
8. Servlet student profile list showing records and file links/images.

Do not submit mock screenshots as real output. Add your own screenshots to the assignment document after running the applications.

## Conclusion (3–5 lines)
The Student Admission and Profile Management System demonstrates form design and client-side validation using HTML, CSS and JavaScript. PHP and MySQL provide student CRUD operations and store file metadata. Java Servlets and JDBC retrieve student profiles dynamically and stream associated files. Session checks and file validation improve basic access control and upload safety. The project can be extended with database-backed authentication and role-based authorization.

## GitHub
Create a new GitHub repository, upload the project files, and replace the placeholder in your submission with your actual repository URL. Do not submit a guessed repository link.
