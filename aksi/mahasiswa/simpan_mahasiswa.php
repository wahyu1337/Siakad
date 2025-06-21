<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$jurusan_id = $_POST['jurusan_id'];

mysqli_query($conn, "INSERT INTO mahasiswa (nim, nama, jenis_kelamin, jurusan_id) 
VALUES ('$nim', '$nama', '$jenis_kelamin', '$jurusan_id')");

header("Location: ../../dashboard/data_mahasiswa.php");
exit;
?>
