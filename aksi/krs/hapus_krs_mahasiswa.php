<?php
session_start();
include '../../koneksi.php';

// Pastikan user mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'] ?? null;

// Ambil ID tahun ajaran aktif
$ta = mysqli_query($conn, "SELECT id FROM tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
$tahun = mysqli_fetch_assoc($ta);
$tahun_ajaran_id = $tahun['id'];

// Proses hapus semua KRS semester aktif milik mahasiswa
if ($mahasiswa_id && $tahun_ajaran_id) {
  mysqli_query($conn, "DELETE FROM krs WHERE mahasiswa_id = $mahasiswa_id AND tahun_ajaran_id = $tahun_ajaran_id");
}

header("Location: ../../dashboard/mahasiswa/krs_saya.php?pesan=hapus");
exit;
