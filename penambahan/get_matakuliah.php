<?php
include '../koneksi.php';

if (isset($_GET['mahasiswa_id'])) {
  $mhs_id = $_GET['mahasiswa_id'];

  // Ambil jurusan_id dari mahasiswa
  $mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jurusan_id FROM mahasiswa WHERE id = '$mhs_id'"));

  // Ambil mata kuliah berdasarkan jurusan
  $query = mysqli_query($conn, "SELECT id, nama_mk FROM matakuliah WHERE jurusan_id = '{$mhs['jurusan_id']}'");

  $output = "<option value=''>-- Pilih Mata Kuliah --</option>";
  while ($mk = mysqli_fetch_assoc($query)) {
    $output .= "<option value='{$mk['id']}'>{$mk['nama_mk']}</option>";
  }

  echo $output;
}
?>