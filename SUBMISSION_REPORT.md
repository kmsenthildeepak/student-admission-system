# U21CS501 Web Technology — Assignment II

## Project Title
Student Admission and Profile Management System

## Question 1 — HTML, CSS and JavaScript Registration Form (8 Marks)

**Aim:** To create a responsive student registration form with required-field, email, password-strength and file validation.

**Procedure:**
1. Create fields for name, roll number, email, course, password and optional document upload in `frontend/index.html`.
2. Style the form, input controls, buttons, hints and error messages in `frontend/style.css`.
3. In `frontend/script.js`, prevent the default submission, clear old errors, read field values and validate each value.
4. Show a field-specific message when input is invalid; show a success message only when all client-side checks pass.

**Code explanation:** `index.html` defines semantic labels and controls. `style.css` uses a centered card, focus styles and a media query for narrow screens. `script.js` uses a regular expression to validate email and password rules, then checks MIME type and size for the selected file. Client-side validation is for usability and must not replace server-side validation.

**Expected result:** The browser displays a responsive registration form and gives field-specific errors for invalid input.

## Question 2 — PHP and MySQL CRUD with File Upload (16 Marks)

**Aim:** To build a PHP student management page that creates, views, edits and deletes student records and stores metadata for uploaded documents.

**Procedure:**
1. Run `database.sql` to create the `student_portal` database and its tables.
2. Configure the database username and password in `php/db.php`.
3. Serve the folder using Apache/PHP (for example, XAMPP).
4. Log in using the classroom demo credentials documented in `README.md`.
5. Add a student, edit a record, view the uploaded file, and test deletion.

**Code explanation:** `db.php` creates a PDO database connection with exception mode. `index.php` checks the demo session, performs CRUD operations with prepared statements, validates input, and handles uploads. `file.php` checks the session and retrieves stored file metadata before streaming a file. The uploaded filename is randomized to avoid overwriting another file. Output is escaped before it is inserted into HTML.

**Expected result:** The PHP page lists students and provides add/edit/delete/view functions. File metadata is stored in `student_files` and associated with the student by a foreign key.

## Question 3 — Java Servlet, JDBC and JSP (16 Marks)

**Aim:** To retrieve student records and file metadata from MySQL using JDBC and display them dynamically through JSP, with session checks.

**Procedure:**
1. Configure the JDBC credentials and upload-directory path for the local machine.
2. Build the Maven project in the `servlet` directory using `mvn clean package`.
3. Deploy `target/student-profile-portal.war` to Tomcat 10.1 or another Jakarta Servlet 6 compatible container.
4. Open `/login`, sign in using the classroom demo credentials documented in `README.md`, and view the student list.
5. Test the profile/file links and confirm that unauthenticated requests are redirected to login.

**Code explanation:** `Db.java` opens a JDBC connection. `LoginServlet.java` checks the demo credentials and creates a session. `StudentServlet.java` verifies the session, queries students and associated file metadata with a prepared statement, then forwards the result to `WEB-INF/students.jsp`. The JSP uses a loop and conditional checks to show multiple records and image/document links. `FileServlet.java` verifies the session and streams the requested file.

**Expected result:** An authenticated user sees a dynamic list of student profiles and associated files retrieved from MySQL.

## Database Schema

`database.sql` creates:
- `students`: student name, unique roll number, email, course and creation time.
- `student_files`: original and stored filenames, path, MIME type, upload date and a foreign key to `students`.
- `portal_users`: username and password hash columns reserved for database-backed accounts.

## Screenshots Required

Capture the actual browser output on your computer after starting each application. Save images in the `screenshots/` directory and add them to your final report:
1. Registration form (empty/default state).
2. Registration form showing validation errors.
3. Registration form showing successful validation.
4. PHP login and student list.
5. PHP add/edit form and uploaded file link.
6. Servlet login page.
7. Servlet student profile list showing database records.
8. Image/document opened or streamed through the file servlet.

Do not use sample or fabricated images as evidence that the PHP/Servlet applications ran. Those screenshots require your local MySQL, PHP/Apache and Tomcat environment.

## Conclusion
The Student Admission and Profile Management System demonstrates form design and client-side validation using HTML, CSS and JavaScript. PHP and MySQL provide student CRUD operations and store file metadata. Java Servlets and JDBC retrieve student profiles dynamically and stream associated files. Session checks and file validation provide basic protection for this classroom project.

## GitHub Repository
After testing, upload the complete project folder to your GitHub repository and paste the real repository URL here: **[Add your GitHub repository URL]**.
