<?php
session_start();
include '../koneksi.php';

// Cek apakah user sudah login dan berperan sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

// Ambil total data dari berbagai tabel
$mahasiswa = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM mahasiswa"));
$dosen = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM dosen"));
$jurusan = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM jurusan"));
$matakuliah = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM matakuliah"));
$kelas = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM kelas"));
$jadwal = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM jadwal"));
$nilai = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM nilai"));
$tahun_ajaran = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tahun_ajaran"));
$users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" class="logo-fixed" alt="Logo">
    </div>
    <div class="header-center">
      <h1>Sistem Akademik - Admin</h1>
      <p>Halo, <strong><?= $_SESSION['username'] ?></strong> (Admin)</p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="admin_dashboard.php" class="active">🏠 Dashboard</a>
      <a href="data_mahasiswa.php">👨‍🎓 Mahasiswa</a>
      <a href="data_dosen.php">👨‍🏫 Dosen</a>
      <a href="data_jurusan.php">📚 Jurusan</a>
      <a href="data_matakuliah.php">📖 Mata Kuliah</a>
      <a href="data_kelas.php">🏫 Kelas</a>
      <a href="data_jadwal.php">🗓 Jadwal</a>
      <a href="data_nilai.php">📝 Nilai</a>
      <a href="data_tahun_ajaran.php">📆 Tahun Ajaran</a>
      <a href="data_users.php">🔐 User Login</a>

      <!-- Logout langsung setelah menu terakhir -->
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2>Dashboard Admin</h2>

        <div class="dashboard-cards">
          <div class="card"><h3>Mahasiswa</h3><p><?= $mahasiswa ?></p></div>
          <div class="card"><h3>Dosen</h3><p><?= $dosen ?></p></div>
          <div class="card"><h3>Jurusan</h3><p><?= $jurusan ?></p></div>
          <div class="card"><h3>Mata Kuliah</h3><p><?= $matakuliah ?></p></div>
          <div class="card"><h3>Kelas</h3><p><?= $kelas ?></p></div>
          <div class="card"><h3>Jadwal</h3><p><?= $jadwal ?></p></div>
          <div class="card"><h3>Nilai</h3><p><?= $nilai ?></p></div>
          <div class="card"><h3>Tahun Ajaran</h3><p><?= $tahun_ajaran ?></p></div>
          <div class="card"><h3>User Login</h3><p><?= $users ?></p></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
