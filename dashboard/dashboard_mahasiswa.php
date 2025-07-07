<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
  header("Location: ../index.php");
  exit;
}

$mahasiswa_id = $_SESSION['mahasiswa_id'] ?? null;
if (!$mahasiswa_id) {
  echo "<p style='color:red; text-align:center;'>Akun Anda tidak memiliki ID mahasiswa yang valid. Silakan hubungi admin.</p>";
  exit;
}

$username = $_SESSION['username'];

// Ambil info mahasiswa
$data_mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, nim FROM mahasiswa WHERE id = $mahasiswa_id"));

// Total KRS
$total_krs = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM krs WHERE mahasiswa_id = $mahasiswa_id"));

// Total Nilai
$total_nilai = mysqli_num_rows(mysqli_query($conn, "
  SELECT n.id 
  FROM nilai n 
  JOIN krs k ON n.krs_id = k.id 
  WHERE k.mahasiswa_id = $mahasiswa_id
"));

// Total Jadwal
$total_jadwal = mysqli_num_rows(mysqli_query($conn, "
  SELECT DISTINCT j.id 
  FROM jadwal j
  JOIN krs k ON j.id = k.jadwal_id
  WHERE k.mahasiswa_id = $mahasiswa_id
"));

// Total SKS Lulus
$sks_lulus = 0;
$result = mysqli_query($conn, "
  SELECT mk.sks, n.nilai_angka 
  FROM nilai n
  JOIN krs k ON n.krs_id = k.id
  JOIN matakuliah mk ON k.matakuliah_id = mk.id
  WHERE k.mahasiswa_id = $mahasiswa_id AND n.nilai_angka >= 60
");


while ($row = mysqli_fetch_assoc($result)) {
  $sks_lulus += intval($row['sks']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mahasiswa</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/layout.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #f9f9f9;
      border-left: 6px solid #2c3e50;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.06);
    }
    .card h3 {
      margin: 0 0 10px;
      font-size: 1.2em;
      color: #333;
    }
    .card p {
      font-size: 2em;
      font-weight: bold;
      color: #2c3e50;
    }
  </style>
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
      <a href="mahasiswa/profil/profile_mahasiswa.php">👤 Profil</a>
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
          <div class="card">
            <h3>SKS Lulus</h3>
            <p><?= $sks_lulus ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>