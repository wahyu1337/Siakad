<?php
session_start();
include '../../../koneksi.php';

if (!isset($_SESSION['dosen_id'])) {
  header("Location: ../../../index.php");
  exit;
}

$dosen_id = $_SESSION['dosen_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$bidang = mysqli_real_escape_string($conn, $_POST['bidang']);

$query = "UPDATE dosen SET nama='$nama', email='$email', bidang='$bidang' WHERE id=$dosen_id";

if (mysqli_query($conn, $query)) {
  $_SESSION['username'] = $nama;
  echo "<script>alert('Profil berhasil diperbarui'); window.location='profile_dosen.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui profil'); window.history.back();</script>";
}
?>