<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$id = intval($_GET['id']);

// Cek kepemilikan data
$cek = mysqli_query($conn, "SELECT * FROM krs WHERE id = $id AND mahasiswa_id = $mahasiswa_id");
if (mysqli_num_rows($cek) === 0) {
  echo "<script>alert('Akses ditolak!'); window.location.href='../../dashboard/mahasiswa/krs_saya.php';</script>";
  exit;
}

mysqli_query($conn, "DELETE FROM krs WHERE id = $id");
header("Location: ../../dashboard/mahasiswa/krs_saya.php");
exit;
