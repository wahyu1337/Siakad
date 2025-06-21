<?php
include '../../koneksi.php';

if (!isset($_GET['id'])) {
  echo "<script>alert('ID tidak ditemukan'); window.location='../../dashboard/data_matakuliah.php';</script>";
  exit;
}

$id = $_GET['id'];

$cek = mysqli_query($conn, "SELECT * FROM matakuliah WHERE id = '$id'");
if (mysqli_num_rows($cek) == 0) {
  echo "<script>alert('Data tidak ditemukan'); window.location='../../dashboard/data_matakuliah.php';</script>";
  exit;
}

$hapus = mysqli_query($conn, "DELETE FROM matakuliah WHERE id = '$id'");

if ($hapus) {
  echo "<script>alert('Mata kuliah berhasil dihapus'); window.location='../../dashboard/data_matakuliah.php';</script>";
} else {
  echo "<script>alert('Gagal menghapus mata kuliah!'); window.location='../../dashboard/data_matakuliah.php';</script>";
}
?>
