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

// Hitung jumlah nilai yang sudah diinput oleh dosen (via jadwal & krs)
$jumlah_nilai = mysqli_num_rows(mysqli_query($conn, "
  SELECT n.id FROM nilai n
  JOIN krs k ON n.krs_id = k.id
  JOIN jadwal j ON k.jadwal_id = j.id
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
  <style>
    .sidebar-user {
      color: white;
      text-align: center;
      padding: 15px;
      background-color: #4f46e5;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .sidebar-user strong {
      font-size: 1.1em;
      display: block;
    }
    .sidebar-user small {
      color: #ccc;
      font-size: 0.9em;
    }
    .dashboard-cards {
      display: flex;
      gap: 20px;
      margin-top: 20px;
      flex-wrap: wrap;
    }
    .card {
      flex: 1;
      min-width: 200px;
      background: #eef4fd;
      padding: 20px;
      border-left: 5px solid #2c3e50;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    .card h3 {
      margin: 0;
      font-size: 1.2em;
    }
    .card p {
      font-size: 2em;
      color: #2c3e50;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="../logo.png" alt="Logo" class="logo-fixed">
    </div>
    <div class="header-center">
      <h1>Dashboard Dosen</h1>
    </div>
  </header>

  <div class="main-content">
    <div class="sidebar">
      <div class="sidebar-user">
        <strong><?= htmlspecialchars($dosen['nama']) ?></strong>
        <small><?= htmlspecialchars($dosen['email']) ?></small>
      </div>
      <a href="dashboard_dosen.php" class="active">🏠 Dashboard</a>
      <a href="dosen/jadwal_saya.php">📅 Jadwal Saya</a>
      <a href="dosen/input_nilai.php">📝 Input Nilai</a>
      <a href="dosen/profil/profile_dosen.php">👤 Profil</a>
      <form action="../logout.php" method="post">
        <button type="submit" class="logout-button">🔓 Logout</button>
      </form>
    </div>

    <div class="page-wrapper">
      <div class="container">
        <h2 class="form-title">Selamat Datang, <?= htmlspecialchars($dosen['nama']) ?></h2>
        <p>Bidang: <?= htmlspecialchars($dosen['bidang']) ?></p>

        <div class="dashboard-cards">
          <div class="card">
            <h3>Total Jadwal Mengajar</h3>
            <p><?= $jumlah_jadwal ?></p>
          </div>
          <div class="card">
            <h3>Jumlah Nilai Diinput</h3>
            <p><?= $jumlah_nilai ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>