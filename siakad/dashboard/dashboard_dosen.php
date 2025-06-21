<?php
session_start();
include '../koneksi.php';

// Cek login dan role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
  header("Location: ../index.php");
  exit;
}

// Ambil dosen_id dari session
if (!isset($_SESSION['dosen_id'])) {
  echo "<p style='color:red;text-align:center;'>Dosen ID tidak ditemukan di session!</p>";
  echo "<p style='text-align:center;'>Silakan logout lalu login kembali.</p>";
  exit;
}

$dosen_id = $_SESSION['dosen_id'];
$username = $_SESSION['username'];

// Ambil info dosen
$result = mysqli_query($conn, "SELECT nama, email, bidang FROM dosen WHERE id = $dosen_id");
if (!$result || mysqli_num_rows($result) === 0) {
  echo "<p style='color:red;text-align:center;'>Data dosen tidak ditemukan di database!</p>";
  exit;
}
$dosen = mysqli_fetch_assoc($result);

// Hitung jumlah jadwal
$jumlah_jadwal = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM jadwal WHERE dosen_id = $dosen_id"));

// Hitung jumlah nilai yang sudah diinput oleh dosen (via jadwal)
$jumlah_nilai = mysqli_num_rows(mysqli_query($conn, "
  SELECT n.id FROM nilai n
  JOIN jadwal j ON n.matakuliah_id = j.matakuliah_id
  WHERE j.dosen_id = $dosen_id
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Dosen</title>
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
      <h1>Selamat Datang, <?= htmlspecialchars($dosen['nama']) ?></h1>
      <p>Dosen - <?= htmlspecialchars($dosen['bidang']) ?> | <?= htmlspecialchars($dosen['email']) ?></p>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <a href="dashboard_dosen.php" class="active">🏠 Dashboard</a>
      <a href="dosen/jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="dosen/input_nilai.php">📝 Input Nilai</a>
      <a href="dosen/mahasiswa_perkelas.php">👨‍🎓 Mahasiswa Perkelas</a>
      <form action="../logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Dashboard Dosen</h2>
        <div class="dashboard-cards">
          <div class="card">
            <h3>Jadwal Mengajar</h3>
            <p><?= $jumlah_jadwal ?></p>
          </div>
          <div class="card">
            <h3>Nilai Diinput</h3>
            <p><?= $jumlah_nilai ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
