<?php
include '../koneksi.php';

if (isset($_GET['matakuliah_id'])) {
  $matakuliah_id = intval($_GET['matakuliah_id']);

  // Cari jurusan_id dari matakuliah
  $result = mysqli_query($conn, "SELECT jurusan_id FROM matakuliah WHERE id = $matakuliah_id");
  $data = mysqli_fetch_assoc($result);

  if ($data) {
    $jurusan_id = $data['jurusan_id'];

    // Ambil semua kelas berdasarkan jurusan
    $kelas = mysqli_query($conn, "SELECT id, nama_kelas FROM kelas WHERE jurusan_id = $jurusan_id");

    while ($k = mysqli_fetch_assoc($kelas)) {
      echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
    }
  }
}
?>