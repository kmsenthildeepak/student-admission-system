<?php
session_start(); if (empty($_SESSION['logged_in'])) { http_response_code(403); exit('Login required'); }
require __DIR__.'/db.php'; $q=$pdo->prepare('SELECT * FROM student_files WHERE id=?'); $q->execute([(int)($_GET['id']??0)]); $file=$q->fetch();
if (!$file) { http_response_code(404); exit('File not found'); }
$path=__DIR__.'/../'.str_replace('..','', $file['file_path']); if (!is_file($path)) { http_response_code(404); exit('Stored file not found'); }
header('Content-Type: '.$file['file_type']); header('X-Content-Type-Options: nosniff'); header('Content-Disposition: inline; filename="'.rawurlencode(basename($file['original_name'])).'"'); readfile($path);
