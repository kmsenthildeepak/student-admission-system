<?php
session_start();
require __DIR__ . '/db.php';
// Demo login: username admin, password Admin@123. Change this before deployment.
if (isset($_POST['login'])) {
  if ($_POST['username'] === 'admin' && password_verify($_POST['password'], password_hash('Admin@123', PASSWORD_DEFAULT))) {
    $_SESSION['logged_in'] = true; header('Location: index.php'); exit;
  }
  $error = 'Invalid username or password.';
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: index.php'); exit; }
if (empty($_SESSION['logged_in'])): ?>
<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="style.css"><body><main class="wrap"><h1>Student Portal Admin</h1><?php if(isset($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?><form method="post"><label>Username</label><input name="username" required><label>Password</label><input type="password" name="password" required><button name="login">Login</button><p>Demo credentials: admin / Admin@123</p></form></main></body></html>
<?php exit; endif;
$uploadDir = __DIR__ . '/../uploads/'; if (!is_dir($uploadDir)) mkdir($uploadDir, 0750, true);
function clean($value) { return trim((string)$value); }
// Delete a record and its files. Prepared statements prevent SQL injection.
if (isset($_GET['delete'])) {
  $id=(int)$_GET['delete']; $q=$pdo->prepare('SELECT stored_name FROM student_files WHERE student_id=?'); $q->execute([$id]);
  foreach($q->fetchAll() as $f) { $path=$uploadDir . basename($f['stored_name']); if(is_file($path)) unlink($path); }
  $pdo->prepare('DELETE FROM students WHERE id=?')->execute([$id]); header('Location: index.php'); exit;
}
$message='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_student'])) {
  $id=(int)($_POST['id']??0); $name=clean($_POST['name']??''); $roll=clean($_POST['roll_number']??''); $email=clean($_POST['email']??''); $course=clean($_POST['course']??'');
  if (!$name || !$roll || !filter_var($email,FILTER_VALIDATE_EMAIL) || !$course) { $message='Please provide valid required fields.'; }
  else {
    try {
      if ($id) { $stmt=$pdo->prepare('UPDATE students SET name=?,roll_number=?,email=?,course=? WHERE id=?'); $stmt->execute([$name,$roll,$email,$course,$id]); $studentId=$id; }
      else { $stmt=$pdo->prepare('INSERT INTO students(name,roll_number,email,course) VALUES(?,?,?,?)'); $stmt->execute([$name,$roll,$email,$course]); $studentId=(int)$pdo->lastInsertId(); }
      if (!empty($_FILES['document']['name'])) {
        $file=$_FILES['document']; $max=2*1024*1024; $allowed=['image/jpeg'=>'jpg','image/png'=>'png','application/pdf'=>'pdf'];
        if ($file['error']!==UPLOAD_ERR_OK || $file['size']>$max) throw new RuntimeException('Upload failed or file exceeds 2 MB.');
        $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
        if (!isset($allowed[$mime])) throw new RuntimeException('Only JPG, PNG and PDF files are allowed.');
        // Random server-side name prevents collisions and overwriting existing files.
        $stored=bin2hex(random_bytes(16)).'.'.$allowed[$mime];
        if (!move_uploaded_file($file['tmp_name'],$uploadDir.$stored)) throw new RuntimeException('Could not save uploaded file.');
        $path='uploads/'.$stored;
        $stmt=$pdo->prepare('INSERT INTO student_files(student_id,original_name,stored_name,file_path,file_type) VALUES(?,?,?,?,?)');
        $stmt->execute([$studentId,basename($file['name']),$stored,$path,$mime]);
      }
      header('Location: index.php'); exit;
    } catch (Throwable $e) { $message='Could not save record: '.$e->getMessage(); }
  }
}
$edit=null; if(isset($_GET['edit'])) { $s=$pdo->prepare('SELECT * FROM students WHERE id=?'); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$students=$pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Student Management</title><link rel="stylesheet" href="style.css"></head><body><main class="wrap"><header><h1>Student Management</h1><a href="?logout=1">Logout</a></header>
<?php if($message) echo '<p class="error">'.htmlspecialchars($message).'</p>'; ?>
<section class="panel"><h2><?= $edit?'Edit':'Add' ?> Student</h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><label>Name</label><input name="name" required maxlength="100" value="<?= htmlspecialchars($edit['name']??'') ?>"><label>Roll number</label><input name="roll_number" required maxlength="30" value="<?= htmlspecialchars($edit['roll_number']??'') ?>"><label>Email</label><input name="email" type="email" required value="<?= htmlspecialchars($edit['email']??'') ?>"><label>Course</label><input name="course" required value="<?= htmlspecialchars($edit['course']??'') ?>"><label>Profile image / ID proof / certificate (optional)</label><input type="file" name="document" accept=".jpg,.jpeg,.png,.pdf"><small>JPG, PNG or PDF; maximum 2 MB.</small><button name="save_student">Save Student</button></form></section>
<section class="panel"><h2>Student Records</h2><div class="table-scroll"><table><tr><th>ID</th><th>Name</th><th>Roll</th><th>Email</th><th>Course</th><th>Files</th><th>Actions</th></tr>
<?php foreach($students as $s): ?><tr><td><?= (int)$s['id'] ?></td><td><?= htmlspecialchars($s['name']) ?></td><td><?= htmlspecialchars($s['roll_number']) ?></td><td><?= htmlspecialchars($s['email']) ?></td><td><?= htmlspecialchars($s['course']) ?></td><td><?php $f=$pdo->prepare('SELECT * FROM student_files WHERE student_id=?');$f->execute([$s['id']]);foreach($f as $file): ?><a href="file.php?id=<?= (int)$file['id'] ?>"><?= htmlspecialchars($file['original_name']) ?></a><br><?php endforeach; ?></td><td><a href="?edit=<?= (int)$s['id'] ?>">Edit</a> | <a onclick="return confirm('Delete this student and files?')" href="?delete=<?= (int)$s['id'] ?>">Delete</a></td></tr><?php endforeach; ?>
</table></div></section></main></body></html>
