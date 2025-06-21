<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id = $id");

header("Location: ../../dashboard/data_users.php");
exit;
?>
