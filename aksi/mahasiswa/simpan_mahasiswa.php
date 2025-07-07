<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../index.php");
  exit;
}

// Ambil dan sanitasi input
$nim = mysqli_real_escape_string($conn, $_POST['nim']);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
$jurusan_id = intval($_POST['jurusan_id']);

$foto_nama = null;

// Proses upload foto jika ada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $tmp = $_FILES['foto']['tmp_name'];
  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $foto_nama = uniqid('mhs_') . '.' . strtolower($ext);
  $tujuan = '../../upload/' . $foto_nama;

  if (!move_uploaded_file($tmp, $tujuan)) {
    echo "Gagal mengunggah foto.";
    exit;
  }
}

// Query simpan ke database
$query = "
  INSERT INTO mahasiswa (nim, nama, tanggal_lahir, jenis_kelamin, jurusan_id, foto)
  VALUES ('$nim', '$nama', '$tanggal_lahir', '$jenis_kelamin', $jurusan_id, " . ($foto_nama ? "'$foto_nama'" : "NULL") . ")
";

if (mysqli_query($conn, $query)) {
  header("Location: ../../dashboard/data_mahasiswa.php");
  exit;
} else {
  echo "Gagal menyimpan data: " . mysqli_error($conn);
}
?>
