<?php
session_start();
include '../koneksi.php';

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$username = $_SESSION['username'];

// Ambil informasi mahasiswa
$data_mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, nim FROM mahasiswa WHERE id = $mahasiswa_id"));

// Hitung jumlah data terkait mahasiswa
$total_krs = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM krs WHERE mahasiswa_id = $mahasiswa_id"));
$total_nilai = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM nilai WHERE mahasiswa_id = $mahasiswa_id"));
$total_jadwal = mysqli_num_rows(mysqli_query($conn, "
  SELECT DISTINCT j.id 
  FROM jadwal j
  JOIN krs k ON j.kelas_id = k.kelas_id
  WHERE k.mahasiswa_id = $mahasiswa_id
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mahasiswa</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Selamat Datang, <?= htmlspecialchars($data_mhs['nama']) ?></h1>
      <p>Mahasiswa • NIM: <?= htmlspecialchars($data_mhs['nim']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="dashboard_mahasiswa.php" class="active">🏠 Dashboard</a>
      <a href="mahasiswa/krs_saya.php">📄 KRS</a>
      <a href="mahasiswa/jadwal_saya.php">📅 Jadwal Kuliah</a>
      <a href="mahasiswa/nilai_saya.php">📝 Nilai</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Dashboard Mahasiswa</h2>
        <div class="dashboard-cards">
          <div class="card">
            <h3>Total KRS</h3>
            <p><?= $total_krs ?></p>
          </div>
          <div class="card">
            <h3>Total Nilai</h3>
            <p><?= $total_nilai ?></p>
          </div>
          <div class="card">
            <h3>Total Jadwal</h3>
            <p><?= $total_jadwal ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
